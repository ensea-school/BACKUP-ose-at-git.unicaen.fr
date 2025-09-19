<?php

namespace Application\Service;

use Application\Traits\TranslatorTrait;
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
    use TranslatorTrait;

    /**
     *
     * @return \BjyAuthorize\Service\Authorize
     */
    public function getAuthorize()
    {
        return \Framework\Application\Application::getInstance()->container()->get('BjyAuthorize\Service\Authorize');
    }

}