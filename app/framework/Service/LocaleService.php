<?php
namespace Raizdev\Framework\Service;

use Raizdev\Framework\Model\Locale;

/**
 * Class LocaleService
 */
class LocaleService
{
    /**
     * LocaleService constructor.
     *
     * @param Locale $locale
     */
    public function __construct(
        private readonly Locale $locale
    ) {}

    /**
     * Takes message and placeholder to translate them in given locale.
     *
     * @param string $key
     * @param array  $placeholder
     * @return string
     */
    public function translate(string $key, array $placeholder = []): string
    {
        $message = $this->locale->translate($key);

        if (!$placeholder) {
            return $message;
        }

        return vsprintf($message, $placeholder);
    }
}
