<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPassAction
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    public function __construct(ValidatorInterface $validator,
                                UserPasswordEncoderInterface $userPasswordEncoder,
                                EntityManagerInterface $entityManager,
                                JWTTokenManagerInterface $tokenManager)
    {

        $this->validator = $validator;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
    }

    public function __invoke(User $data)
    {

        $context['groups'] = ['put-reset-pass'];

        $this->validator->validate($data, $context);

        $data->setPassword(
            $this->userPasswordEncoder->encodePassword(
                $data, $data->getNewPass()
            )
        );

        $data->setPassChangeDate(time());

         $this->entityManager->flush();

         $token = $this->tokenManager->create($data);

         return new JsonResponse(['token' => $token]);

        //var_dump($data->getNewPass(), $data->getNewRetypedPass(), $data->getOldPass(), $data->getRetypedPassword());die;
    }
}