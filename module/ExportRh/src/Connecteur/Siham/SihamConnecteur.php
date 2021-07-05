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
            $codeRh = $intervenant->getCodeRh();
            //Si code RH
            if (!strstr($codeRh, 'UCN')) {
                $codeRh = $this->siham->getCodeAdministration() . str_pad($codeRh, 9, '0', STR_PAD_LEFT);
            }

            $params =
                [
                    'listeMatricules' => [$codeRh],
                ];

            $agent = $this->siham->recupererDonneesPersonnellesAgent($params);

            if (!empty($agent)) {
                $intervenantRh = new IntervenantRH();
                $intervenantRh->setNomUsuel($agent->getNomUsuel());
                $intervenantRh->setPrenom($agent->getPrenom());
                $intervenantRh->setDateNaissance(new \DateTime($agent->getDateNaissance()));
                $intervenantRh->setTelPerso($agent->getTelephonePerso());
                $intervenantRh->setTelPro($agent->getTelephonePro());
                $intervenantRh->setNumeroInsee($agent->getNumeroInseeDefinitif());

                return $intervenantRh;
            }
        }

        return null;
    }



    public function prendreEnChargeIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRh
    {

    }



    public function recupererListeUO(): ?array
    {
        /*On récupére les UO de type composante*/
        $params = [
            'codeAdministration' => '',
            'listeUO'            => [[
                                         'typeUO' => 'COP',
                                     ]],
        ];

        $uo = $this->siham->recupererListeUO($params);

        return $uo;
    }



    public function recupererListePositions(): ?array
    {
        return $this->siham->recupererListePositions();
    }



    public function recupererListeEmplois(): array
    {
        return $this->siham->recupererListeEmplois();
    }

}