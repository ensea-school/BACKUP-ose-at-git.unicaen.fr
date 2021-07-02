<?php

namespace ExportRh\Connecteur\Siham;


use ExportRh\Connecteur\ConnecteurRhInterface;
use ExportRh\Entity\IntervenantRh;
use UnicaenSiham\Service\Siham;


class SihamConnecteur implements ConnecteurRhInterface
{

    public Siham $siham;



    public function __construct(Siham $siham)
    {
        $this->siham = $siham;
    }



    public function rechercherIntervenantRh($nomUsuel = '', $prenom = '', $insee = ''): array
    {
        $params = [
            'nomUsuel'    => $nomUsuel,
            'prenom'      => $prenom,
            'numeroInsee' => $insee,

        ];

        $listIntervenantRh = [];
        //$result        = $this->siham->rechercherAgent($params);
        $result = $this->siham->recupererListeAgents($params);

        if (!empty($result)) {
            foreach ($result as $v) {
                $intervenantRh = new IntervenantRh();
                $intervenantRh->setNomUsuel($v->getNomUsuel());
                $intervenantRh->setPrenom($v->getPrenom());
                $intervenantRh->setCodeRh($v->getMatricule());
                $dateNaissance = new \DateTime($v->getDateNaissance());
                $intervenantRh->setDateNaissance($dateNaissance);
                $intervenantRh->setNumeroInsee($v->getNumeroInsee());
                $listIntervenantRh[] = $intervenantRh;
            }
        }


        return $listIntervenantRh;
    }



    public function trouverIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRh
    {

        $intervenantRh = null;
        if (!empty($intervenant->getCodeRh())) {
            $params        =
                [
                    'listeMatricules' => [$intervenant->getCodeRh()],
                ];
            $agent         = $this->siham->recupererDonneesPersonnellesAgent($params);
            $intervenantRh = new IntervenantRH();
            $intervenantRh->setNomUsuel($agent->getNomUsuel());
            $intervenantRh->setPrenom($agent->getPrenom());
            $intervenantRh->setDateNaissance(new \DateTime($agent->getDateNaissance()));
            $intervenantRh->setTelPerso($agent->getTelephonePerso());
            $intervenantRh->setTelPro($agent->getTelephonePro());
            $intervenantRh->setNumeroInsee($agent->getNumeroInsee());
        }

        return $intervenantRh;
    }



    public function prendreEnChargeIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRh
    {

    }

}