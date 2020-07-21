<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Service;

use App\Model\Locale;

/**
 * Class LocaleService
 */
class LocaleService
{
    /**
     * @var Locale
     */
    private Locale $locale;

    /**
     * LocaleService constructor.
     *
     * @param Locale $locale
     */
    public function __construct(
        Locale $locale
    ) {
        $this->locale = $locale;
    }

    /**
     * Takes message and placeholder to translate them in given locale.
     *
     * @param string $message
     * @param array $placeholder
     * @return string
     */
    public function translate(string $message, array $placeholder = []): string
    {
        $message = $this->locale->translate($message);

        if (!$placeholder) {
            return $message;
        }

        return vsprintf($message, $placeholder);
    }
}
