<?php

namespace Application\Entity\Db;

/**
 * ElementModulateur
 */
class ElementModulateur implements HistoriqueAwareInterface
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
     * Détermine si le volume horaire a vocation à être supprimé ou non
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

}
