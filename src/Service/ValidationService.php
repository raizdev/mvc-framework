<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Service;

use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class ValidationService
 *
 * @package App\Service
 */
class ValidationService
{
    /**
     * @var array $errors
     */
    private array $errors;

    /**
     * @param          $data
     * @param   array  $rules
     *
     * @return $this
     */
    public function execute($data, array $rules): ValidationService
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName($field)->assert($data[$field] ?? null);
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