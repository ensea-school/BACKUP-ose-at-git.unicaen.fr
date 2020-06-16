<?php

namespace Application\Service;

use Application\Entity\Db\Modulateur;
use Application\Entity\Db\ElementPedagogique;
use Application\Service\Traits\ElementModulateurServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of DossierAutreService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class DossierAutreService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\DossierAutre::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'dossierAutre';
    }

}