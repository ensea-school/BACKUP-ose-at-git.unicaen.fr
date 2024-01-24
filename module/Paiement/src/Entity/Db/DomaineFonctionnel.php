<?php

namespace Paiement\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

class DomaineFonctionnel implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->sourceCode . " - " . $this->libelle;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return DomaineFonctionnel
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
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

}
