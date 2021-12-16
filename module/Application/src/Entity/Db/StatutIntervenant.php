<?php

namespace Application\Entity\Db;

use Application\Service\StatutIntervenantService;
use phpDocumentor\Reflection\Types\Integer;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenAuth\Entity\Db\Privilege;
use Laminas\Permissions\Acl\Role\RoleInterface;

/**
 * StatutIntervenant
 */
class StatutIntervenant implements HistoriqueAwareInterface, RoleInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var boolean
     */
    protected $depassement;

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
    protected $plafondReferentielService = 9999;

    /**
     * @var float
     */
    protected $plafondReferentielHc = 9999;

    /**
     * @var float
     */
    protected $maximumHETD;

    /**
     * @var float
     */
    protected $chargesPatronales = 1.0;

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
    protected $typePieceJointeStatut;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $privilege;

    /**
     * @var float
     */
    protected $plafondHcHorsRemuFc;

    /**
     * @var float
     */
    protected $plafondHcRemuFc;

    /**
     * @var float
     */
    protected $plafondHcFiHorsEad = 9999;

    /**
     * @var boolean
     */
    protected $peutSaisirServiceExt;

    /**
     * @var boolean
     */
    protected $temAtv;

    /**
     * @var boolean
     */
    protected $temVa;

    /**
     * @var boolean
     */
    protected $temBiatss;

    /**
     * @var boolean
     */
    protected $depassementSDSHC;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $champsAutres;

    /**
     * @var boolean
     */
    protected $dossierIdentiteComplementaire;

    /**
     * @var boolean
     */
    protected $dossierAdresse;

    /**
     * @var boolean
     */
    protected $dossierContact;

    /**
     * @var boolean
     */
    protected $dossierInsee;

    /**
     * @var boolean
     */
    protected $dossierIban;

    /**
     * @var boolean
     */
    protected $dossierEmployeur;

    /**
     * @var boolean
     */
    protected $dossierEmailPerso;

    /**
     * @var boolean
     */
    protected $dossierTelPerso;

    /**
     * @var string
     */
    protected      $codeRh;

    protected bool $prioritaireIndicateurs = false;



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }



    /**
     * @param string|null $code
     *
     * @return StatutIntervenant
     */
    public function setCode(?string $code): StatutIntervenant
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getPeutSaisirServiceExt()
    {
        return $this->peutSaisirServiceExt;
    }



    /**
     * @param boolean $peutSaisirServieExt
     *
     * @return StatutIntervenant
     */
    public function setPeutSaisirServiceExt($peutSaisirServiceExt)
    {
        $this->peutSaisirServiceExt = $peutSaisirServiceExt;

        return $this;
    }



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
    public function getTemVa()
    {
        return $this->temVa;
    }



    /**
     * @param boolean $temVa
     *
     * @return StatutIntervenant
     */
    public function setTemVa($temVa)
    {
        $this->temVa = $temVa;

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
     * @return float
     */
    public function getPlafondReferentielService(): float
    {
        return $this->plafondReferentielService;
    }



    /**
     * @param float $plafondReferentielService
     *
     * @return StatutIntervenant
     */
    public function setPlafondReferentielService(float $plafondReferentielService): StatutIntervenant
    {
        $this->plafondReferentielService = $plafondReferentielService;

        return $this;
    }



    /**
     * @return float
     */
    public function getPlafondReferentielHc(): float
    {
        return $this->plafondReferentielHc;
    }



    /**
     * @param float $plafondReferentielHc
     *
     * @return StatutIntervenant
     */
    public function setPlafondReferentielHc(float $plafondReferentielHc): StatutIntervenant
    {
        $this->plafondReferentielHc = $plafondReferentielHc;

        return $this;
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
     * @return float
     */
    public function getChargesPatronales(): float
    {
        return $this->chargesPatronales;
    }



    /**
     * @param float $chargesPatronales
     *
     * @return StatutIntervenant
     */
    public function setChargesPatronales(float $chargesPatronales): StatutIntervenant
    {
        $this->chargesPatronales = $chargesPatronales;

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
     * @param int $id
     *
     * @return StatutIntervenant
     */
    public function setId($id): StatutIntervenant
    {
        $this->id = $id;

        return $this;
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
        if ($privileges) {
            foreach ($privileges as $priv) {
                if ($priv->getFullCode() === $privilege) return true;
            }
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



    /**
     * @return float
     */
    public function getPlafondHcFiHorsEad(): float
    {
        return $this->plafondHcFiHorsEad;
    }



    /**
     * @param float $plafondHcFiHorsEad
     *
     * @return StatutIntervenant
     */
    public function setPlafondHcFiHorsEad(float $plafondHcFiHorsEad): StatutIntervenant
    {
        $this->plafondHcFiHorsEad = $plafondHcFiHorsEad;

        return $this;
    }



    public function getRoleId()
    {
        return 'statut/' . $this->getCode();
    }



    /**
     * @return boolean
     */
    public function getDepassementSDSHC()
    {
        return $this->depassementSDSHC;
    }



    /**
     * @param boolean $depassementSDSHC
     *
     * @return StatutIntervenant
     */
    public function setDepassementSDSHC($depassementSDSHC)
    {
        $this->depassementSDSHC = $depassementSDSHC;

        return $this;
    }



    public function dupliquer()
    {
        $new     = new StatutIntervenant();
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            $setMethod = 'set' . substr($method, 3);
            if (0 === strpos($method, 'get') && in_array($setMethod, $methods)) {
                $new->$setMethod($this->$method());
            }
        }
        $new->setId(null);
        $uid = uniqid();
        $new->setCode($this->getCode() . '_' . $uid);
        $new->setLibelle($this->getLibelle() . ' (Copie ' . $uid . ')');

        return $new;
    }



    /**
     * @return boolean
     */
    public function getDossierIdentiteComplementaire()
    {
        return $this->dossierIdentiteComplementaire;
    }



    /**
     * @param integer $dossierIdentiteComplementaire
     *
     * @return StatutIntervenant
     */
    public function setDossierIdentiteComplementaire(int $dossierIdentiteComplementaire): StatutIntervenant
    {
        $this->dossierIdentiteComplementaire = $dossierIdentiteComplementaire;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getDossierAdresse()
    {
        return $this->dossierAdresse;
    }



    /**
     * @param integer $dossierAdresse
     *
     * @return StatutIntervenant
     */
    public function setDossierAdresse(int $dossierAdresse): StatutIntervenant
    {
        $this->dossierAdresse = $dossierAdresse;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getDossierContact()
    {
        return $this->dossierContact;
    }



    /**
     * @param integer $dossierContact
     *
     * @return StatutIntervenant
     */
    public function setDossierContact(int $dossierContact): StatutIntervenant
    {
        $this->dossierContact = $dossierContact;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getDossierInsee()
    {
        return $this->dossierInsee;
    }



    /**
     * @param integer $dossierInsee
     *
     * @return StatutIntervenant
     */
    public function setDossierInsee(int $dossierInsee): StatutIntervenant
    {
        $this->dossierInsee = $dossierInsee;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getDossierIban()
    {
        return $this->dossierIban;
    }



    /**
     * @param integer $dossierIban
     *
     * @return StatutIntervenant
     */
    public function setDossierIban(int $dossierIban): StatutIntervenant
    {
        $this->dossierIban = $dossierIban;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getDossierEmployeur()
    {
        return $this->dossierEmployeur;
    }



    /**
     * @param integer $dossierEmployeur
     *
     * @return StatutIntervenant
     */
    public function setDossierEmployeur(int $dossierEmployeur): StatutIntervenant
    {
        $this->dossierEmployeur = $dossierEmployeur;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getDossierEmailPerso()
    {
        return $this->dossierEmailPerso;
    }



    /**
     * @param integer $dossierEmailPerso
     *
     * @return StatutIntervenant
     */
    public function setDossierEmailPerso(int $dossierEmailPerso): StatutIntervenant
    {
        $this->dossierEmailPerso = $dossierEmailPerso;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getDossierTelPerso()
    {
        return $this->dossierTelPerso;
    }



    /**
     * @param integer $dossierTelPerso
     */
    public function setDossierTelPerso(int $dossierTelPerso): StatutIntervenant
    {
        $this->dossierTelPerso = $dossierTelPerso;

        return $this;
    }



    /**
     * @return string
     */
    public function getCodeRh()
    {
        return $this->codeRh;
    }



    /**
     * @param string $codeRh
     */
    public function setCodeRh($codeRh): string
    {
        $this->codeRh = $codeRh;

        return $this;
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
     * @return bool
     */
    public function isPrioritaireIndicateurs(): bool
    {
        return $this->prioritaireIndicateurs;
    }



    /**
     * @param bool $prioritaireIndicateurs
     *
     * @return StatutIntervenant
     */
    public function setPrioritaireIndicateurs(bool $prioritaireIndicateurs): StatutIntervenant
    {
        $this->prioritaireIndicateurs = $prioritaireIndicateurs;

        return $this;
    }


    
    /**
     * Add champ autre
     *
     * @param \Application\Entity\Db\DossierAutre $champAutre
     *
     * @return StatutIntervenant
     */
    public function addChampAutre(DossierAutre $champAutre)
    {
        if (!$this->champsAutres->contains($champAutre)) {
            $this->champsAutres[] = $champAutre;
        }

        return $this;
    }



    /**
     * Remove champ autre
     *
     * @param \Application\Entity\Db\DossierAutre $champAutre
     */
    public function removeChampAutre(DossierAutre $champAutre)
    {
        if ($this->champsAutres) {
            $this->champsAutres->removeElement($champAutre);
        }
    }



    /**
     * Get champs autres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChampsAutres()
    {
        return $this->champsAutres;
    }



    public function getStatutSelectable($criteria = [])
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\StatutIntervenant')->createQueryBuilder('s');
        foreach ($criteria as $key => $value) {
            $qb->orWhere($key, $value);
        }
        $qb->orderBy('ordre', 'ASC');

        return $qb->getQuery()->getResult();
    }

}
