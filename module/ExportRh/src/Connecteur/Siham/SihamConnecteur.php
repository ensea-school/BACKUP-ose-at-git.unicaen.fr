<?php

namespace ExportRh\Connecteur\Siham;


use ExportRh\Connecteur\ConnecteurRhInterface;
use ExportRh\Entity\Intervenant;
use ExportRh\Entity\IntervenantRH;
use UnicaenSiham\Service\Siham;

class SihamConnecteur implements ConnecteurRhInterface
{

    public Siham $siham;



    public function __construct(Siham $siham)
    {
        $this->siham = $siham;
    }



    public function rechercherIntervenant($nom, $prenom, $insee, $dateNaissance): ?IntervenantRH
    {
        $params        = [
            'nomUsuel' => $nom,
        ];
        $result        = $this->siham->rechercherAgent($params);
        $intervenantRH = new IntervenantRH();
        $intervenantRH->setNomUsuel($result->getNomUsuel());

        return $intervenantRH;
    }



    public function trouverIntervenant(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRH
    {

        $intervenantRH = null;
        if (!empty($intervenant->getCodeRh())) {
            $params        =
                [
                    'listeMatricules' => [$intervenant->getCodeRh()],
                ];
            $agent         = $this->siham->recupererDonneesPersonnellesAgent($params);
            $intervenantRH = new IntervenantRH();
            $intervenantRH->setNomUsuel($agent->getNomUsuel());
            $intervenantRH->setPrenom($agent->getPrenom());
            $intervenantRH->setTelPerso($agent->getTelephonePerso());
            $intervenantRH->setTelPro($agent->getTelephonePro());
        }

        return $intervenantRH;
    }



    public
    function prendreEnChargeIntervenant(\Application\Entity\Db\Intervenant $intervenant): Intervenant
    {

    }



    public
    function exporterIntervenant(Intervenant $intervenant): bool
    {
        // TODO: Implement intervenantExport() method.
    }



    public
    function test()
    {
        echo 'test réussi';
    }
}