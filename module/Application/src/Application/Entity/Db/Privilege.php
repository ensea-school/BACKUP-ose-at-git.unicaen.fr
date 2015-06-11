<?php

namespace Application\Entity\Db;

/**
 * Privilege
 */
class Privilege
{
    const ENSEIGNEMENT_VISUALISATION        = 'enseignement-visualisation';
    const ENSEIGNEMENT_EXPORT_CSV           = 'enseignement-export-csv';
    const IMPORT_ECARTS                     = 'import-ecarts';
    const IMPORT_MAJ                        = 'import-maj';
    const IMPORT_TBL                        = 'import-tbl';
    const IMPORT_VUES_PROCEDURES            = 'import-vues-procedures';
    const INTERVENANT_RECHERCHE             = 'intervenant-recherche';
    const INTERVENANT_FICHE                 = 'intervenant-fiche';
    const INTERVENANT_CALCUL_HETD           = 'intervenant-calcul-hetd';
    const MISE_EN_PAIEMENT_VISUALISATION    = 'mise-en-paiement-visualisation';
    const MISE_EN_PAIEMENT_DEMANDE          = 'mise-en-paiement-demande';
    const MISE_EN_PAIEMENT_EXPORT_CSV       = 'mise-en-paiement-export-csv';
    const MISE_EN_PAIEMENT_EXPORT_PDF       = 'mise-en-paiement-export-pdf';
    const MISE_EN_PAIEMENT_MISE_EN_PAIEMENT = 'mise-en-paiement-mise-en-paiement';
    const MISE_EN_PAIEMENT_EXPORT_PAIE      = 'mise-en-paiement-export-paie';
    const MODIF_SERVICE_DU_VISUALISATION    = 'modif-service-du-visualisation';
    const MODIF_SERVICE_DU_EDITION          = 'modif-service-du-edition';
    const MODIF_SERVICE_DU_ASSOCIATION      = 'modif-service-du-association';
    const PRIVILEGE_VISUALISATION           = 'privilege-visualisation';
    const PRIVILEGE_EDITION                 = 'privilege-edition';




    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     *
     * @var integer
     */
    private $ordre;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\CategoriePrivilege
     */
    private $categorie;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $role;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $statut;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statut = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Privilege
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    public function getFullCode()
    {
        return $this->getCategorie()->getCode().'-'.$this->getCode();
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Privilege
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
     * 
     * @return integer
     */
    function getOrdre()
    {
        return $this->ordre;
    }

    /**
     *
     * @param integer $ordre
     * @return \Application\Entity\Db\Privilege
     */
    function setOrdre($ordre)
    {
        $this->ordre = $ordre;
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
     * Set categorie
     *
     * @param \Application\Entity\Db\CategoriePrivilege $categorie
     * @return Privilege
     */
    public function setCategorie(\Application\Entity\Db\CategoriePrivilege $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Application\Entity\Db\CategoriePrivilege
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Add role
     *
     * @param \Application\Entity\Db\Role $role
     * @return Privilege
     */
    public function addRole(\Application\Entity\Db\Role $role)
    {
        $this->Role[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \Application\Entity\Db\Role $role
     */
    public function removeRole(\Application\Entity\Db\Role $role)
    {
        $this->role->removeElement($role);
    }

    /**
     * Get role
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRole()
    {
        return $this->role;
    }

    public function getRoleCodes()
    {
        $result = [];
        foreach( $this->role as $role ){
            /* @var $role Role */
            $result[] = $role->getCode();
        }
        return $result;
    }

    /**
     * Add statut
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     * @return Privilege
     */
    public function addStatut(\Application\Entity\Db\StatutIntervenant $statut)
    {
        $this->statut[] = $statut;

        return $this;
    }

    /**
     * Remove statut
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     */
    public function removeStatut(\Application\Entity\Db\StatutIntervenant $statut)
    {
        $this->statut->removeElement($statut);
    }

    /**
     * Get statut
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public static function getResourceId($privilege)
    {
        if ($privilege instanceof Privilege)
            $privilege = $privilege->getFullCode ();

        return sprintf('privilege/%s', $privilege);
    }
}
