<?php

namespace Application\Processus;

use Framework\Application\Application;
use Framework\Authorize\Authorize;
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


    public function getAuthorize(): Authorize
    {
        return Application::getInstance()->container()->get(Authorize::class);
    }

}