<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Unicaen\Framework\Application\Application;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        $appConfig = Application::getInstance()->config();
        $cacheDir  = $appConfig['module_listener_options']['cache_dir'] ?? getcwd() . '/cache';

        return $cacheDir;
    }



    public function getLogDir(): string
    {
        return getcwd() . '/data/log';
    }



    public function getProjectDir(): string
    {
        return getcwd() . '/api';
    }
}
