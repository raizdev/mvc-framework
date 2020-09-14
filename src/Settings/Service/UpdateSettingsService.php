<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Settings\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Settings\Entity\Setting;
use Ares\Settings\Exception\SettingsException;
use Ares\Settings\Repository\SettingsRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

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
     * @throws SettingsException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function update($data): CustomResponseInterface
    {
        /** @var string $key */
        $key = $data['key'];

        /** @var string $value */
        $value = $data['value'];

        /** @var Setting $configData */
        $configData = $this->settingsRepository->getOneBy([
            'key' => $key
        ]);

        if (!$configData) {
            throw new SettingsException(__('Key not found in Config'));
        }

        $configData->setValue($value);
        $configData = $this->settingsRepository->update($configData);

        return response()
            ->setData(
                $configData->toArray()
            );
    }
}
