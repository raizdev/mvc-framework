<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Provider;

use App\Helper\LocaleHelper;
use App\Model\Locale;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class LocaleServiceProvider
 */
class LocaleServiceProvider extends AbstractServiceProvider
{
    /** @var array */
    protected $provides = [
        'settings',
        Locale::class
    ];

    /**
     * Registers Locale Model as shared instance.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(Locale::class, function () {
            $localeHelper = new LocaleHelper();
            return new Locale($localeHelper);
        });
    }
}
