<?php

namespace Intervenant\Entity\Db;

use Application\Entity\Db\Traits\TypeIntervenantAwareTrait;
use Application\Entity\Db\TypeIntervenant;
use Application\Interfaces\ParametreEntityInterface;
use Application\Traits\ParametreEntityTrait;
use Laminas\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Statut
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Statut implements ParametreEntityInterface, RoleInterface
{
    const CODE_AUTRES       = 'AUTRES';
    const CODE_NON_AUTORISE = 'NON_AUTORISE';

    use ParametreEntityTrait;
    use TypeIntervenantAwareTrait;


    private ?string $code;

    private ?string $libelle;

    private int     $ordre                              = 9999;

    private bool    $prioritaireIndicateurs             = false;

    private float   $serviceStatutaire                  = 0;

    private bool    $depassementServiceDuSansHC         = false;

    private float   $tauxChargesPatronales              = 1.0;

    private bool    $dossier                            = true;

    private bool    $dossierVisualisation               = true;

    private bool    $dossierEdition                     = true;

    private bool    $dossierSelectionnable              = true;

    private bool    $dossierIdentiteComplementaire      = true;

    private bool    $dossierContact                     = true;

    private bool    $dossierTelPerso                    = false;

    private bool    $dossierEmailPerso                  = false;

    private bool    $dossierAdresse                     = true;

    private bool    $dossierBanque                      = true;

    private bool    $dossierInsee                       = true;

    private bool    $dossierEmployeur                   = false;

    private bool    $dossierAutre1                      = false;

    private bool    $dossierAutre1Visualisation         = true;

    private bool    $dossierAutre1Edition               = true;

    private bool    $dossierAutre2                      = false;

    private bool    $dossierAutre2Visualisation         = true;

    private bool    $dossierAutre2Edition               = true;

    private bool    $dossierAutre3                      = false;

    private bool    $dossierAutre3Visualisation         = true;

    private bool    $dossierAutre3Edition               = true;

    private bool    $dossierAutre4                      = false;

    private bool    $dossierAutre4Visualisation         = true;

    private bool    $dossierAutre4Edition               = true;

    private bool    $dossierAutre5                      = false;

    private bool    $dossierAutre5Visualisation         = true;

    private bool    $dossierAutre5Edition               = true;

    private bool    $pieceJustificativeVisualisation    = true;

    private bool    $pieceJustificativeTelechargement   = true;

    private bool    $pieceJustificativeEdition          = true;

    private bool    $pieceJustificativeArchivage        = true;

    private bool    $conseilRestreint                   = true;

    private bool    $conseilRestreintVisualisation      = true;

    private int     $conseilRestreintDureeVie           = 1;

    private bool    $conseilAcademique                  = true;

    private bool    $conseilAcademiqueVisualisation     = true;

    private int     $conseilAcademiqueDureeVie          = 5;

    private bool    $contrat                            = true;

    private bool    $contratVisualisation               = true;

    private bool    $contratDepot                       = true;

    private bool    $service                            = true;

    private bool    $serviceVisualisation               = true;

    private bool    $serviceEdition                     = true;

    private bool    $serviceExterieur                   = true;

    private bool    $referentiel                        = true;

    private bool    $referentielVisualisation           = true;

    private bool    $referentielEdition                 = true;

    private bool    $cloture                            = true;

    private bool    $modificationServiceDu              = true;

    private bool    $modificationServiceDuVisualisation = true;

    private bool    $paiementVisualisation              = true;

    private bool    $motifNonPaiement                   = true;

    private bool    $formuleVisualisation               = true;

    private ?string $codesCorresp1                      = null;

    private ?string $codesCorresp2                      = null;

    private ?string $codesCorresp3                      = null;

    private ?string $codesCorresp4                      = null;



    public function __toString(): string
    {
        return $this->getLibelle();
    }



    public function getRoleId(): string
    {
        return 'statut/' . $this->getCode();
    }



    public function dupliquer(): Statut
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



    public function estPermanent(): bool
    {
        return $this->getTypeIntervenant()->getCode() == TypeIntervenant::CODE_PERMANENT;
    }



    public function estVacataire(): bool
    {
        return $this->getTypeIntervenant()->getCode() == TypeIntervenant::CODE_EXTERIEUR;
    }



    public function isNonAutorise(): bool
    {
        return $this->code === self::CODE_NON_AUTORISE;
    }



    public function isAutres(): bool
    {
        return $this->code === self::CODE_AUTRES;
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
     * @return bool
     */
    public function getDepassementServiceDuSansHc(): bool
    {
        return $this->depassementServiceDuSansHc;
    }



    /**
     * @param bool $depassementServiceDuSansHc
     *
     * @return Statut
     */
    public function setDepassementServiceDuSansHc(bool $depassementServiceDuSansHc): Statut
    {
        $this->depassementServiceDuSansHc = $depassementServiceDuSansHc;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxChargesPatronales(): float
    {
        return $this->tauxChargesPatronales;
    }



    /**
     * @param float $tauxChargesPatronales
     *
     * @return Statut
     */
    public function setTauxChargesPatronales(float $tauxChargesPatronales): Statut
    {
        $this->tauxChargesPatronales = $tauxChargesPatronales;

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
    public function getDossierVisualisation(): bool
    {
        return $this->dossierVisualisation;
    }



    /**
     * @param bool $dossierVisualisation
     *
     * @return Statut
     */
    public function setDossierVisualisation(bool $dossierVisualisation): Statut
    {
        $this->dossierVisualisation = $dossierVisualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierEdition(): bool
    {
        return $this->dossierEdition;
    }



    /**
     * @param bool $dossierEdition
     *
     * @return Statut
     */
    public function setDossierEdition(bool $dossierEdition): Statut
    {
        $this->dossierEdition = $dossierEdition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierSelectionnable(): bool
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
    public function getDossierAutre1(): bool
    {
        return $this->dossierAutre1;
    }



    /**
     * @param bool $dossierAutre1
     *
     * @return Statut
     */
    public function setDossierAutre1(bool $dossierAutre1): Statut
    {
        $this->dossierAutre1 = $dossierAutre1;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre1Visualisation(): bool
    {
        return $this->dossierAutre1Visualisation;
    }



    /**
     * @param bool $dossierAutre1Visualisation
     *
     * @return Statut
     */
    public function setDossierAutre1Visualisation(bool $dossierAutre1Visualisation): Statut
    {
        $this->dossierAutre1Visualisation = $dossierAutre1Visualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre1Edition(): bool
    {
        return $this->dossierAutre1Edition;
    }



    /**
     * @param bool $dossierAutre1Edition
     *
     * @return Statut
     */
    public function setDossierAutre1Edition(bool $dossierAutre1Edition): Statut
    {
        $this->dossierAutre1Edition = $dossierAutre1Edition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre2(): bool
    {
        return $this->dossierAutre2;
    }



    /**
     * @param bool $dossierAutre2
     *
     * @return Statut
     */
    public function setDossierAutre2(bool $dossierAutre2): Statut
    {
        $this->dossierAutre2 = $dossierAutre2;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre2Visualisation(): bool
    {
        return $this->dossierAutre2Visualisation;
    }



    /**
     * @param bool $dossierAutre2Visualisation
     *
     * @return Statut
     */
    public function setDossierAutre2Visualisation(bool $dossierAutre2Visualisation): Statut
    {
        $this->dossierAutre2Visualisation = $dossierAutre2Visualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre2Edition(): bool
    {
        return $this->dossierAutre2Edition;
    }



    /**
     * @param bool $dossierAutre2Edition
     *
     * @return Statut
     */
    public function setDossierAutre2Edition(bool $dossierAutre2Edition): Statut
    {
        $this->dossierAutre2Edition = $dossierAutre2Edition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre3(): bool
    {
        return $this->dossierAutre3;
    }



    /**
     * @param bool $dossierAutre3
     *
     * @return Statut
     */
    public function setDossierAutre3(bool $dossierAutre3): Statut
    {
        $this->dossierAutre3 = $dossierAutre3;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre3Visualisation(): bool
    {
        return $this->dossierAutre3Visualisation;
    }



    /**
     * @param bool $dossierAutre3Visualisation
     *
     * @return Statut
     */
    public function setDossierAutre3Visualisation(bool $dossierAutre3Visualisation): Statut
    {
        $this->dossierAutre3Visualisation = $dossierAutre3Visualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre3Edition(): bool
    {
        return $this->dossierAutre3Edition;
    }



    /**
     * @param bool $dossierAutre3Edition
     *
     * @return Statut
     */
    public function setDossierAutre3Edition(bool $dossierAutre3Edition): Statut
    {
        $this->dossierAutre3Edition = $dossierAutre3Edition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre4(): bool
    {
        return $this->dossierAutre4;
    }



    /**
     * @param bool $dossierAutre4
     *
     * @return Statut
     */
    public function setDossierAutre4(bool $dossierAutre4): Statut
    {
        $this->dossierAutre4 = $dossierAutre4;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre4Visualisation(): bool
    {
        return $this->dossierAutre4Visualisation;
    }



    /**
     * @param bool $dossierAutre4Visualisation
     *
     * @return Statut
     */
    public function setDossierAutre4Visualisation(bool $dossierAutre4Visualisation): Statut
    {
        $this->dossierAutre4Visualisation = $dossierAutre4Visualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre4Edition(): bool
    {
        return $this->dossierAutre4Edition;
    }



    /**
     * @param bool $dossierAutre4Edition
     *
     * @return Statut
     */
    public function setDossierAutre4Edition(bool $dossierAutre4Edition): Statut
    {
        $this->dossierAutre4Edition = $dossierAutre4Edition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre5(): bool
    {
        return $this->dossierAutre5;
    }



    /**
     * @param bool $dossierAutre5
     *
     * @return Statut
     */
    public function setDossierAutre5(bool $dossierAutre5): Statut
    {
        $this->dossierAutre5 = $dossierAutre5;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre5Visualisation(): bool
    {
        return $this->dossierAutre5Visualisation;
    }



    /**
     * @param bool $dossierAutre5Visualisation
     *
     * @return Statut
     */
    public function setDossierAutre5Visualisation(bool $dossierAutre5Visualisation): Statut
    {
        $this->dossierAutre5Visualisation = $dossierAutre5Visualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getDossierAutre5Edition(): bool
    {
        return $this->dossierAutre5Edition;
    }



    /**
     * @param bool $dossierAutre5Edition
     *
     * @return Statut
     */
    public function setDossierAutre5Edition(bool $dossierAutre5Edition): Statut
    {
        $this->dossierAutre5Edition = $dossierAutre5Edition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPieceJustificativeVisualisation(): bool
    {
        return $this->pieceJustificativeVisualisation;
    }



    /**
     * @param bool $pieceJustificativeVisualisation
     *
     * @return Statut
     */
    public function setPieceJustificativeVisualisation(bool $pieceJustificativeVisualisation): Statut
    {
        $this->pieceJustificativeVisualisation = $pieceJustificativeVisualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPieceJustificativeTelechargement(): bool
    {
        return $this->pieceJustificativeTelechargement;
    }



    /**
     * @param bool $pieceJustificativeTelechargement
     *
     * @return Statut
     */
    public function setPieceJustificativeTelechargement(bool $pieceJustificativeTelechargement): Statut
    {
        $this->pieceJustificativeTelechargement = $pieceJustificativeTelechargement;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPieceJustificativeEdition(): bool
    {
        return $this->pieceJustificativeEdition;
    }



    /**
     * @param bool $pieceJustificativeEdition
     *
     * @return Statut
     */
    public function setPieceJustificativeEdition(bool $pieceJustificativeEdition): Statut
    {
        $this->pieceJustificativeEdition = $pieceJustificativeEdition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPieceJustificativeArchivage(): bool
    {
        return $this->pieceJustificativeArchivage;
    }



    /**
     * @param bool $pieceJustificativeArchivage
     *
     * @return Statut
     */
    public function setPieceJustificativeArchivage(bool $pieceJustificativeArchivage): Statut
    {
        $this->pieceJustificativeArchivage = $pieceJustificativeArchivage;

        return $this;
    }



    /**
     * @return bool
     */
    public function getConseilRestreint(): bool
    {
        return $this->conseilRestreint;
    }



    /**
     * @param bool $conseilRestreint
     *
     * @return Statut
     */
    public function setConseilRestreint(bool $conseilRestreint): Statut
    {
        $this->conseilRestreint = $conseilRestreint;

        return $this;
    }



    /**
     * @return bool
     */
    public function getConseilRestreintVisualisation(): bool
    {
        return $this->conseilRestreintVisualisation;
    }



    /**
     * @param bool $conseilRestreintVisualisation
     *
     * @return Statut
     */
    public function setConseilRestreintVisualisation(bool $conseilRestreintVisualisation): Statut
    {
        $this->conseilRestreintVisualisation = $conseilRestreintVisualisation;

        return $this;
    }



    /**
     * @return int
     */
    public function getConseilRestreintDureeVie(): int
    {
        return $this->conseilRestreintDureeVie;
    }



    /**
     * @param int $conseilRestreintDureeVie
     *
     * @return Statut
     */
    public function setConseilRestreintDureeVie(int $conseilRestreintDureeVie): Statut
    {
        $this->conseilRestreintDureeVie = $conseilRestreintDureeVie;

        return $this;
    }



    /**
     * @return bool
     */
    public function getConseilAcademique(): bool
    {
        return $this->conseilAcademique;
    }



    /**
     * @param bool $conseilAcademique
     *
     * @return Statut
     */
    public function setConseilAcademique(bool $conseilAcademique): Statut
    {
        $this->conseilAcademique = $conseilAcademique;

        return $this;
    }



    /**
     * @return bool
     */
    public function getConseilAcademiqueVisualisation(): bool
    {
        return $this->conseilAcademiqueVisualisation;
    }



    /**
     * @param bool $conseilAcademiqueVisualisation
     *
     * @return Statut
     */
    public function setConseilAcademiqueVisualisation(bool $conseilAcademiqueVisualisation): Statut
    {
        $this->conseilAcademiqueVisualisation = $conseilAcademiqueVisualisation;

        return $this;
    }



    /**
     * @return int
     */
    public function getConseilAcademiqueDureeVie(): int
    {
        return $this->conseilAcademiqueDureeVie;
    }



    /**
     * @param int $conseilAcademiqueDureeVie
     *
     * @return Statut
     */
    public function setConseilAcademiqueDureeVie(int $conseilAcademiqueDureeVie): Statut
    {
        $this->conseilAcademiqueDureeVie = $conseilAcademiqueDureeVie;

        return $this;
    }



    /**
     * @return bool
     */
    public function getContrat(): bool
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
    public function getContratVisualisation(): bool
    {
        return $this->contratVisualisation;
    }



    /**
     * @param bool $contratVisualisation
     *
     * @return Statut
     */
    public function setContratVisualisation(bool $contratVisualisation): Statut
    {
        $this->contratVisualisation = $contratVisualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getContratDepot(): bool
    {
        return $this->contratDepot;
    }



    /**
     * @param bool $contratDepot
     *
     * @return Statut
     */
    public function setContratDepot(bool $contratDepot): Statut
    {
        $this->contratDepot = $contratDepot;

        return $this;
    }



    /**
     * @return bool
     */
    public function getService(): bool
    {
        return $this->service;
    }



    /**
     * @param bool $service
     *
     * @return Statut
     */
    public function setService(bool $service): Statut
    {
        $this->service = $service;

        return $this;
    }



    /**
     * @return bool
     */
    public function getServiceVisualisation(): bool
    {
        return $this->serviceVisualisation;
    }



    /**
     * @param bool $serviceVisualisation
     *
     * @return Statut
     */
    public function setServiceVisualisation(bool $serviceVisualisation): Statut
    {
        $this->serviceVisualisation = $serviceVisualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getServiceEdition(): bool
    {
        return $this->serviceEdition;
    }



    /**
     * @param bool $serviceEdition
     *
     * @return Statut
     */
    public function setServiceEdition(bool $serviceEdition): Statut
    {
        $this->serviceEdition = $serviceEdition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getServiceExterieur(): bool
    {
        return $this->serviceExterieur;
    }



    /**
     * @param bool $serviceExterieur
     *
     * @return Statut
     */
    public function setServiceExterieur(bool $serviceExterieur): Statut
    {
        $this->serviceExterieur = $serviceExterieur;

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
    public function getReferentielVisualisation(): bool
    {
        return $this->referentielVisualisation;
    }



    /**
     * @param bool $referentielVisualisation
     *
     * @return Statut
     */
    public function setReferentielVisualisation(bool $referentielVisualisation): Statut
    {
        $this->referentielVisualisation = $referentielVisualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getReferentielEdition(): bool
    {
        return $this->referentielEdition;
    }



    /**
     * @param bool $referentielEdition
     *
     * @return Statut
     */
    public function setReferentielEdition(bool $referentielEdition): Statut
    {
        $this->referentielEdition = $referentielEdition;

        return $this;
    }



    /**
     * @return bool
     */
    public function getCloture(): bool
    {
        return $this->cloture;
    }



    /**
     * @param bool $cloture
     *
     * @return Statut
     */
    public function setCloture(bool $cloture): Statut
    {
        $this->cloture = $cloture;

        return $this;
    }



    /**
     * @return bool
     */
    public function getModificationServiceDu(): bool
    {
        return $this->modificationServiceDu;
    }



    /**
     * @param bool $modificationServiceDu
     *
     * @return Statut
     */
    public function setModificationServiceDu(bool $modificationServiceDu): Statut
    {
        $this->modificationServiceDu = $modificationServiceDu;

        return $this;
    }



    /**
     * @return bool
     */
    public function getModificationServiceDuVisualisation(): bool
    {
        return $this->modificationServiceDuVisualisation;
    }



    /**
     * @param bool $modificationServiceDuVisualisation
     *
     * @return Statut
     */
    public function setModificationServiceDuVisualisation(bool $modificationServiceDuVisualisation): Statut
    {
        $this->modificationServiceDuVisualisation = $modificationServiceDuVisualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getPaiementVisualisation(): bool
    {
        return $this->paiementVisualisation;
    }



    /**
     * @param bool $paiementVisualisation
     *
     * @return Statut
     */
    public function setPaiementVisualisation(bool $paiementVisualisation): Statut
    {
        $this->paiementVisualisation = $paiementVisualisation;

        return $this;
    }



    /**
     * @return bool
     */
    public function getMotifNonPaiement(): bool
    {
        return $this->motifNonPaiement;
    }



    /**
     * @param bool $motifNonPaiement
     *
     * @return Statut
     */
    public function setMotifNonPaiement(bool $motifNonPaiement): Statut
    {
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }



    /**
     * @return bool
     */
    public function getFormuleVisualisation(): bool
    {
        return $this->formuleVisualisation;
    }



    /**
     * @param bool $formuleVisualisation
     *
     * @return Statut
     */
    public function setFormuleVisualisation(bool $formuleVisualisation): Statut
    {
        $this->formuleVisualisation = $formuleVisualisation;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCodesCorresp1(): ?string
    {
        return $this->codesCorresp1;
    }



    /**
     * @param string|null $codesCorresp1
     *
     * @return Statut
     */
    public function setCodesCorresp1(?string $codesCorresp1): Statut
    {
        $this->codesCorresp1 = $codesCorresp1;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCodesCorresp2(): ?string
    {
        return $this->codesCorresp2;
    }



    /**
     * @param string|null $codesCorresp2
     *
     * @return Statut
     */
    public function setCodesCorresp2(?string $codesCorresp2): Statut
    {
        $this->codesCorresp2 = $codesCorresp2;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCodesCorresp3(): ?string
    {
        return $this->codesCorresp3;
    }



    /**
     * @param string|null $codesCorresp3
     *
     * @return Statut
     */
    public function setCodesCorresp3(?string $codesCorresp3): Statut
    {
        $this->codesCorresp3 = $codesCorresp3;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCodesCorresp4(): ?string
    {
        return $this->codesCorresp4;
    }



    /**
     * @param string|null $codesCorresp4
     *
     * @return Statut
     */
    public function setCodesCorresp4(?string $codesCorresp4): Statut
    {
        $this->codesCorresp4 = $codesCorresp4;

        return $this;
    }

}
