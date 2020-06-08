<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Service;

/**
 * Class ContainerSettingsService
 */
class ContainerSettingsService
{
    /** @var array */
    private array $settings;

    /**
     * ContainerSettingsService constructor.
     *
     * @param   array  $settings
     */
    public function __construct(
        array $settings
    ) {
        $this->settings = $settings;
    }

    /**
     * Get container-settings by key
     *
     * @param   string  $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->settings[$key];
    }
}