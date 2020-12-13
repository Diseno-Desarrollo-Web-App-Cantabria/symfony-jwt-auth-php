<?php 
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterService{

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;
    /**
     * @var EntityManagerInterface
     */
    protected $emi;
    /**
     * RegisterService constructor
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $emi
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $emi)
    {
        $this->encoder = $encoder;
        $this->emi = $emi;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return array
     */
    public function register(string $name, string $email, string $password)
    {
       $user = new User();
       $user->setName($name);
       $user->setEmail($email);
       $user->setPlain($password);
       $user->setRoles(['ROLE_USER']); 

       $encoded = $this->encoder->encodePassword($user, $password);
       $user->setPassword($encoded);

       try{ 
        $this->emi->persist($user);
        $this->emi->flush();

       } catch (\Exception $e){
        return ['error'=>$e->getMessage()];
       }
      

       return [
               'id'=>$user->getId(),
               'name'=>$user->getName(),
               'email'=>$user->getEmail(),
               'password'=>$user->getPassword()
            ];
    }
}