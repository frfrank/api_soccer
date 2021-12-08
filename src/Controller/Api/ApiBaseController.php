<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Admin\Traits\SerializerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


/**
 * Class ApiController
 * @package App\Controller
 *
 * @Route("/api")
 */

class ApiBaseController extends AbstractController
{
    use SerializerTrait;


    protected $logger;
    private $em;


    /**
     * ApiController constructor.
     *
     * @param $logger
     *
     */
    public function __construct(LoggerInterface $logger, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->logger           = $logger;
        $this->userRepository   = $userRepository;
        $this->em = $em;
    }

    /**
     * @Route("/login", name="api-login",methods={"POST"})
     * @return Response
     */

    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder, JWTManager $JWT)
    {
        try {
            $code = 200;
            $data = json_decode($request->getContent(), true);
            $error = false;

            $this->logger->debug(serialize($data));

            $email = $data["email"];
            $password = $data["password"];

            $user = $this->em->getRepository(User::class)->findOneBy(array('email' => $email));

            $checkPassword = $passwordEncoder->isPasswordValid($user, $password);

            if ($user && $checkPassword) {
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $this->get('session')->set('_security_main', serialize($token));

                $response = [
                    'status' => $code,
                    'error' => $error,
                    'data' =>
                    [
                        "user" => $user,
                        'token' => $JWT->create($this->getUser()),
                    ],
                ];
            }
        } catch (\Exception $e) {
            $code    = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error   = true;
            $message = "Ocurrio un error" . " {'.$e->getMessage().'}";

            $response = [
                'status' => $code,
                'error' => $error,
                'messages' => $message
            ];                          
        }


        return $this->sendResponse($response, array('groups' => array('user_area')));
    }

      /**
     * @Route("/logout", name="api-logout", methods={"GET"})
     * @return Response
     */
    public function logout (Request $request)
    {
        try
        {
            $code    = Response::HTTP_OK;
            $error   = false;
            $message = "User successfully logout";
            $this->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();
        }
        catch (\Exception $e)
        {
            $code    = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error   = true;
            $message = "An error has occurred trying to login the user - Error: {'.$e->getMessage().'}";
        }

        $response = [
            'status' => $code,
            'error'  => $error,
            'data'   => $message,
        ];

        return $this->sendResponse($response, array('groups' => array('user_area')));
    }
}
