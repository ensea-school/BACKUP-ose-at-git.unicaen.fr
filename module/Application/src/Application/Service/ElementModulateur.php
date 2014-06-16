<?php

namespace Application\Service;

/**
 * Description of ElementModulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateur extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\ElementModulateur';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'epmod';
    }

    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     * @return \Application\Entity\Db\ElementModulateur
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // Initialisation de l'année en cours
        $entity->setAnnee( $this->getContextProvider()->getGlobalContext()->getAnnee() );
        return $entity;
    }
}