<?php

namespace Administration\DataSource;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of DataSourceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DataSourceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DataSource
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DataSource
    {
        $dataSource = new DataSource();
        $dataSource->setBdd($container->get(Bdd::class));

        return $dataSource;
    }
}