<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenAuth\Entity\Db\Privilege;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * StatutIntervenant
 */
class StatutIntervenant implements HistoriqueAwareInterface, RoleInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    const SS_EMPLOI_NON_ETUD = 'SS_EMPLOI_NON_ETUD';
    const NON_AUTORISE       = 'NON_AUTORISE';



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * Indique si ce statut correspond à un intervenant permanent.
     *
     * @return bool
     */
    public function estPermanent()
    {
        return $this->getTypeIntervenant()->getCode() == TypeIntervenant::CODE_PERMANENT;
    }



    /**
     * Indique si ce statut correspond aux vacataires.
     *
     * @return bool
     */
    public function estVacataire()
    {
        return $this->getTypeIntervenant()->getCode() == TypeIntervenant::CODE_EXTERIEUR;
    }



    /**
     * @var boolean
     */
    protected $depassement;

    /**
     * @var boolean
     */
    protected $fonctionEC;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var float
     */
    protected $serviceStatutaire;

    /**
     * @var float
     */
    protected $plafondReferentiel;

    /**
     * @var float
     */
    protected $maximumHETD;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\TypeIntervenant
     */
    protected $typeIntervenant;

    /**
     * @var \Application\Entity\Db\TypeAgrementStatut
     */
    protected $typeAgrementStatut;

    /**
     * @var boolean
     */
    protected $nonAutorise;

    /**
     * @var boolean
     */
    protected $peutSaisirService;

    /**
     * @var boolean
     */
    protected $peutSaisirReferentiel;

    /**
     * @var boolean
     */
    protected $peutChoisirDansDossier;

    /**
     * @var boolean
     */
    protected $peutSaisirDossier;

    /**
     * @var boolean
     */
    protected $peutAvoirContrat;

    /**
     * @var boolean
     */
    protected $peutCloturerSaisie;

    /**
     * @var boolean
     */
    protected $peutSaisirMotifNonPaiement;

    /**
     * @var integer
     */
    protected $ordre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typePieceJointeStatut;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $privilege;

    /**
     * @var float
     */
    private $plafondHcHorsRemuFc;

    /**
     * @var float
     */
    private $plafondHcRemuFc;

    /**
     * @var boolean
     */
    private $temAtv;

    /**
     * @var boolean
     */
    private $temBiatss;



    /**
     * @return boolean
     */
    public function getTemAtv()
    {
        return $this->temAtv;
    }



    /**
     * @param boolean $temAtv
     *
     * @return StatutIntervenant
     */
    public function setTemAtv($temAtv)
    {
        $this->temAtv = $temAtv;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getTemBiatss()
    {
        return $this->temBiatss;
    }



    /**
     * @param boolean $temBiatss
     *
     * @return StatutIntervenant
     */
    public function setTemBiatss($temBiatss)
    {
        $this->temBiatss = $temBiatss;

        return $this;
    }



    /**
     *
     * @return boolean
     */
    function getNonAutorise()
    {
        return $this->nonAutorise;
    }



    /**
     *
     * @return boolean
     */
    function getPeutSaisirService()
    {
        return $this->peutSaisirService;
    }



    /**
     *
     * @return boolean
     */
    function getPeutSaisirReferentiel()
    {
        return $this->peutSaisirReferentiel;
    }



    /**
     *
     * @param boolean $peutSaisirReferentiel
     *
     * @return \Application\Entity\Db\StatutIntervenant
     */
    function setPeutSaisirReferentiel($peutSaisirReferentiel)
    {
        $this->peutSaisirReferentiel = $peutSaisirReferentiel;

        return $this;
    }



    /**
     *
     * @param boolean $nonAutorise
     *
     * @return \Application\Entity\Db\StatutIntervenant
     */
    function setNonAutorise($nonAutorise)
    {
        $this->nonAutorise = $nonAutorise;

        return $this;
    }



    /**
     *
     * @param boolean $peutSaisirService
     *
     * @return \Application\Entity\Db\StatutIntervenant
     */
    function setPeutSaisirService($peutSaisirService)
    {
        $this->peutSaisirService = $peutSaisirService;

        return $this;
    }



    /**
     *
     * @return boolean
     */
    function getPeutChoisirDansDossier()
    {
        return $this->peutChoisirDansDossier;
    }



    /**
     *
     * @param boolean $peutChoisirDansDossier
     *
     * @return \Application\Entity\Db\StatutIntervenant
     */
    function setPeutChoisirDansDossier($peutChoisirDansDossier)
    {
        $this->peutChoisirDansDossier = $peutChoisirDansDossier;

        return $this;
    }



    /**
     * Spécifie si ce statut permet la saisie des données personnelles.
     *
     * @param boolean $peutSaisirDossier
     *
     * @return self
     */
    public function setPeutSaisirDossier($peutSaisirDossier = true)
    {
        $this->peutSaisirDossier = $peutSaisirDossier;

        return $this;
    }



    /**
     * Indique si ce statut permet la saisie des données personnelles.
     *
     * @return boolean
     */
    public function getPeutSaisirDossier()
    {
        return $this->peutSaisirDossier;
    }



    /**
     * Spécifie si ce statut permet l'établissement d'un contrat/avenant.
     *
     * @param boolean $peutAvoirContrat
     *
     * @return self
     */
    public function setPeutAvoirContrat($peutAvoirContrat = true)
    {
        $this->peutAvoirContrat = $peutAvoirContrat;

        return $this;
    }



    /**
     * Indique si ce statut permet l'établissement d'un contrat/avenant.
     *
     * @return boolean
     */
    public function getPeutAvoirContrat()
    {
        return $this->peutAvoirContrat;
    }



    /**
     * @return boolean
     */
    public function getPeutCloturerSaisie()
    {
        return $this->peutCloturerSaisie;
    }



    /**
     * @param boolean $peutCloturerSaisie
     *
     * @return StatutIntervenant
     */
    public function setPeutCloturerSaisie($peutCloturerSaisie)
    {
        $this->peutCloturerSaisie = $peutCloturerSaisie;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getPeutSaisirMotifNonPaiement()
    {
        return $this->peutSaisirMotifNonPaiement;
    }



    /**
     * @param boolean $peutSaisirMotifNonPaiement
     *
     * @return StatutIntervenant
     */
    public function setPeutSaisirMotifNonPaiement($peutSaisirMotifNonPaiement)
    {
        $this->peutSaisirMotifNonPaiement = $peutSaisirMotifNonPaiement;

        return $this;
    }



    /**
     * Set depassement
     *
     * @param boolean $depassement
     *
     * @return StatutIntervenant
     */
    public function setDepassement($depassement)
    {
        $this->depassement = $depassement;

        return $this;
    }



    /**
     * Get depassement
     *
     * @return boolean
     */
    public function getDepassement()
    {
        return $this->depassement;
    }



    /**
     * Set fonctionEC
     *
     * @param boolean $fonctionEC
     *
     * @return StatutIntervenant
     */
    public function setFonctionEC($fonctionEC)
    {
        $this->fonctionEC = $fonctionEC;

        return $this;
    }



    /**
     * Get fonctionEC
     *
     * @return boolean
     */
    public function getFonctionEC()
    {
        return $this->fonctionEC;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return StatutIntervenant
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
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }



    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return self
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * Set serviceStatutaire
     *
     * @param float $serviceStatutaire
     *
     * @return StatutIntervenant
     */
    public function setServiceStatutaire($serviceStatutaire)
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }



    /**
     * Get serviceStatutaire
     *
     * @return float
     */
    public function getServiceStatutaire()
    {
        return $this->serviceStatutaire;
    }



    /**
     * Set plafondReferentiel
     *
     * @param float $plafondReferentiel
     *
     * @return StatutIntervenant
     */
    public function setPlafondReferentiel($plafondReferentiel)
    {
        $this->plafondReferentiel = $plafondReferentiel;

        return $this;
    }



    /**
     * Get plafondReferentiel
     *
     * @return float
     */
    public function getPlafondReferentiel()
    {
        return $this->plafondReferentiel;
    }



    /**
     * Set maximumHETD
     *
     * @param float $maximumHETD
     *
     * @return StatutIntervenant
     */
    public function setMaximumHETD($maximumHETD)
    {
        $this->maximumHETD = $maximumHETD;

        return $this;
    }



    /**
     * Get maximumHETD
     *
     * @return float
     */
    public function getMaximumHETD()
    {
        return $this->maximumHETD;
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
     * Set typeIntervenant
     *
     * @param \Application\Entity\Db\TypeIntervenant $typeIntervenant
     *
     * @return StatutIntervenant
     */
    public function setTypeIntervenant(\Application\Entity\Db\TypeIntervenant $typeIntervenant = null)
    {
        $this->typeIntervenant = $typeIntervenant;

        return $this;
    }



    /**
     * Get typeIntervenant
     *
     * @return \Application\Entity\Db\TypeIntervenant
     */
    public function getTypeIntervenant()
    {
        return $this->typeIntervenant;
    }



    /**
     * Add typeAgrementStatut
     *
     * @param \Application\Entity\Db\TypeAgrementStatut $typeAgrementStatut
     *
     * @return TypeTypeAgrementStatut
     */
    public function addTypeAgrementStatut(\Application\Entity\Db\TypeAgrementStatut $typeAgrementStatut)
    {
        $this->typeAgrementStatut[] = $typeAgrementStatut;

        return $this;
    }



    /**
     * Remove typeAgrementStatut
     *
     * @param \Application\Entity\Db\TypeAgrementStatut $typeAgrementStatut
     */
    public function removeTypeAgrementStatut(\Application\Entity\Db\TypeAgrementStatut $typeAgrementStatut)
    {
        $this->typeAgrementStatut->removeElement($typeAgrementStatut);
    }



    /**
     * Get typeAgrementStatut
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypeAgrementStatut()
    {
        return $this->typeAgrementStatut;
    }



    /**
     * Get typePieceJointeStatut
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypePieceJointeStatut()
    {
        return $this->typePieceJointeStatut;
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->typeAgrementStatut    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->typePieceJointeStatut = new \Doctrine\Common\Collections\ArrayCollection();
        $this->privilege             = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Add privilege
     *
     * @param Privilege $privilege
     *
     * @return StatutIntervenant
     */
    public function addPrivilege(Privilege $privilege)
    {
        $this->privilege[] = $privilege;

        return $this;
    }



    /**
     * Remove privilege
     *
     * @param Privilege $privilege
     */
    public function removePrivilege(Privilege $privilege)
    {
        $this->privilege->removeElement($privilege);
    }



    /**
     * Get privilege
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }



    /**
     * Détermine si le type de rôle possède un provilège ou non.
     * Si le privilège transmis est un objet de classe Privilege, alors il est inutile de fournir la ressource, sinon il est
     * obligatoire de la préciser
     *
     * @param Privilege|string $privilege
     *
     * @return boolean
     * @throws \LogicException
     */
    public function hasPrivilege($privilege)
    {
        if ($privilege instanceof Privilege) {
            $privilege = $privilege->getFullCode();
        }
        $privileges = $this->getPrivilege();
        if ($privileges) foreach ($privileges as $priv) {
            if ($priv->getFullCode() === $privilege) return true;
        }

        return false;
    }



    /**
     * @return float
     */
    public function getPlafondHcHorsRemuFc()
    {
        return $this->plafondHcHorsRemuFc;
    }



    /**
     * @param float $plafondHcHorsRemuFc
     *
     * @return StatutIntervenant
     */
    public function setPlafondHcHorsRemuFc($plafondHcHorsRemuFc)
    {
        $this->plafondHcHorsRemuFc = $plafondHcHorsRemuFc;

        return $this;
    }



    /**
     * @return float
     */
    public function getPlafondHcRemuFc()
    {
        return $this->plafondHcRemuFc;
    }



    /**
     * @param float $plafondHcRemuFc
     *
     * @return StatutIntervenant
     */
    public function setPlafondHcRemuFc($plafondHcRemuFc)
    {
        $this->plafondHcRemuFc = $plafondHcRemuFc;

        return $this;
    }



    public function getRoleId()
    {
        return 'statut/' . $this->getSourceCode();
    }



    /**
     * @since PHP 5.6.0
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @return array
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [
            'id'         => $this->id,
            'sourceCode' => $this->sourceCode,
            'libelle'    => $this->libelle,
            'type'       => $this->typeIntervenant ? $this->typeIntervenant->getLibelle() : null,
        ];
    }

}
