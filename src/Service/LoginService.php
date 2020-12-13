<?php
namespace App\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginService{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $emi;

    /**
     * @param EntityManagerInterface $emi
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $emi, UserPasswordEncoderInterface $encoder)
    {
        $this->emi = $emi;
        $this->encoder = $encoder;
    }

    /**
     *
     * @param string $email
     * @param string $password
     * @return object
     */
    public function login(string $email, string $password): object{
        try {
            $user = $this->emi->createQueryBuilder()
                ->select('user')
                ->from(User::class, 'user')
                ->where('user.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $e) {
            return (object) array('error' => 'user don t exist');
        }
        
        if(is_null( $user)){
            return (object) array('error' => 'user is empty');
        };

        $passwordValid = $this->encoder->isPasswordValid($user, $password);
        if($passwordValid){
            return $user; 
        }

        return (object) array('error' => 'password invalid');
    }
}