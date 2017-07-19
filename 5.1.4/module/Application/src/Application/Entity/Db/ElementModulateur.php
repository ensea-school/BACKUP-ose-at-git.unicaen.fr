<?php

namespace Application\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

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
     * @var \Application\Entity\Db\Modulateur
     */
    protected $modulateur;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
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
     * @param \Application\Entity\Db\Modulateur $modulateur
     *
     * @return ElementModulateur
     */
    public function setModulateur(\Application\Entity\Db\Modulateur $modulateur = null)
    {
        $this->modulateur = $modulateur;

        return $this;
    }



    /**
     * Get modulateur
     *
     * @return \Application\Entity\Db\Modulateur
     */
    public function getModulateur()
    {
        return $this->modulateur;
    }



    /**
     * Set element
     *
     * @param \Application\Entity\Db\ElementPedagogique $element
     *
     * @return ElementModulateur
     */
    public function setElement(\Application\Entity\Db\ElementPedagogique $element = null)
    {
        $this->element = $element;

        return $this;
    }



    /**
     * Get element
     *
     * @return \Application\Entity\Db\ElementPedagogique
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
