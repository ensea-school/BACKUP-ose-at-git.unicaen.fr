<?php

namespace ExportRh\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractService;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use ExportRh\Entity\IntervenantRHExportParams;
use Zend\Form\Fieldset;

/**
 * Description of FonctionReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ExportRhService extends AbstractService
{
    use ParametresServiceAwareTrait;
    use IntervenantServiceAwareTrait;

    /**
     * @var IntervenantRHExportParams
     */
    private   $intervenantEportParams;

    protected $connecteur;



    public function __construct($connecteur)
    {
        $this->connecteur = $connecteur;
    }



    public function getListIntervenantRh($nomUsuel, $prenom, $insee)
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



    public function getIntervenantRh($intervenant)
    {
        $intervenantRh = $this->connecteur->recupererIntervenantRh($intervenant);

        return $intervenantRh;
    }



    public function getDonneesAdministrativeIntervenantRh($intervenant)
    {
        $donneesAdministratives = $this->connecteur->recupererDonneesAdministrativesIntervenantRh($intervenant);

        return $donneesAdministratives;
    }



    public function getAffectationEnCoursIntervenantRh($intervenant)
    {
        $affectation = $this->connecteur->recupererAffectationEnCoursIntervenantRh($intervenant);


        return $affectation;
    }



    public function getContratEnCoursIntervenantRh($intervenant)
    {
        return true;
    }



    public function getListeUO()
    {
        return $this->connecteur->recupererListeUO();
    }



    public function getListePositions()
    {
        return $this->connecteur->recupererListePositions();
    }



    public function getListeEmplois()
    {
        return $this->connecteur->recupererListeEmplois();
    }



    public function getListeStatuts()
    {
        return $this->connecteur->recupererListeStatuts();
    }



    public function getListeModalites()
    {
        return $this->connecteur->recupererListeModalites();
    }



    public function priseEnChargeIntrervenantRh(Intervenant $intervenant, $datas)
    {
        return $this->connecteur->prendreEnChargeIntervenantRh($intervenant, $datas);
    }



    public function renouvellementIntervenantRh(Intervenant $intervenant, $datas)
    {
        return $this->connecteur->renouvellerIntervenantRH($intervenant, $datas);
    }



    public function synchroniserDonneesPersonnellesIntervenantRh(Intervenant $intervenant, $datas)
    {
        return $this->connecteur->synchroniserDonneesPersonnellesIntervenantRh($intervenant, $datas);
    }



    public function getFieldsetConnecteur(): Fieldset
    {
        return $this->connecteur->recupererFormulairePriseEnCharge();
    }



    public function getConnecteurName(): string
    {
        return $this->connecteur->getConnecteurName();
    }



    public function getConnecteur()
    {
        return $this->connecteur;
    }

}