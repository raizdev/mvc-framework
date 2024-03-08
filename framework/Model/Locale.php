<?php
namespace StarreDEV\Framework\Model;

class Locale
{
    /**
     * @var array
     */
    private array $messages = [];


    /**
     * Takes message and placeholder to translate them in given locale.
     *
     * @param string $key
     * @return string
     */
    public function translate(string $key): string
    {
        $messages = $this->getMessages();

        if (!array_key_exists($key, $messages)) {

            if (!array_key_exists($key, $messages)) {
                return $key;
            } else {
                return $messages[$key];
            }
        }

        return $messages[$key];
    }


    /**
     * @param string $locale
     * @return string
     */
    private function getFileName(): string
    {
        return config('website_settings.language') . '.json';
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return app_dir() . '/app/locales/';
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        $path = $this->getPath();
        $fileName = $this->getFileName();

        $jsonContent = @file_get_contents($path . $fileName);

        if (!$jsonContent) {
            return [];
        }

        return json_decode($jsonContent, true);
    }
}