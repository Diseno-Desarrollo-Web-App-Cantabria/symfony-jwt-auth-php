<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Service\CreateTokenAccessFirstService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RefreshController extends AbstractController
{
    /**
     * @Route("/refresh", name="refresh")
     */
    public function index(Request $request): Response
    {
        $refresh_token = $request->query->get('token'); 

        $result = CreateTokenAccessFirstService::isTokenValid($refresh_token, 'refresh');

        if( $result['the_signature_is_valid'] && !$result['the_token_has_expired']){
            $new_access_token = CreateTokenAccessFirstService::createTokenRegisterUser( $result['obj']->userid,  $result['obj']->username, 1500, 'access');
            $result['new_access_token'] = $new_access_token;
            return $this->json($result);
        } else {
            return $this->json($result);
        }
    }
}
