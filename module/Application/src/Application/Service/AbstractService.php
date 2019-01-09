<?php

namespace Application\Service;

use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Service abstrait
 *
 * Permet d'accéder facilement aux paramètres globaux de l'application
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AbstractService implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use Traits\ContextServiceAwareTrait;

    /**
     *
     * @return \BjyAuthorize\Service\Authorize
     */
    public function getAuthorize()
    {
        return \Application::$container->get('BjyAuthorize\Service\Authorize');
    }

}