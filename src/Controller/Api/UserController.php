<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Entity\User;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends ApiController
{
    /**
     * @Route("/api/login", name="api_login")
     * @param UserInterface|null $user
     * @return Response
     */
    public function login (?UserInterface $user): Response
    {
        if (null === $user)
        {
            return $this->json([
                'message' => 'Missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new Response('Invalid api request. Please use json format', Response::HTTP_NOT_FOUND);
    }


    /**
     * @Rest\Post("/api/register", name="api_register")
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $encoder
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function register (Request $request, UserPasswordHasherInterface $encoder,
                              ValidatorInterface $validator)
    {
        $user = new User();
        $user
            ->setEmail($request->get('email'))
            ->setPlainPassword($request->get('password'))
            ->setRoles([User::ROLE_USER]);

        $errors = $validator->validate($user, null, ['User', 'registration']);
        if (count($errors) > 0)
        {
            return $this->respondValidationError($errors);
        }

        $user->setPassword($encoder->hashPassword($user, $user->getPlainPassword()));

        $this->em->persist($user);
        $this->em->flush();

        // TODO send email

        return $this->respondWithSuccess(sprintf($this->trans('user.successfullRegistration'), $user->getEmail()));
    }


    /**
     * @Rest\Get("/api/user/profile", name="api_user_profile")
     *
     * @return Response
     */
    public function profile ()
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $data = null;

        if ($user)
        {
            $data = [
                'email' => $user->getEmail(),
            ];
        }

        $response = [
            'status' => $user ? Response::HTTP_OK : Response::HTTP_NOT_FOUND,
            'error'  => $user ? true : false,
            'data'   => $data,
        ];

        return $this->sendResponse($response, ['groups' => 'user']);
    }


    /**
     * @Rest\Post("/api/user/update", name="api_user_update")
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param ValidatorInterface $validator
     * @return JsonResponse|Response
     */
    public function update (Request $request, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator)
    {
        $user = $this->getUser();

        $data = [
            'user'  => [
                'email'     => 'setEmail',
                'firstName' => 'setFirstName',
                'lastName'  => 'setLastName',
            ],
        ];

        if (!empty($request->get('password')))
        {
            $user->setPassword($userPasswordHasher->hashPassword($user, $request->get('password')));
        }

        foreach ($data['user'] as $field => $method)
        {
            if (!empty($request->get($field)))
            {
                $user->$method($request->get($field));
            }
        }

        $errors = $validator->validate($user, null, ['User', 'registration']);
        if (count($errors) > 0)
        {
            return $this->respondValidationError($errors);
        }

        $this->em->flush();

        return $this->respondWithSuccess(sprintf($this->trans('User %s successfully updated'), $user->getEmail()));
    }


    /**
     * @Rest\Post("/api/user/password/recover", name="api_user_password")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function recoverPassword (Request $request, UserRepository $userRepository, MailerInterface $mailer)
    {
        $email = $request->get('email');

        $user = $userRepository->findOneBy(['email' => $email]);

        $status = Response::HTTP_NOT_FOUND;
        $error  = true;
        $data   = false;

        if ($user)
        {
            $length                = 40;
            $forgottenPasswordCode = substr(bin2hex(random_bytes($length)), 0, $length);

            $user
                ->setForgottenPasswordCode($forgottenPasswordCode)
                ->setForgottenPasswordTime(new \DateTime());

            $this->em->flush();

            $email = (new TemplatedEmail())
                ->from(new Address($this->getParameter('app.sender_email'), $this->getParameter('app.sender_name')))
                ->to($user->getEmail())
                ->subject('Recuperar contraseña')
                ->htmlTemplate('emails/password-recover.html.twig')
                ->context([
                    'forgottenPasswordCode' => $forgottenPasswordCode,
                ]);

            $mailer->send($email);

            $status = Response::HTTP_OK;
            $error  = false;
            $data   = 'Email sent';
        }

        $response = [
            'status' => $status,
            'error'  => $error,
            'data'   => $data,
        ];

        return $this->sendResponse($response);
    }



    /**
     * @Rest\Post("/api/user/password/check", name="api_user_password_check")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function checkPasswordCode (Request $request, UserRepository $userRepository)
    {
        $forgottenPasswordCode = $request->get('code');
        $user                  = $userRepository->findOneBy(['forgottenPasswordCode' => $forgottenPasswordCode]);

        $status = Response::HTTP_NOT_FOUND;
        $error  = true;
        $data   = 'Code error';


        if ($user and !$user->isForgottenPasswordTimedOut($this->getParameter('app.user_password_timeout')))
        {
            $status = Response::HTTP_OK;
            $error  = false;
            $data   = $user;
        }

        $response = [
            'status' => $status,
            'error'  => $error,
            'data'   => $data,
        ];

        return $this->sendResponse($response, ['groups' => ['user']]);
    }



    /**
     * @Rest\Post("/api/user/password/update", name="api_user_password_update")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return JsonResponse|Response
     */
    public function updatePassword (Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $forgottenPasswordCode = $request->get('code');
        $user                  = $userRepository->findOneBy(['forgottenPasswordCode' => $forgottenPasswordCode]);

        $status = Response::HTTP_NOT_FOUND;
        $error  = true;
        $data   = 'Code error';

        if ($user and !$user->isForgottenPasswordTimedOut(3600))
        {
            $user
                ->setPassword($userPasswordHasher->hashPassword($user, $request->get('password')))
                ->setForgottenPasswordCode(null)
                ->setForgottenPasswordTime(null);

            $this->em->flush();

            $status = Response::HTTP_OK;
            $error  = false;
            $data   = $user;
        }

        $response = [
            'status' => $status,
            'error'  => $error,
            'data'   => $data,
        ];

        return $this->sendResponse($response, ['groups' => ['detail']]);
    }


    /**
     * @Rest\Post("/api/user/impersonate", name="api_user_impersonate")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param JWTTokenManagerInterface $JWTManager
     * @param RefreshTokenManagerInterface $refreshTokenManager
     * @return Response
     */
    public function impersonateUser (Request $request, UserRepository $userRepository,
                                     JWTTokenManagerInterface $JWTManager, RefreshTokenManagerInterface $refreshTokenManager)
    {
        $impersonateCode = $request->get('code');

        if ($impersonateCode)
        {
            $user = $userRepository->findOneBy(['impersonateCode' => $impersonateCode]);

            if ($user)
            {
                $user->setImpersonateCode(null);
                $tokenRefresh = $refreshTokenManager->create();
                $tokenRefresh->setRefreshToken();
                $tokenRefresh->setUsername($user->getUserIdentifier());
                $tokenRefresh->setValid(new \DateTime('+1 month')); // TODO get bundle config ttl
                $this->em->persist($tokenRefresh);
                $this->em->flush();

                return new JsonResponse([
                    'token'         => $JWTManager->create($user),
                    'refresh_token' => $tokenRefresh->getRefreshToken(), // TODO get bundle config key
                ]);
            }
        }

        $response = [
            'status' => Response::HTTP_NOT_FOUND,
            'error'  => true,
            'data'   => 'Invalid code',
        ];

        return $this->sendResponse($response);
    }
}
