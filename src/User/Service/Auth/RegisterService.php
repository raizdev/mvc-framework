<?php
namespace StarreDEV\User\Service\Auth;

use PHLAK\Config\Interfaces\ConfigInterface;
use Odan\Session\SessionInterface;
use StarreDEV\Framework\Interfaces\CustomResponseInterface;
use StarreDEV\Framework\Interfaces\HttpResponseCodeInterface;
use StarreDEV\Framework\Service\ValidationService;
use StarreDEV\User\Exception\RegisterException;
use StarreDEV\User\Model\UserModel;
use StarreDEV\User\Entity\User;

/**
 * Class RegisterService
 *
 * @package StarreDEV\User\Service\Auth
 */
class RegisterService
{
    /**
     * RegisterService constructor.
     *
     * @param ConfigInterface       $config
     * @param SessionInterface      $session
     */
    public function __construct(
        private ConfigInterface $config,
        private SessionInterface $session,
        private UserModel $userModel,
        private HashService $hashService,
        private ValidationService $validationService
    ) {}

    /**
     * Registers a new User.
     *
     * @param array $data
     */
    public function register(array $parsedData)
    {
        $this->validationService->validate($parsedData, [
            'username'              => 'required|min:2|max:12|regex:/^[a-zA-Z\d]+$/',
            'email'                  => 'required|email|min:9',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);        
      
        if($this->validationService->getErrors()) return;

        /** @var UserModel $user */
        $user = $this->userModel->where('username', $parsedData['username'])->orWhere('email', $parsedData['email'])->get();

        if($user->isNotEmpty()) return $this->session->getFlash()->add('message', 'Email en of gebruikersnaam is al in gebruik!');
      
        $parsedData['password'] = $this->hashService->hash($parsedData['password']);
      
        /** @var UserModel $user */
        $user = $this->userModel->create($parsedData);

        $this->session->set('user', $user);
        return $this->session->getFlash()->add('message', 'Je account is aangemaakt!');
    }
  
}