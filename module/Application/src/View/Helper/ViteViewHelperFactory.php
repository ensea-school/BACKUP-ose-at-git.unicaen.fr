<?php

namespace Application\View\Helper;

use Psr\Container\ContainerInterface;


/**
 * Description of ViteViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ViteViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ViteViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ViteViewHelper
    {
        $config = $container->get('config');

        if (array_key_exists('vite', $config)) {
            $viteConfig = $config['vite'];
        } else {
            $viteConfig = [
                'host'        => 'http://localhost:5133',
                'vue-url'     => '/vendor/vue.js',
                'hot-loading' => true,
            ];
        }

        $viewHelper = new ViteViewHelper($viteConfig);

        return $viewHelper;
    }
}