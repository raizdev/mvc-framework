<?php
namespace StarreDEV\User\Service\Auth;

use Odan\Session\SessionInterface;
use StarreDEV\Framework\Interfaces\CustomResponseInterface;
use StarreDEV\Framework\Service\ValidationService;
use StarreDEV\User\Interfaces\UserInterface;
use StarreDEV\User\Exception\LoginException;
use StarreDEV\User\Model\UserModel;

/**
 * Class LoginService
 *
 * @package StarreDEV\User\Service\Auth
 */
class LoginService
{
    /**
     * LoginService constructor.
     *
     * @param UserModel   $userModel
     */
    public function __construct(
        private UserModel $userModel,
        private SessionInterface $session,
        private ValidationService $validationService
    ) {}

    /**
     * Login User.
     *
     * @return string|null
     */
    public function login(array $data)
    {
        $this->validationService->validate($data, [
            'username' => 'required',
            'password' => 'required'
        ]);
      
        if($this->validationService->getErrors()) return;
      
        $user = $this->userModel->firstWhere('username', $data['username']);

        if (!$user || !password_verify($data['password'], $user->password)) {
            return $this->session->getFlash()->add('message', 'Gebruikersnaam en of wachtwoord verkeerd ingevuld!');
        }
        
        $user->{UserInterface::COLUMN_IP_CURRENT} = $data[UserInterface::COLUMN_IP_CURRENT];

        $user->save();
        
        return $this->session->set('user', $user);
    }
}