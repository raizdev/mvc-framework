<?php declare(strict_types=1);

namespace StarreDEV\Framework\Service;

use StarreDEV\Framework\Service\ContainerService;
use StarreDEV\Framework\Exception\ValidationException;
use Rakit\Validation\Validator;
use Odan\Session\SessionInterface;

/**
 * Class ValidatorService
 *
 * @package Ares\Framework\Service
 */
class ValidationService
{
    use ContainerService;
    /**
     * @var array $errors
     */
    private array $errors = [];

    public function __construct() {
        $this->validator = new Validator();;
    }
    /**
     * Validates the given data and returns an Exception if Validator fails
     *
     * @param mixed $data
     * @param array $rules
     *
     * @return void
     * @throws ValidationException
     */
    public function validate(mixed $data, array $rules)
    {
        if ($data === null || empty($rules)) {
           throw new ValidationException(__('Please provide a right data set'));
        }

        $validator = $this->validator->make($data, $rules);
        $validator->validate();

        if ($validator->fails()) {
            $fields = $validator->errors()->toArray();

            $errors = [];

            foreach ($fields as $key => $messages) {
                foreach ($messages as $message) {
                    $errors[$key] = __($message, [ucfirst($key)]);
                }
            }

            $this->setErrors($errors);

            $session = $this->container->get(SessionInterface::class);
            $session->getFlash()->set('errors', $this->getErrors());
          
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param mixed $error
     */
    public function setErrors(mixed $error): void
    {
        $this->errors = $error;
    }
}