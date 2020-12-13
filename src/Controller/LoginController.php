<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\LoginService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CreateTokenAccessFirstService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(UserPasswordEncoderInterface $encoder, Request $request, EntityManagerInterface $emi): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $loginService = new LoginService($emi, $encoder);
        $user = $loginService->login($email, $password);

        if($user instanceof User) {
          $refresh_jwt = CreateTokenAccessFirstService::createTokenRegisterUser($user->getId(), $user->getName(), 7776000, 'refresh');  // 90 days
          $access_jwt  = CreateTokenAccessFirstService::createTokenRegisterUser($user->getId(), $user->getName(), 1500,'access');  // 20 min
          return $this->json(['refresh_jwt' => $refresh_jwt, 'access_jwt' => $access_jwt]);
        };
       
        return $this->json($user);
    }
}
