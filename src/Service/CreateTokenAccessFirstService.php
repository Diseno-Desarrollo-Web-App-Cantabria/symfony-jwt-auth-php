<?php 
namespace App\Service;
use App\Entity\User;

class CreateTokenAccessFirstService{
 
    public static function createTokenRegisterUser($userid, $username, int $seconds, string $key)
    {
      $time = time() + $seconds; 
      $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
      $payload = json_encode(['userid'=>$userid, 'username'=>$username, "iat"=>$time]);
      
      // Encode header to Base64Url String
      $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
      // Encode Payload to Base64Url String
      $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
      // Create Signature Hash
      $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $key, true);
      // Encode Signature to Base64Url String
      $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
      
      // Create JWT Refresh Register Token
      $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
      
      return $jwt;
    }



    public static function isTokenValid($refresh_token, $key){
     
      function base64UrlEncode($text){return str_replace(['+', '/', '='],['-', '_', ''],base64_encode($text));}

      // split the token            
      $tokenParts = explode('.', $refresh_token);
      $header = base64_decode($tokenParts[0]);
      $payload = base64_decode($tokenParts[1]);
      $signatureProvided = $tokenParts[2];
  
      $obj = json_decode($payload);
      $timeBuildToken = (int) $obj->iat;
      $time = (int) time();
  
      $result = [];
      $result['obj'] = $obj;
    
      if($timeBuildToken < $time){
              $result['the_token_has_expired'] = true;
          } else {
              $result['the_token_has_expired'] = false;
          }
      
      // build a signature based on the header and payload using the secret
      $base64UrlHeader = base64UrlEncode($header);
      $base64UrlPayload = base64UrlEncode($payload);
      $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $key, true);
      $base64UrlSignature = base64UrlEncode($signature);
      
      // verify it matches the signature provided in the token
      $signatureValid = ($base64UrlSignature === $signatureProvided);
      
      
      if ($signatureValid) {
          $result['the_signature_is_valid'] = true;
      } else {
          $result['the_signature_is_valid'] = false;
      }

      return $result;
    }


}