<?php

namespace Workflow\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Workflow\Entity\Db\TypeValidation;

/**
 * Description of TypeValidation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeValidationService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass ()
    {
        return TypeValidation::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias ()
    {
        return 'typev';
    }



    public function getMission (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_MISSION);
    }



    /**
     *
     * @param string $code
     *
     * @return TypeValidation
     */
    public function getByCode ($code)
    {
        if (null == $code) return null;

        return $this->getRepo()->findOneBy(['code' => $code]);
    }



    public function getOffreEmploi (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_OFFRE_EMPLOI);
    }



    public function getCandidature (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_CANDIDATURE);
    }



    public function getMissionRealise (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_MISSION_REALISE);
    }



    public function getDonneesPerso (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_DONNEES_PERSO);
    }



    public function getEnseignement (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_ENSEIGNEMENT);
    }



    public function getReferentiel (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_REFERENTIEL);
    }



    public function getContrat (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_CONTRAT);
    }



    public function getFichier (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_FICHIER);
    }



    public function getPieceJointe (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_PIECE_JOINTE);
    }



    public function getClotureRealise (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_CLOTURE_REALISE);
    }



    public function getDeclaration (): TypeValidation
    {
        return $this->getByCode(TypeValidation::CODE_DECLARATION_PRIME);
    }



    /**
     * Retourne la liste des types de volumes horaires
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return TypeValidation[]
     */
    public function orderBy (QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }

}