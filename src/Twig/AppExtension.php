<?php

namespace App\Twig;

use Symfony\Component\Dotenv\Dotenv;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    /**
     * @var string $baseDomain
     */
    private $baseDomain;

    /**
     * @var string $defaultSchema
     */
    private $defaultSchema;

    public function __construct() {
        $this->baseDomain = $_SERVER['BASE_DOMAIN'];
        $this->defaultSchema = $_SERVER['DEFAULT_SCHEMA'];
    }

    /**
     * Функции
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_full_domain', [$this, 'getFullDomain']),
            new TwigFunction('abs_asset', [$this, 'AbsoluteAsset'])
        ];
    }

    /**
     * Фильтры
     *
     * @return array
     */
    public function getFilters()
    {
        return [];
    }

    public function getFullDomain()
    {
        return $this->defaultSchema . '://' . $this->baseDomain;
    }

    public function AbsoluteAsset($path)
    {
        $siteAddress = $this->defaultSchema . '://' . $this->baseDomain;
        if ($path[0] === '/') {
            return $siteAddress . $path;
        }
        return $siteAddress . '/' . $path;
    }

}
