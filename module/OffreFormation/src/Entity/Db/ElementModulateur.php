<?php

namespace OffreFormation\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * ElementModulateur
 */
class ElementModulateur implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Paiement\Entity\Db\Modulateur
     */
    protected $modulateur;

    /**
     * @var \OffreFormation\Entity\Db\ElementPedagogique
     */
    protected $element;

    /**
     * remove
     *
     * @var boolean
     */
    protected $remove = false;



    /**
     * Détermine si l'entité a vocation à être supprimée ou non
     */
    public function setRemove($remove)
    {
        $this->remove = (boolean)$remove;

        return $this;
    }



    public function getRemove()
    {
        return $this->remove;
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
     * Set modulateur
     *
     * @param \Paiement\Entity\Db\Modulateur $modulateur
     *
     * @return ElementModulateur
     */
    public function setModulateur(?\Paiement\Entity\Db\Modulateur $modulateur = null)
    {
        $this->modulateur = $modulateur;

        return $this;
    }



    /**
     * Get modulateur
     *
     * @return \Paiement\Entity\Db\Modulateur
     */
    public function getModulateur()
    {
        return $this->modulateur;
    }



    /**
     * Set element
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $element
     *
     * @return ElementModulateur
     */
    public function setElement(?\OffreFormation\Entity\Db\ElementPedagogique $element = null)
    {
        $this->element = $element;

        return $this;
    }



    /**
     * Get element
     *
     * @return \OffreFormation\Entity\Db\ElementPedagogique
     */
    public function getElement()
    {
        return $this->element;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'ElementModulateur';
    }

}
