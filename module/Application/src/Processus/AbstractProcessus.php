<?php

namespace Application\Processus;

use Unicaen\Framework\Application\Application;
use Unicaen\Framework\Authorize\Authorize;
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