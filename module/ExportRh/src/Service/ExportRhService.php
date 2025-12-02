<?php

namespace ExportRh\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Annee;
use Application\Service\AbstractService;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use ExportRh\Entity\IntervenantRHExportParams;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Laminas\Form\Fieldset;

/**
 * ExportRhService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class ExportRhService extends AbstractService
{
    use ParametresServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use SourceServiceAwareTrait;

    protected $connecteur;

    protected $config;

    /**
     * @var IntervenantRHExportParams
     */
    private $intervenantEportParams;



    public function __construct ($connecteur, $config)
    {
        $this->connecteur = $connecteur;
        $this->config     = $config;
    }



    public function getListIntervenantRh ($nomUsuel, $prenom, $insee)
    {

        $listIntervenantRh  = $this->connecteur->rechercherIntervenantRH($nomUsuel, $prenom, $insee);
        $intervenantService = $this->getServiceIntervenant();
        if (!empty($listIntervenantRh)) {
            foreach ($listIntervenantRh as $key => $intervenantRh) {
                $intervenant = $intervenantService->getByCodeRh($intervenantRh->getCodeRh());
                if ($intervenant) {
                    $intervenantRh->setIntervenant($intervenant);
                    $listIntervenantRh[$key] = $intervenantRh;
                }
            }
        }

        return $listIntervenantRh;
    }



    public function getIntervenantRh ($intervenant)
    {
        $intervenantRh = $this->connecteur->recupererIntervenantRh($intervenant);

        return $intervenantRh;
    }



    public function getDonneesAdministrativeIntervenantRh ($intervenant)
    {
        $donneesAdministratives = $this->connecteur->recupererDonneesAdministrativesIntervenantRh($intervenant);

        return $donneesAdministratives;
    }



    public function getAffectationEnCoursIntervenantRh ($intervenant)
    {
        $affectation = $this->connecteur->recupererAffectationEnCoursIntervenantRh($intervenant);


        return $affectation;
    }



    public function getContratEnCoursIntervenantRh ($intervenant)
    {
        $contrat = $this->connecteur->recupererContratEnCoursIntervenantRh($intervenant);

        return $contrat;
    }



    public function getListeUO ()
    {
        return $this->connecteur->recupererListeUO();
    }



    public function getListePositions ()
    {
        return $this->connecteur->recupererListePositions();
    }



    public function getListeEmplois ()
    {
        return $this->connecteur->recupererListeEmplois();
    }



    public function getListeStatuts ()
    {
        return $this->connecteur->recupererListeStatuts();
    }



    public function getListeModalites ()
    {
        return $this->connecteur->recupererListeModalites();
    }



    public function getListContrats ()
    {
        return $this->connecteur->recupererListeContrats();
    }



    public function priseEnChargeIntrervenantRh (Intervenant $intervenant, $datas)
    {
        return $this->connecteur->prendreEnChargeIntervenantRh($intervenant, $datas);
    }



    public function renouvellementIntervenantRh (Intervenant $intervenant, $datas)
    {
        return $this->connecteur->renouvellerIntervenantRH($intervenant, $datas);
    }



    public function synchroniserDonneesPersonnellesIntervenantRh (Intervenant $intervenant, $datas)
    {
        return $this->connecteur->synchroniserDonneesPersonnellesIntervenantRh($intervenant, $datas);
    }



    public function cloreDossier(Intervenant $intervenant, string $codeStatutSiham)
    {
        return $this->connecteur->cloreDossier($intervenant, $codeStatutSiham);
    }



    public function getFieldsetConnecteur (): Fieldset
    {
        return $this->connecteur->recupererFormulairePriseEnCharge();
    }



    public function getConnecteurName (): string
    {
        return $this->connecteur->getConnecteurName();
    }



    public function getConnecteur ()
    {
        return $this->connecteur;
    }



    public function getAnneeUniversitaireEnCours (): ?Annee
    {
        $annee = $this->getServiceParametres()->get('annee');

        return $this->getServiceAnnee()->get($annee);
    }



    public function haveToSyncCode (): bool
    {
        $config = $this->config;
        return $config['export-rh']['sync-code'] ?? false;
    }



    public function haveToSyncSource (): bool
    {

        $config = $this->config;
        $syncSource = $config['export-rh']['sync-source'] ?? '';
        //On regarde si le code fourni correspond bien à une source valide
        $source = $this->getServiceSource()->getByCode($syncSource);
        if (!empty($source)) {
            return true;
        }

        return false;
    }

    public function haveToSyncCodeRh(): bool
    {
        $config = $this->config;
        return $config['export-rh']['sync-code-rh'] ?? false;

    }


}