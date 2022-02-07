<?php

namespace Intervenant\Entity\Db;

use Application\Entity\Db\DossierAutre;
use Application\Entity\Db\Traits\TypeIntervenantAwareTrait;
use Application\Entity\Db\TypeAgrementStatut;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypePieceJointeStatut;
use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenAuth\Entity\Db\Privilege;
use Laminas\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Statut
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Statut implements HistoriqueAwareInterface, RoleInterface
{
    const CODE_AUTRES       = 'AUTRES';
    const CODE_NON_AUTORISE = 'NON_AUTORISE';

    use HistoriqueAwareTrait;
    use TypeIntervenantAwareTrait;

    protected ?int    $id;

    protected ?string $code;

    protected ?string $libelle;

    protected int     $ordre                         = 0;

    protected ?string $codeRh;

    protected bool    $prioritaireIndicateurs        = false;

    protected bool    $nonAutorise                   = false;

    protected bool    $depassement                   = true;

    protected bool    $depassementSDSHC              = false;

    protected float   $serviceStatutaire             = 0;

    protected float   $maximumHETD                   = 9999;

    protected float   $chargesPatronales             = 1.0;

    protected bool    $peutSaisirService             = true;

    protected bool    $referentiel                   = false;

    protected bool    $dossierSelectionnable         = true;

    protected bool    $dossier                       = true;

    protected bool    $contrat                       = true;

    protected bool    $peutCloturerSaisie            = false;

    protected bool    $peutSaisirMotifNonPaiement    = false;

    protected bool    $peutSaisirServiceExt          = false;

    protected bool    $dossierIdentiteComplementaire = true;

    protected bool    $dossierAdresse                = true;

    protected bool    $dossierContact                = true;

    protected bool    $dossierInsee                  = true;

    protected bool    $dossierBanque                 = true;

    protected bool    $dossierEmployeur              = false;

    protected bool    $dossierEmailPerso             = true;

    protected bool    $dossierTelPerso               = true;

    /**
     * @var Collection|DossierAutre[]
     */
    protected Collection $champsAutres;

    /**
     * @var Collection|TypePieceJointeStatut[]
     */
    protected Collection $typePieceJointeStatut;

    /**
     * @var Collection|TypeAgrementStatut[]
     */
    protected Collection $typeAgrementStatut;

    /**
     * @var Collection|Privilege[]
     */
    protected Collection $privilege;



    public function __construct()
    {
        $this->champsAutres          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->typePieceJointeStatut = new \Doctrine\Common\Collections\ArrayCollection();
        $this->typeAgrementStatut    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->privilege             = new \Doctrine\Common\Collections\ArrayCollection();
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



    public function getRoleId()
    {
        return 'statut/' . $this->getCode();
    }



    public function dupliquer()
    {
        $new     = new Statut();
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
     * Add champ autre
     *
     * @param \Application\Entity\Db\DossierAutre $champAutre
     *
     * @return Statut
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



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @param int|null $id
     *
     * @return Statut
     */
    public function setId(?int $id): Statut
    {
        $this->id = $id;

        return $this;
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
     * @return Statut
     */
    public function setCode(?string $code): Statut
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    /**
     * @param string|null $libelle
     *
     * @return Statut
     */
    public function setLibelle(?string $libelle): Statut
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return int
     */
    public function getOrdre(): int
    {
        return $this->ordre;
    }



    /**
     * @param int $ordre
     *
     * @return Statut
     */
    public function setOrdre(int $ordre): Statut
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCodeRh(): ?string
    {
        return $this->codeRh;
    }



    /**
     * @param string|null $codeRh
     *
     * @return Statut
     */
    public function setCodeRh(?string $codeRh): Statut
    {
        $this->codeRh = $codeRh;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPrioritaireIndicateurs(): bool
    {
        return $this->prioritaireIndicateurs;
    }



    /**
     * @param bool $prioritaireIndicateurs
     *
     * @return Statut
     */
    public function setPrioritaireIndicateurs(bool $prioritaireIndicateurs): Statut
    {
        $this->prioritaireIndicateurs = $prioritaireIndicateurs;

        return $this;
    }



    /**
     * @return bool
     */
    public function getNonAutorise(): bool
    {
        return $this->nonAutorise;
    }



    /**
     * @param bool $nonAutorise
     *
     * @return Statut
     */
    public function setNonAutorise(bool $nonAutorise): Statut
    {
        $this->nonAutorise = $nonAutorise;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDepassement(): bool
    {
        return $this->depassement;
    }



    /**
     * @param bool $depassement
     *
     * @return Statut
     */
    public function setDepassement(bool $depassement): Statut
    {
        $this->depassement = $depassement;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDepassementSDSHC(): bool
    {
        return $this->depassementSDSHC;
    }



    /**
     * @param bool $depassementSDSHC
     *
     * @return Statut
     */
    public function setDepassementSDSHC(bool $depassementSDSHC): Statut
    {
        $this->depassementSDSHC = $depassementSDSHC;

        return $this;
    }



    /**
     * @return float|int
     */
    public function getServiceStatutaire(): float|int
    {
        return $this->serviceStatutaire;
    }



    /**
     * @param float|int $serviceStatutaire
     *
     * @return Statut
     */
    public function setServiceStatutaire(float|int $serviceStatutaire): Statut
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }



    /**
     * @return float|int
     */
    public function getMaximumHETD(): float|int
    {
        return $this->maximumHETD;
    }



    /**
     * @param float|int $maximumHETD
     *
     * @return Statut
     */
    public function setMaximumHETD(float|int $maximumHETD): Statut
    {
        $this->maximumHETD = $maximumHETD;

        return $this;
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
     * @return Statut
     */
    public function setChargesPatronales(float $chargesPatronales): Statut
    {
        $this->chargesPatronales = $chargesPatronales;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPeutSaisirService(): bool
    {
        return $this->peutSaisirService;
    }



    /**
     * @param bool $peutSaisirService
     *
     * @return Statut
     */
    public function setPeutSaisirService(bool $peutSaisirService): Statut
    {
        $this->peutSaisirService = $peutSaisirService;

        return $this;
    }



    /**
     * @return bool
     */
    public function getReferentiel(): bool
    {
        return $this->referentiel;
    }



    /**
     * @param bool $referentiel
     *
     * @return Statut
     */
    public function setReferentiel(bool $referentiel): Statut
    {
        $this->referentiel = $referentiel;

        return $this;
    }



    /**
     * @return bool
     */
    public function isDossierSelectionnable(): bool
    {
        return $this->dossierSelectionnable;
    }



    /**
     * @param bool $dossierSelectionnable
     *
     * @return Statut
     */
    public function setDossierSelectionnable(bool $dossierSelectionnable): Statut
    {
        $this->dossierSelectionnable = $dossierSelectionnable;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossier(): bool
    {
        return $this->dossier;
    }



    /**
     * @param bool $dossier
     *
     * @return Statut
     */
    public function setDossier(bool $dossier): Statut
    {
        $this->dossier = $dossier;

        return $this;
    }



    /**
     * @return bool
     */
    public function hasContrat(): bool
    {
        return $this->contrat;
    }



    /**
     * @param bool $contrat
     *
     * @return Statut
     */
    public function setContrat(bool $contrat): Statut
    {
        $this->contrat = $contrat;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPeutCloturerSaisie(): bool
    {
        return $this->peutCloturerSaisie;
    }



    /**
     * @param bool $peutCloturerSaisie
     *
     * @return Statut
     */
    public function setPeutCloturerSaisie(bool $peutCloturerSaisie): Statut
    {
        $this->peutCloturerSaisie = $peutCloturerSaisie;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPeutSaisirMotifNonPaiement(): bool
    {
        return $this->peutSaisirMotifNonPaiement;
    }



    /**
     * @param bool $peutSaisirMotifNonPaiement
     *
     * @return Statut
     */
    public function setPeutSaisirMotifNonPaiement(bool $peutSaisirMotifNonPaiement): Statut
    {
        $this->peutSaisirMotifNonPaiement = $peutSaisirMotifNonPaiement;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPeutSaisirServiceExt(): bool
    {
        return $this->peutSaisirServiceExt;
    }



    /**
     * @param bool $peutSaisirServiceExt
     *
     * @return Statut
     */
    public function setPeutSaisirServiceExt(bool $peutSaisirServiceExt): Statut
    {
        $this->peutSaisirServiceExt = $peutSaisirServiceExt;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierIdentiteComplementaire(): bool
    {
        return $this->dossierIdentiteComplementaire;
    }



    /**
     * @param bool $dossierIdentiteComplementaire
     *
     * @return Statut
     */
    public function setDossierIdentiteComplementaire(bool $dossierIdentiteComplementaire): Statut
    {
        $this->dossierIdentiteComplementaire = $dossierIdentiteComplementaire;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAdresse(): bool
    {
        return $this->dossierAdresse;
    }



    /**
     * @param bool $dossierAdresse
     *
     * @return Statut
     */
    public function setDossierAdresse(bool $dossierAdresse): Statut
    {
        $this->dossierAdresse = $dossierAdresse;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierContact(): bool
    {
        return $this->dossierContact;
    }



    /**
     * @param bool $dossierContact
     *
     * @return Statut
     */
    public function setDossierContact(bool $dossierContact): Statut
    {
        $this->dossierContact = $dossierContact;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierInsee(): bool
    {
        return $this->dossierInsee;
    }



    /**
     * @param bool $dossierInsee
     *
     * @return Statut
     */
    public function setDossierInsee(bool $dossierInsee): Statut
    {
        $this->dossierInsee = $dossierInsee;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierBanque(): bool
    {
        return $this->dossierBanque;
    }



    /**
     * @param bool $dossierBanque
     *
     * @return Statut
     */
    public function setDossierBanque(bool $dossierBanque): Statut
    {
        $this->dossierBanque = $dossierBanque;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierEmployeur(): bool
    {
        return $this->dossierEmployeur;
    }



    /**
     * @param bool $dossierEmployeur
     *
     * @return Statut
     */
    public function setDossierEmployeur(bool $dossierEmployeur): Statut
    {
        $this->dossierEmployeur = $dossierEmployeur;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierEmailPerso(): bool
    {
        return $this->dossierEmailPerso;
    }



    /**
     * @param bool $dossierEmailPerso
     *
     * @return Statut
     */
    public function setDossierEmailPerso(bool $dossierEmailPerso): Statut
    {
        $this->dossierEmailPerso = $dossierEmailPerso;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierTelPerso(): bool
    {
        return $this->dossierTelPerso;
    }



    /**
     * @param bool $dossierTelPerso
     *
     * @return Statut
     */
    public function setDossierTelPerso(bool $dossierTelPerso): Statut
    {
        $this->dossierTelPerso = $dossierTelPerso;

        return $this;
    }



    /**
     * @return TypePieceJointeStatut[]|Collection
     */
    public function getTypePieceJointeStatut(): \Doctrine\Common\Collections\ArrayCollection|Collection|array
    {
        return $this->typePieceJointeStatut;
    }



    /**
     * @param TypePieceJointeStatut[]|Collection $typePieceJointeStatut
     *
     * @return Statut
     */
    public function setTypePieceJointeStatut(\Doctrine\Common\Collections\ArrayCollection|Collection|array $typePieceJointeStatut): Statut
    {
        $this->typePieceJointeStatut = $typePieceJointeStatut;

        return $this;
    }



    public function isNonAutorise(): bool
    {
        return $this->code === self::CODE_NON_AUTORISE;
    }
}
