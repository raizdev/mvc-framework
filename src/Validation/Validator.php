<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class Validator
 */
class Validator
{
    /**
     * @var array $errors
     */
    private array $errors;

    /**
     * @param          $request
     * @param   array  $rules
     *
     * @return $this
     */
    public function validate($request, array $rules): Validator
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName($field)->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return !empty($this->errors);
    }
}