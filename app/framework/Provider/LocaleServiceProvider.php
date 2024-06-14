<?php
namespace Raizdev\Framework\Provider;

use Raizdev\Framework\Helper\LocaleHelper;
use Raizdev\Framework\Model\Locale;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class LocaleServiceProvider
 */
class LocaleServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        Locale::class
    ];

    /**
     * Registers new service.
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
