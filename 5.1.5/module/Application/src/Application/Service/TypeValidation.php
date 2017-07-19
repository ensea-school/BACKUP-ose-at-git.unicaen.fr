<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;

/**
 * Description of TypeValidation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeValidation extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeValidationEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'typev';
    }

    /**
     *
     * @param string $code
     * @return TypeValidationEntity
     */
    public function getByCode( $code )
    {
        if (null == $code) return null;
        return $this->getRepo()->findOneBy(['code' => $code]);
    }



    public function getDonneesPerso()
    {
        return $this->getByCode(TypeValidationEntity::CODE_DONNEES_PERSO);
    }

    public function getEnseignement()
    {
        return $this->getByCode(TypeValidationEntity::CODE_ENSEIGNEMENT);
    }

    public function getReferentiel()
    {
        return $this->getByCode(TypeValidationEntity::CODE_REFERENTIEL);
    }

    public function getContrat()
    {
        return $this->getByCode(TypeValidationEntity::CODE_CONTRAT);
    }

    public function getFichier()
    {
        return $this->getByCode(TypeValidationEntity::CODE_FICHIER);
    }

    public function getPieceJointe()
    {
        return $this->getByCode(TypeValidationEntity::CODE_PIECE_JOINTE);
    }

    public function getClotureRealise()
    {
        return $this->getByCode(TypeValidationEntity::CODE_CLOTURE_REALISE);
    }


    /**
     * Retourne la liste des types de volumes horaires
     *
     * @param QueryBuilder|null $queryBuilder
     * @return TypeValidationEntity[]
     */
    public function orderBy( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return $qb;
    }

}