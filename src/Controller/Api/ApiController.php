<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Traits\SerializerTrait;
use App\Traits\TranslatorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiController extends AbstractController
{
    use SerializerTrait, TranslatorTrait;

    protected EntityManagerInterface $em;
    protected LoggerInterface $logger;
    protected TranslatorInterface $translator;
    protected int $statusCode = 200;


    /**
     * ApiController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     */
    public function __construct (EntityManagerInterface $entityManager, LoggerInterface $logger,
                                 TranslatorInterface $translator)
    {
        $this->em     = $entityManager;
        $this->logger = $logger;
        $this->translator = $translator;
    }


    /**
     * Get a user from the Security Token Storage.
     *
     * @return UserInterface|User|object|null
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    public function getUser ()
    {
        return parent::getUser();
    }


    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode ()
    {
        return $this->statusCode;
    }


    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode (int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }


    public function response ($response, $context = [])
    {
        return $this->sendResponse($response, $context);
    }


    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $success
     * @return Response
     */
    public function respondWithSuccess (string $success)
    {
        $data = [
            'status' => $this->getStatusCode(),
            'error'  => false,
            'data'   => $success,
        ];

        return $this->response($data);
    }



    public function respondWithErrors ($errors)
    {
        $data = [
            'status' => $this->getStatusCode(),
            'error'  => true,
            'data'   => $errors,
        ];

        return $this->response($data);
    }


    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return Response
     */
    public function respondNotFound ($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }


    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return Response
     */
    public function respondUnauthorized ($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)
            ->respondWithErrors($message);
    }


    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param $errors
     * @return Response
     */
    public function respondValidationError ($errors)
    {
        if (is_a($errors, '\Symfony\Component\Validator\ConstraintViolationListInterface'))
        {
            $errors = $this->transformValidationErrors($errors);
        }

        return $this->setStatusCode(422)->respondWithErrors($errors);
    }


    protected function transformValidationErrors (\Symfony\Component\Validator\ConstraintViolationListInterface $validationErrors)
    {
        $errors = [];

        foreach ($validationErrors as $error)
        {
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errors;
    }
}
