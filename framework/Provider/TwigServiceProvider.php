<?php
namespace StarreDEV\Framework\Provider;

use StarreDEV\Framework\Service\FlashMessageService;
use StarreDEV\Framework\Service\ContainerService;
use StarreDEV\Framework\Service\TwigService;
use Odan\Session\SessionInterface;
use PHLAK\Config\Interfaces\ConfigInterface;
use Twig\Environment;
use Twig\TwigFunction;
use Twig\TwigFilter;

class TwigServiceProvider 
{
    use ContainerService;
    /**
     * Registers our Twig Provider
     *
     * @throws LoaderError
     */
    public function register() 
    {
        $twig = TwigService::create(app_dir() . 'src/Frontend/Views',
            ['cache' => ($_ENV['CACHE_ENABLED'] === false) ? cache_dir() . '/twig' : false]);

        $this->registerGlobals($twig->getEnvironment());
        $this->registerFunctions($twig->getEnvironment());
        //$this->registerFilters($twig->getEnvironment());

        return $twig;
    }

   /**
     * @param $string , $placeholders
     * @return string
     */
    public function getLanguage($string, $placeholders = []): string
    {
        return __($string, $placeholders);
    }

    /**
     * @param $string
     * @return string|null
     */
    public function getConfig($string): string | null {
        $config = $this->container->get(ConfigInterface::class);
        return $config->get($string);
    }
  
    /**
     * @param $string
     * @return string|null
     */
    public function firstFlashMessage($keyword) {
        $session = $this->container->get(SessionInterface::class);
        return $session->getFlash()->get($keyword)[0] ?? false;
    }

    /**
     * @param Environment $twig
     * @return void
     */
    public function registerFunctions(Environment $twig): void
    {
        $twig->addFunction(
            new TwigFunction('config', [$this, 'getConfig'])
        );
        $twig->addFunction(
            new TwigFunction('lang', [$this, 'getLanguage'])
        );
        $twig->addFunction(
            new TwigFunction('firstFlashMessage', [$this, 'firstFlashMessage'])
        );
    }
  
      /**
     * @param Environment $twig
     * @return void
     */
    private function registerGlobals(Environment $twig)
    {
        $session = $this->container->get(SessionInterface::class);
        $twig->addGlobal('flash', $session->getFlash());
        $twig->addGlobal('user', $session->get('user') ?? false);
    }
}