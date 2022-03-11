<?php

namespace Intervenant\Entity\Db;

use Application\Interfaces\ParametreEntityInterface;
use Application\Provider\Privilege\Privileges;
use Application\Traits\ParametreEntityTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Statut
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Statut implements ParametreEntityInterface, RoleInterface, ResourceInterface
{
    const CODE_AUTRES       = 'AUTRES';
    const CODE_NON_AUTORISE = 'NON_AUTORISE';

    use ParametreEntityTrait;
    use TypeIntervenantAwareTrait;


    private ?string $code                               = null;

    private ?string $libelle                            = null;

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

    private bool    $pieceJustificativeEdition          = true;

    private bool    $conseilRestreint                   = true;

    private bool    $conseilRestreintVisualisation      = true;

    private int     $conseilRestreintDureeVie           = 1;

    private bool    $conseilAcademique                  = true;

    private bool    $conseilAcademiqueVisualisation     = true;

    private int     $conseilAcademiqueDureeVie          = 5;

    private bool    $contrat                            = true;

    private bool    $contratVisualisation               = true;

    private bool    $contratDepot                       = true;

    private bool    $servicePrevu                       = true;

    private bool    $servicePrevuVisualisation          = true;

    private bool    $servicePrevuEdition                = true;

    private bool    $serviceRealise                     = true;

    private bool    $serviceRealiseVisualisation        = true;

    private bool    $serviceRealiseEdition              = true;

    private bool    $serviceExterieur                   = true;

    private bool    $referentielPrevu                   = true;

    private bool    $referentielPrevuVisualisation      = true;

    private bool    $referentielPrevuEdition            = true;

    private bool    $referentielRealise                 = true;

    private bool    $referentielRealiseVisualisation    = true;

    private bool    $referentielRealiseEdition          = true;

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



    public function getResourceId()
    {
        return 'Statut';
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
            if (str_starts_with($method, 'get') && in_array($setMethod, $methods)) {
                $new->$setMethod($this->$method());
            }
        }
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



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): Statut
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): Statut
    {
        $this->libelle = $libelle;

        return $this;
    }



    public function getOrdre(): int
    {
        return $this->ordre;
    }



    public function setOrdre(int $ordre): Statut
    {
        $this->ordre = $ordre;

        return $this;
    }



    public function getPrioritaireIndicateurs(): bool
    {
        return $this->prioritaireIndicateurs;
    }



    public function setPrioritaireIndicateurs(bool $prioritaireIndicateurs): Statut
    {
        $this->prioritaireIndicateurs = $prioritaireIndicateurs;

        return $this;
    }



    public function getServiceStatutaire(): float
    {
        return $this->serviceStatutaire;
    }



    public function setServiceStatutaire(float $serviceStatutaire): Statut
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }



    public function getDepassementServiceDuSansHC(): bool
    {
        return $this->depassementServiceDuSansHC;
    }



    public function setDepassementServiceDuSansHC(bool $depassementServiceDuSansHC): Statut
    {
        $this->depassementServiceDuSansHC = $depassementServiceDuSansHC;

        return $this;
    }



    public function getTauxChargesPatronales(): float
    {
        return $this->tauxChargesPatronales;
    }



    public function setTauxChargesPatronales(float $tauxChargesPatronales): Statut
    {
        $this->tauxChargesPatronales = $tauxChargesPatronales;

        return $this;
    }



    public function getDossier(): bool
    {
        return $this->dossier;
    }



    public function setDossier(bool $dossier): Statut
    {
        $this->dossier = $dossier;

        return $this;
    }



    public function getDossierVisualisation(): bool
    {
        return $this->dossierVisualisation;
    }



    public function setDossierVisualisation(bool $dossierVisualisation): Statut
    {
        $this->dossierVisualisation = $dossierVisualisation;

        return $this;
    }



    public function getDossierEdition(): bool
    {
        return $this->dossierEdition;
    }



    public function setDossierEdition(bool $dossierEdition): Statut
    {
        $this->dossierEdition = $dossierEdition;

        return $this;
    }



    public function getDossierSelectionnable(): bool
    {
        return $this->dossierSelectionnable;
    }



    public function setDossierSelectionnable(bool $dossierSelectionnable): Statut
    {
        $this->dossierSelectionnable = $dossierSelectionnable;

        return $this;
    }



    public function getDossierIdentiteComplementaire(): bool
    {
        return $this->dossierIdentiteComplementaire;
    }



    public function setDossierIdentiteComplementaire(bool $dossierIdentiteComplementaire): Statut
    {
        $this->dossierIdentiteComplementaire = $dossierIdentiteComplementaire;

        return $this;
    }



    public function getDossierContact(): bool
    {
        return $this->dossierContact;
    }



    public function setDossierContact(bool $dossierContact): Statut
    {
        $this->dossierContact = $dossierContact;

        return $this;
    }



    public function getDossierTelPerso(): bool
    {
        return $this->dossierTelPerso;
    }



    public function setDossierTelPerso(bool $dossierTelPerso): Statut
    {
        $this->dossierTelPerso = $dossierTelPerso;

        return $this;
    }



    public function getDossierEmailPerso(): bool
    {
        return $this->dossierEmailPerso;
    }



    public function setDossierEmailPerso(bool $dossierEmailPerso): Statut
    {
        $this->dossierEmailPerso = $dossierEmailPerso;

        return $this;
    }



    public function getDossierAdresse(): bool
    {
        return $this->dossierAdresse;
    }



    public function setDossierAdresse(bool $dossierAdresse): Statut
    {
        $this->dossierAdresse = $dossierAdresse;

        return $this;
    }



    public function getDossierBanque(): bool
    {
        return $this->dossierBanque;
    }



    public function setDossierBanque(bool $dossierBanque): Statut
    {
        $this->dossierBanque = $dossierBanque;

        return $this;
    }



    public function getDossierInsee(): bool
    {
        return $this->dossierInsee;
    }



    public function setDossierInsee(bool $dossierInsee): Statut
    {
        $this->dossierInsee = $dossierInsee;

        return $this;
    }



    public function getDossierEmployeur(): bool
    {
        return $this->dossierEmployeur;
    }



    public function setDossierEmployeur(bool $dossierEmployeur): Statut
    {
        $this->dossierEmployeur = $dossierEmployeur;

        return $this;
    }



    public function getDossierAutre1(): bool
    {
        return $this->dossierAutre1;
    }



    public function setDossierAutre1(bool $dossierAutre1): Statut
    {
        $this->dossierAutre1 = $dossierAutre1;

        return $this;
    }



    public function getDossierAutre1Visualisation(): bool
    {
        return $this->dossierAutre1Visualisation;
    }



    public function setDossierAutre1Visualisation(bool $dossierAutre1Visualisation): Statut
    {
        $this->dossierAutre1Visualisation = $dossierAutre1Visualisation;

        return $this;
    }



    public function getDossierAutre1Edition(): bool
    {
        return $this->dossierAutre1Edition;
    }



    public function setDossierAutre1Edition(bool $dossierAutre1Edition): Statut
    {
        $this->dossierAutre1Edition = $dossierAutre1Edition;

        return $this;
    }



    public function getDossierAutre2(): bool
    {
        return $this->dossierAutre2;
    }



    public function setDossierAutre2(bool $dossierAutre2): Statut
    {
        $this->dossierAutre2 = $dossierAutre2;

        return $this;
    }



    public function getDossierAutre2Visualisation(): bool
    {
        return $this->dossierAutre2Visualisation;
    }



    public function setDossierAutre2Visualisation(bool $dossierAutre2Visualisation): Statut
    {
        $this->dossierAutre2Visualisation = $dossierAutre2Visualisation;

        return $this;
    }



    public function getDossierAutre2Edition(): bool
    {
        return $this->dossierAutre2Edition;
    }



    public function setDossierAutre2Edition(bool $dossierAutre2Edition): Statut
    {
        $this->dossierAutre2Edition = $dossierAutre2Edition;

        return $this;
    }



    public function getDossierAutre3(): bool
    {
        return $this->dossierAutre3;
    }



    public function setDossierAutre3(bool $dossierAutre3): Statut
    {
        $this->dossierAutre3 = $dossierAutre3;

        return $this;
    }



    public function getDossierAutre3Visualisation(): bool
    {
        return $this->dossierAutre3Visualisation;
    }



    public function setDossierAutre3Visualisation(bool $dossierAutre3Visualisation): Statut
    {
        $this->dossierAutre3Visualisation = $dossierAutre3Visualisation;

        return $this;
    }



    public function getDossierAutre3Edition(): bool
    {
        return $this->dossierAutre3Edition;
    }



    public function setDossierAutre3Edition(bool $dossierAutre3Edition): Statut
    {
        $this->dossierAutre3Edition = $dossierAutre3Edition;

        return $this;
    }



    public function getDossierAutre4(): bool
    {
        return $this->dossierAutre4;
    }



    public function setDossierAutre4(bool $dossierAutre4): Statut
    {
        $this->dossierAutre4 = $dossierAutre4;

        return $this;
    }



    public function getDossierAutre4Visualisation(): bool
    {
        return $this->dossierAutre4Visualisation;
    }



    public function setDossierAutre4Visualisation(bool $dossierAutre4Visualisation): Statut
    {
        $this->dossierAutre4Visualisation = $dossierAutre4Visualisation;

        return $this;
    }



    public function getDossierAutre4Edition(): bool
    {
        return $this->dossierAutre4Edition;
    }



    public function setDossierAutre4Edition(bool $dossierAutre4Edition): Statut
    {
        $this->dossierAutre4Edition = $dossierAutre4Edition;

        return $this;
    }



    public function getDossierAutre5(): bool
    {
        return $this->dossierAutre5;
    }



    public function setDossierAutre5(bool $dossierAutre5): Statut
    {
        $this->dossierAutre5 = $dossierAutre5;

        return $this;
    }



    public function getDossierAutre5Visualisation(): bool
    {
        return $this->dossierAutre5Visualisation;
    }



    public function setDossierAutre5Visualisation(bool $dossierAutre5Visualisation): Statut
    {
        $this->dossierAutre5Visualisation = $dossierAutre5Visualisation;

        return $this;
    }



    public function getDossierAutre5Edition(): bool
    {
        return $this->dossierAutre5Edition;
    }



    public function setDossierAutre5Edition(bool $dossierAutre5Edition): Statut
    {
        $this->dossierAutre5Edition = $dossierAutre5Edition;

        return $this;
    }



    public function getPieceJustificativeVisualisation(): bool
    {
        return $this->pieceJustificativeVisualisation;
    }



    public function setPieceJustificativeVisualisation(bool $pieceJustificativeVisualisation): Statut
    {
        $this->pieceJustificativeVisualisation = $pieceJustificativeVisualisation;

        return $this;
    }



    public function getPieceJustificativeEdition(): bool
    {
        return $this->pieceJustificativeEdition;
    }



    public function setPieceJustificativeEdition(bool $pieceJustificativeEdition): Statut
    {
        $this->pieceJustificativeEdition = $pieceJustificativeEdition;

        return $this;
    }



    public function getConseilRestreint(): bool
    {
        return $this->conseilRestreint;
    }



    public function setConseilRestreint(bool $conseilRestreint): Statut
    {
        $this->conseilRestreint = $conseilRestreint;

        return $this;
    }



    public function getConseilRestreintVisualisation(): bool
    {
        return $this->conseilRestreintVisualisation;
    }



    public function setConseilRestreintVisualisation(bool $conseilRestreintVisualisation): Statut
    {
        $this->conseilRestreintVisualisation = $conseilRestreintVisualisation;

        return $this;
    }



    public function getConseilRestreintDureeVie(): int
    {
        return $this->conseilRestreintDureeVie;
    }



    public function setConseilRestreintDureeVie(int $conseilRestreintDureeVie): Statut
    {
        $this->conseilRestreintDureeVie = $conseilRestreintDureeVie;

        return $this;
    }



    public function getConseilAcademique(): bool
    {
        return $this->conseilAcademique;
    }



    public function setConseilAcademique(bool $conseilAcademique): Statut
    {
        $this->conseilAcademique = $conseilAcademique;

        return $this;
    }



    public function getConseilAcademiqueVisualisation(): bool
    {
        return $this->conseilAcademiqueVisualisation;
    }



    public function setConseilAcademiqueVisualisation(bool $conseilAcademiqueVisualisation): Statut
    {
        $this->conseilAcademiqueVisualisation = $conseilAcademiqueVisualisation;

        return $this;
    }



    public function getConseilAcademiqueDureeVie(): int
    {
        return $this->conseilAcademiqueDureeVie;
    }



    public function setConseilAcademiqueDureeVie(int $conseilAcademiqueDureeVie): Statut
    {
        $this->conseilAcademiqueDureeVie = $conseilAcademiqueDureeVie;

        return $this;
    }



    public function getContrat(): bool
    {
        return $this->contrat;
    }



    public function setContrat(bool $contrat): Statut
    {
        $this->contrat = $contrat;

        return $this;
    }



    public function getContratVisualisation(): bool
    {
        return $this->contratVisualisation;
    }



    public function setContratVisualisation(bool $contratVisualisation): Statut
    {
        $this->contratVisualisation = $contratVisualisation;

        return $this;
    }



    public function getContratDepot(): bool
    {
        return $this->contratDepot;
    }



    public function setContratDepot(bool $contratDepot): Statut
    {
        $this->contratDepot = $contratDepot;

        return $this;
    }



    public function getServicePrevu(): bool
    {
        return $this->servicePrevu;
    }



    public function setServicePrevu(bool $servicePrevu): Statut
    {
        $this->servicePrevu = $servicePrevu;

        return $this;
    }



    public function getServicePrevuVisualisation(): bool
    {
        return $this->servicePrevuVisualisation;
    }



    public function setServicePrevuVisualisation(bool $servicePrevuVisualisation): Statut
    {
        $this->servicePrevuVisualisation = $servicePrevuVisualisation;

        return $this;
    }



    public function getServicePrevuEdition(): bool
    {
        return $this->servicePrevuEdition;
    }



    public function setServicePrevuEdition(bool $servicePrevuEdition): Statut
    {
        $this->servicePrevuEdition = $servicePrevuEdition;

        return $this;
    }



    public function getServiceRealise(): bool
    {
        return $this->serviceRealise;
    }



    public function setServiceRealise(bool $serviceRealise): Statut
    {
        $this->serviceRealise = $serviceRealise;

        return $this;
    }



    public function getServiceRealiseVisualisation(): bool
    {
        return $this->serviceRealiseVisualisation;
    }



    public function setServiceRealiseVisualisation(bool $serviceRealiseVisualisation): Statut
    {
        $this->serviceRealiseVisualisation = $serviceRealiseVisualisation;

        return $this;
    }



    public function getServiceRealiseEdition(): bool
    {
        return $this->serviceRealiseEdition;
    }



    public function setServiceRealiseEdition(bool $serviceRealiseEdition): Statut
    {
        $this->serviceRealiseEdition = $serviceRealiseEdition;

        return $this;
    }



    public function getServiceExterieur(): bool
    {
        return $this->serviceExterieur;
    }



    public function setServiceExterieur(bool $serviceExterieur): Statut
    {
        $this->serviceExterieur = $serviceExterieur;

        return $this;
    }



    public function getReferentielPrevu(): bool
    {
        return $this->referentielPrevu;
    }



    public function setReferentielPrevu(bool $referentielPrevu): Statut
    {
        $this->referentielPrevu = $referentielPrevu;

        return $this;
    }



    public function getReferentielPrevuVisualisation(): bool
    {
        return $this->referentielPrevuVisualisation;
    }



    public function setReferentielPrevuVisualisation(bool $referentielPrevuVisualisation): Statut
    {
        $this->referentielPrevuVisualisation = $referentielPrevuVisualisation;

        return $this;
    }



    public function getReferentielPrevuEdition(): bool
    {
        return $this->referentielPrevuEdition;
    }



    public function setReferentielPrevuEdition(bool $referentielPrevuEdition): Statut
    {
        $this->referentielPrevuEdition = $referentielPrevuEdition;

        return $this;
    }



    public function getReferentielRealise(): bool
    {
        return $this->referentielRealise;
    }



    public function setReferentielRealise(bool $referentielRealise): Statut
    {
        $this->referentielRealise = $referentielRealise;

        return $this;
    }



    public function getReferentielRealiseVisualisation(): bool
    {
        return $this->referentielRealiseVisualisation;
    }



    public function setReferentielRealiseVisualisation(bool $referentielRealiseVisualisation): Statut
    {
        $this->referentielRealiseVisualisation = $referentielRealiseVisualisation;

        return $this;
    }



    public function getReferentielRealiseEdition(): bool
    {
        return $this->referentielRealiseEdition;
    }



    public function setReferentielRealiseEdition(bool $referentielRealiseEdition): Statut
    {
        $this->referentielRealiseEdition = $referentielRealiseEdition;

        return $this;
    }



    public function getCloture(): bool
    {
        return $this->cloture;
    }



    public function setCloture(bool $cloture): Statut
    {
        $this->cloture = $cloture;

        return $this;
    }



    public function getModificationServiceDu(): bool
    {
        return $this->modificationServiceDu;
    }



    public function setModificationServiceDu(bool $modificationServiceDu): Statut
    {
        $this->modificationServiceDu = $modificationServiceDu;

        return $this;
    }



    public function getModificationServiceDuVisualisation(): bool
    {
        return $this->modificationServiceDuVisualisation;
    }



    public function setModificationServiceDuVisualisation(bool $modificationServiceDuVisualisation): Statut
    {
        $this->modificationServiceDuVisualisation = $modificationServiceDuVisualisation;

        return $this;
    }



    public function getPaiementVisualisation(): bool
    {
        return $this->paiementVisualisation;
    }



    public function setPaiementVisualisation(bool $paiementVisualisation): Statut
    {
        $this->paiementVisualisation = $paiementVisualisation;

        return $this;
    }



    public function getMotifNonPaiement(): bool
    {
        return $this->motifNonPaiement;
    }



    public function setMotifNonPaiement(bool $motifNonPaiement): Statut
    {
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }



    public function getFormuleVisualisation(): bool
    {
        return $this->formuleVisualisation;
    }



    public function setFormuleVisualisation(bool $formuleVisualisation): Statut
    {
        $this->formuleVisualisation = $formuleVisualisation;

        return $this;
    }



    public function getCodesCorresp1(): ?string
    {
        return $this->codesCorresp1;
    }



    public function setCodesCorresp1(?string $codesCorresp1): Statut
    {
        $this->codesCorresp1 = $codesCorresp1;

        return $this;
    }



    public function getCodesCorresp2(): ?string
    {
        return $this->codesCorresp2;
    }



    public function setCodesCorresp2(?string $codesCorresp2): Statut
    {
        $this->codesCorresp2 = $codesCorresp2;

        return $this;
    }



    public function getCodesCorresp3(): ?string
    {
        return $this->codesCorresp3;
    }



    public function setCodesCorresp3(?string $codesCorresp3): Statut
    {
        $this->codesCorresp3 = $codesCorresp3;

        return $this;
    }



    public function getCodesCorresp4(): ?string
    {
        return $this->codesCorresp4;
    }



    public function setCodesCorresp4(?string $codesCorresp4): Statut
    {
        $this->codesCorresp4 = $codesCorresp4;

        return $this;
    }



    /**
     * Retourne la liste des privilèges associés à un statut sous forme de tableau associatif :
     *
     * [privilege] => boolean
     *
     * @return array
     */
    public function getPrivileges(): array
    {
        $privileges = [
            Privileges::INTERVENANT_FICHE                          => true,
            Privileges::ODF_ELEMENT_VISUALISATION                  => ($this->servicePrevu && $this->servicePrevuVisualisation) || ($this->serviceRealise && $this->serviceRealiseVisualisation),
            Privileges::ODF_ETAPE_VISUALISATION                    => ($this->servicePrevu && $this->servicePrevuVisualisation) || ($this->serviceRealise && $this->serviceRealiseVisualisation),
            Privileges::INTERVENANT_CALCUL_HETD                    => $this->formuleVisualisation,
            Privileges::MODIF_SERVICE_DU_ASSOCIATION               => $this->modificationServiceDu,
            Privileges::MODIF_SERVICE_DU_VISUALISATION             => $this->modificationServiceDu && $this->modificationServiceDuVisualisation,
            Privileges::DOSSIER_VISUALISATION                      => $this->dossier && $this->dossierVisualisation,
            Privileges::DOSSIER_EDITION                            => $this->dossier && $this->dossierEdition,
            Privileges::DOSSIER_ADRESSE_VISUALISATION              => $this->dossier && $this->dossierVisualisation && $this->dossierAdresse,
            Privileges::DOSSIER_ADRESSE_EDITION                    => $this->dossier && $this->dossierEdition && $this->dossierAdresse,
            Privileges::DOSSIER_BANQUE_VISUALISATION               => $this->dossier && $this->dossierVisualisation && $this->dossierBanque,
            Privileges::DOSSIER_BANQUE_EDITION                     => $this->dossier && $this->dossierEdition && $this->dossierBanque,
            Privileges::DOSSIER_CONTACT_VISUALISATION              => $this->dossier && $this->dossierVisualisation && $this->dossierContact,
            Privileges::DOSSIER_CONTACT_EDITION                    => $this->dossier && $this->dossierEdition && $this->dossierContact,
            Privileges::DOSSIER_EMPLOYEUR_VISUALISATION            => $this->dossier && $this->dossierVisualisation && $this->dossierEmployeur,
            Privileges::DOSSIER_EMPLOYEUR_EDITION                  => $this->dossier && $this->dossierEdition && $this->dossierEmployeur,
            Privileges::DOSSIER_IDENTITE_VISUALISATION             => $this->dossier && $this->dossierVisualisation && $this->dossierIdentiteComplementaire,
            Privileges::DOSSIER_IDENTITE_EDITION                   => $this->dossier && $this->dossierEdition && $this->dossierIdentiteComplementaire,
            Privileges::DOSSIER_INSEE_VISUALISATION                => $this->dossier && $this->dossierVisualisation && $this->dossierInsee,
            Privileges::DOSSIER_INSEE_EDITION                      => $this->dossier && $this->dossierEdition && $this->dossierInsee,
            Privileges::DOSSIER_CHAMP_AUTRE_1_VISUALISATION        => $this->dossier && $this->dossierAutre1 && $this->dossierAutre1Visualisation,
            Privileges::DOSSIER_CHAMP_AUTRE_1_EDITION              => $this->dossier && $this->dossierAutre1 && $this->dossierAutre1Edition,
            Privileges::DOSSIER_CHAMP_AUTRE_2_VISUALISATION        => $this->dossier && $this->dossierAutre2 && $this->dossierAutre2Visualisation,
            Privileges::DOSSIER_CHAMP_AUTRE_2_EDITION              => $this->dossier && $this->dossierAutre2 && $this->dossierAutre2Edition,
            Privileges::DOSSIER_CHAMP_AUTRE_3_VISUALISATION        => $this->dossier && $this->dossierAutre3 && $this->dossierAutre3Visualisation,
            Privileges::DOSSIER_CHAMP_AUTRE_3_EDITION              => $this->dossier && $this->dossierAutre3 && $this->dossierAutre3Edition,
            Privileges::DOSSIER_CHAMP_AUTRE_4_VISUALISATION        => $this->dossier && $this->dossierAutre4 && $this->dossierAutre4Visualisation,
            Privileges::DOSSIER_CHAMP_AUTRE_4_EDITION              => $this->dossier && $this->dossierAutre4 && $this->dossierAutre4Edition,
            Privileges::DOSSIER_CHAMP_AUTRE_5_VISUALISATION        => $this->dossier && $this->dossierAutre5 && $this->dossierAutre5Visualisation,
            Privileges::DOSSIER_CHAMP_AUTRE_5_EDITION              => $this->dossier && $this->dossierAutre5 && $this->dossierAutre5Edition,
            Privileges::PIECE_JUSTIFICATIVE_VISUALISATION          => $this->pieceJustificativeVisualisation,
            Privileges::PIECE_JUSTIFICATIVE_TELECHARGEMENT         => $this->pieceJustificativeVisualisation,
            Privileges::PIECE_JUSTIFICATIVE_EDITION                => $this->pieceJustificativeEdition,
            Privileges::PIECE_JUSTIFICATIVE_ARCHIVAGE              => $this->pieceJustificativeEdition,
            Privileges::ENSEIGNEMENT_VISUALISATION                 => ($this->servicePrevu && $this->servicePrevuVisualisation) || ($this->serviceRealise && $this->serviceRealiseVisualisation),
            Privileges::ENSEIGNEMENT_EDITION                       => ($this->servicePrevu && $this->servicePrevuEdition) || ($this->serviceRealise && $this->serviceRealiseEdition),
            Privileges::ENSEIGNEMENT_EXTERIEUR                     => ($this->servicePrevu || $this->serviceRealise) && $this->serviceExterieur,
            Privileges::REFERENTIEL_VISUALISATION                  => ($this->referentielPrevu && $this->referentielPrevuVisualisation) || ($this->referentielRealise && $this->referentielRealiseVisualisation),
            Privileges::REFERENTIEL_EDITION                        => ($this->referentielPrevu && $this->referentielPrevuEdition) || ($this->referentielRealise && $this->referentielRealiseEdition),
            Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION   => $this->conseilRestreint && $this->conseilRestreintVisualisation,
            Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION  => $this->conseilAcademique && $this->conseilAcademiqueVisualisation,
            Privileges::CONTRAT_VISUALISATION                      => $this->contrat && $this->contratVisualisation,
            Privileges::CONTRAT_DEPOT_RETOUR_SIGNE                 => $this->contrat && $this->contratDepot,
            Privileges::MISE_EN_PAIEMENT_VISUALISATION_INTERVENANT => $this->paiementVisualisation,
            Privileges::CLOTURE_CLOTURE                            => $this->cloture && ($this->serviceRealiseEdition || $this->referentielRealiseEdition),
        ];

        return $privileges;
    }



    public function hasPrivilege(string $privilege): bool
    {
        $privileges = $this->getPrivileges();

        return isset($privileges[$privilege]) && $privileges[$privilege];
    }

}
