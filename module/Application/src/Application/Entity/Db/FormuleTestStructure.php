<?php

namespace Application\Entity\Db;

class FormuleTestStructure
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var boolean
     */
    private $universite = false;



    /**
     * @return int
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
     * @return FormuleTestStructure
     */
    public function setLibelle(string $libelle): FormuleTestStructure
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return bool
     */
    public function isUniversite(): bool
    {
        return $this->universite;
    }



    /**
     * @param bool $universite
     *
     * @return FormuleTestStructure
     */
    public function setUniversite(bool $universite): FormuleTestStructure
    {
        $this->universite = $universite;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
