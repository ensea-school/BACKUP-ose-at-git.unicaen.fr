<?php

namespace Application\Service;


/**
 * Description of DossierAutreTypeService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class DossierAutreTypeService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\DossierAutreType::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'dossierAutreType';
    }

}