<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Settings\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Settings\Entity\Setting;
use Ares\Settings\Exception\SettingsException;
use Ares\Settings\Repository\SettingsRepository;

/**
 * Class UpdateSettingsService
 *
 * @package Ares\Settings\Service
 */
class UpdateSettingsService
{
    /**
     * @var SettingsRepository
     */
    private SettingsRepository $settingsRepository;

    /**
     * UpdateSettingsService constructor.
     *
     * @param   SettingsRepository  $settingsRepository
     */
    public function __construct(
        SettingsRepository $settingsRepository
    ) {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @param $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws SettingsException
     */
    public function update($data): CustomResponseInterface
    {
        /** @var string $key */
        $key = $data['key'];

        /** @var string $value */
        $value = $data['value'];

        $searchCriteria = $this->settingsRepository
            ->getDataObjectManager()
            ->where('key', $key);

        /** @var Setting $configData */
        $configData = $this->settingsRepository
            ->getList($searchCriteria)
            ->first();

        if (!$configData) {
            throw new SettingsException(__('Key not found in Config'));
        }

        $configData->setValue($value);
        $configData = $this->settingsRepository->save($configData);

        return response()
            ->setData(
                $configData
            );
    }
}
