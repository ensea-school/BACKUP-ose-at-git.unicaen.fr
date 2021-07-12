<?php

namespace Indicateur\Entity\Db\Indicateur;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Doctrine\ORM\QueryBuilder;


/**
 * AbstractIndicateur
 */
abstract class AbstractIndicateur
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Annee
     */
    protected $annee;

    /**
     * @var Intervenant
     */
    protected $intervenant;

    /**
     * @var Structure
     */
    protected $structure;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * @return array
     */
    public function getUrlParams()
    {
        return [
            'intervenant' => $this->getIntervenant()->getId(),
        ];
    }



    /**
     * Retourne les options de l'URL
     *
     * @return array
     */
    public function getUrlOptions()
    {
        return ['force_canonical' => true];
    }



    /**
     * Retourne les détails concernant l'indicateur
     *
     * @return string|null
     */
    public function getDetails()
    {
        return null;
    }



    /**
     * @param QueryBuilder $qb
     *
     */
    public static function appendQueryBuilder(QueryBuilder $qb)
    {

    }
}
