<?php

namespace Application\Service;

use Application\Traits\TranslatorTrait;
use Framework\Application\Application;
use Framework\Authorize\Authorize;
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

    public function getAuthorize(): Authorize
    {
        return Application::getInstance()->container()->get(Authorize::class);
    }

}