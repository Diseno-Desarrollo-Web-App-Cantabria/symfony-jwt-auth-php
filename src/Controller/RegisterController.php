<?php
namespace App\Controller;
use App\Service\RegisterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(UserPasswordEncoderInterface $encoder, Request $request, EntityManagerInterface $emi): Response
    {
        $parameters = $request->request->all(); 
        $registerService = new RegisterService($encoder, $emi);
        $json = $registerService->register($parameters['name'], $parameters['email'], $parameters['password']);
 
        return $this->json($json);
    }
}
