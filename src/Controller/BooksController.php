<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CreateTokenAccessFirstService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BooksController extends AbstractController
{
    /**
     * @Route("/books", name="books")
     */
    public function index(Request $request): Response
    {
        $headers = $request->headers->get('Authorization');
        $token = substr($headers, 7);

        $result = CreateTokenAccessFirstService::isTokenValid($token, 'access');


        if( !$result['the_token_has_expired'] && $result['the_signature_is_valid']){
            return $this->json([
                'books' => 'this is work',
                'message' => 'Welcome to your new controller!',
                'path' => 'src/Controller/BooksController.php',
            ]);
        } else{
            return $this->json($result);
        }



      
    }
}
