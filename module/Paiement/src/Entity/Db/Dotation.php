<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\StructureAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Dotation
 */
class Dotation implements HistoriqueAwareInterface, ResourceInterface
{
    use AnneeAwareTrait;
    use StructureAwareTrait;
    use HistoriqueAwareTrait;
    use TypeRessourceAwareTrait;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $anneeCivile;

    /**
     * @var string
     */
    private $libelle;



    /**
     * Set heures
     *
     * @param float $heures
     *
     * @return Dotation
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return Dotation
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



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
     * @return int
     */
    public function getAnneeCivile()
    {
        return $this->anneeCivile;
    }



    /**
     * @param int $anneeCivile
     *
     * @return Dotation
     */
    public function setAnneeCivile($anneeCivile)
    {
        $this->anneeCivile = $anneeCivile;

        return $this;
    }



    public function getResourceId(): string
    {
        return self::class;
    }

}
