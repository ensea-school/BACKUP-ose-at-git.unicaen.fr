<?php

namespace Application\Processus;

use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Processus abstrait
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AbstractProcessus implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;


    /**
     *
     * @return \BjyAuthorize\Service\Authorize
     */
    public function getAuthorize()
    {
        return \OseAdmin::instance()->container()->get('BjyAuthorize\Service\Authorize');
    }

}