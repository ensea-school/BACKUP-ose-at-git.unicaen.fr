<?php

namespace Application\Service;

use Application\Entity\Db\Personnel as PersonnelEntity;


/**
 * Description of Personnel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Personnel extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Personnel';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'personnel';
    }

    /**
     * Retourne le directeur des ressources humaines, s'il est défini.
     *
     * @return PersonnelEntity
     */
    public function getDrh()
    {
        $drhId = $this->getServiceParametres()->get('directeur_ressources_humaines_id');
        return $this->get( $drhId );
    }

    /**
     * @return Parametres
     */
    protected function getServiceParametres()
    {
        return $this->getServiceLocator()->get('applicationParametres');
    }
}