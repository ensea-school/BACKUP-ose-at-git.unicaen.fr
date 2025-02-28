<?php

namespace Chargens\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\StructureAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Scenario implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;
    use StructureAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var integer
     */
    protected $type = 0;



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
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return Scenario
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return bool
     */
    public function isReel()
    {
        return $this->type == 2;
    }



    /**
     * @return bool
     */
    public function isPrevisionnel()
    {
        return $this->type == 1;
    }



    /**
     * @return bool
     */
    public function isLocal()
    {
        return $this->type == 0;
    }



    /**
     * @return string
     */
    public function getTypeString()
    {
        switch ($this->type) {
            case 0:
                return 'Local';
            case 1:
                return 'Prévisionnel';
            case 2:
                return 'Réel';
        }
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'Scenario';
    }

}
