<?php

namespace Application\Processus\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\ServiceAPayerInterface;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\IntervenantSuppressionData;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\MiseEnPaiementIntervenantStructure;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\VolumeHoraireReferentiel;
use Application\Service\Traits\DossierAwareTrait;


class SuppressionDataProcessus
{

    private static $instance;

    use IntervenantAwareTrait;
    use DossierAwareTrait;

    /**
     * @var IntervenantSuppressionData
     */
    private $data;

    /**
     * @var IntervenantSuppressionData[]
     */
    private $srsr;



    public static function run(Intervenant $intervenant)
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        self::$instance->setIntervenant($intervenant);

        return self::$instance->makeData();
    }



    private function newIsd($entity = null)
    {
        $isd = new IntervenantSuppressionData();
        if ($entity) {
            $isd->setEntity($entity);
        }

        return $isd;
    }



    private function addIsdSR($service, $sRub, IntervenantSuppressionData $isd)
    {
        $k1        = $service instanceof Service ? 'service' : 'referentiel';
        $structure = $service->getStructure() ?: $service->getIntervenant()->getStructure();
        $k2        = $structure->getId();
        $k3        = $service->getId();
        $k4        = $sRub;

        if (!$this->data[$k1][$k2]) {
            $this->data[$k1][$k2] = $this->newIsd($structure);
        }

        if (!$this->data[$k1][$k2][$k3]) {
            $this->data[$k1][$k2][$k3] = $this->newIsd($service);
        }

        if (!$this->data[$k1][$k2][$k3][$k4]) {
            $this->data[$k1][$k2][$k3][$k4] = clone($this->srsr[$sRub]);
        }

        $this->data[$k1][$k2][$k3][$k4][] = $isd;
    }



    protected function makeData()
    {
        $rubriques     = [
            'modifs-service-du'           => 'Modifications de service dû',
            'dossier'                     => 'Données personnelles',
            'pieces-jointes'              => 'Pièces justificatives',
            'agrement-conseil-academique' => 'Agrément du conseil académique',
            'agrement-conseil-restreint'  => 'Agréments du conseil restreint',
            'contrats'                    => 'Contrats et avenants',
            'service'                     => 'Enseignements',
            'referentiel'                 => 'Référentiel',
        ];
        $sousRubriques = [
            TypeVolumeHoraire::CODE_PREVU   => 'Prévisionnel',
            TypeVolumeHoraire::CODE_REALISE => 'Réalisé',
            'dmep'                          => 'Demande de mise ne paiement',
            'mep'                           => 'Mise en paiement',
        ];

        $this->data = $this->newIsd($this->getIntervenant());
        foreach ($rubriques as $k => $l) {
            $rd = $this->newIsd();
            $rd->setId($k);
            $rd->setSubject('rubrique');
            $rd->setLabel($l);

            $this->data[] = $rd;
        }

        $ordre = 0;
        foreach ($sousRubriques as $k => $l) {
            $ordre++;
            $srd = $this->newIsd();
            $srd->setId($k);
            $srd->setSubject('sous-rubrique');
            $srd->setLabel($l);
            $srd->setOrdre($ordre);

            $this->srsr[$k] = $srd;
        }

        $this->makeServiceDu();
        $this->makeDossier();
        $this->makeService();
        $this->makeReferentiel();
        $this->makePiecesJointes();
        $this->makeAgrements();
        $this->makeContrats();
        $this->makePaiements();

        /* Purge */
        foreach ($rubriques as $k => $l) {
            if (!$this->data[$k]->hasChildren()) {
                unset($this->data[$k]);
            }
        }

        return $this->data;
    }



    private function makeServiceDu()
    {
        $msds = $this->getIntervenant()->getModificationServiceDu()->filter(function ($m) {
            return $m->estNonHistorise();
        });
        foreach ($msds as $msd) {
            $this->data['modifs-service-du'][] = $this->newIsd($msd);
        }
    }



    private function makeDossier()
    {
        /* Récup des données personnelles */
        $dossier = $this->getIntervenant()->getDossier();
        if ($dossier && $dossier->estNonHistorise()) {
            $d = $this->newIsd($dossier);

            $validation = $this->getServiceDossier()->getValidation($this->getIntervenant());
            if ($validation) {
                $d[] = $this->newIsd($validation);
            }
            $this->data['dossier'][] = $d;
        }
    }



    private function makeService()
    {
        /* Récup des services */
        $service = $this->getIntervenant()->getService()->filter(function ($s) {
            return $s->estNonHistorise();
        });
        if ($service->count() > 1) {
            /** @var Service $s */
            foreach ($service as $s) {
                $vhs = $s->getVolumeHoraire()->filter(function ($v) {
                    return $v->estNonHistorise();
                });

                /** @var VolumeHoraire $vh */
                foreach ($vhs as $vh) {
                    $sr        = 'service';
                    $structure = $s->getStructure();
                    $service   = $s;
                    $sRub      = $vh->getTypeVolumeHoraire()->getCode();


                    $vs = $vh->getValidation()->filter(function ($v) {
                        return $v->estNonHistorise();
                    });

                    $vhd = $this->newIsd($vh);
                    foreach ($vs as $v) {
                        $vhd[] = $this->newIsd($v);
                    }

                    $this->addIsdSR($s, $sRub, $vhd);
                }
            }
        }
    }



    private function makeReferentiel()
    {
        /* Récup du référentiel */
        $refs = $this->getIntervenant()->getServiceReferentiel()->filter(function ($s) {
            return $s->estNonHistorise();
        });
        if ($refs->count() > 1) {
            /** @var ServiceReferentiel $ref */
            foreach ($refs as $ref) {
                $vhs = $ref->getVolumeHoraireReferentiel()->filter(function ($v) {
                    return $v->estNonHistorise();
                });

                /** @var VolumeHoraireReferentiel $vh */
                foreach ($vhs as $vh) {
                    $sRub = $vh->getTypeVolumeHoraire()->getCode();

                    $vs = $vh->getValidation()->filter(function ($v) {
                        return $v->estNonHistorise();
                    });

                    $vhd = $this->newIsd($vh);
                    foreach ($vs as $v) {
                        $vhd[] = $this->newIsd($v);
                    }

                    $this->addIsdSR($ref, $sRub, $vhd);
                }
            }
        }
    }



    private function makePiecesJointes()
    {
        /* Pièces justificatives */
        $pjs = $this->getIntervenant()->getPieceJointe()->filter(function ($pj) {
            return $pj->estNonHistorise();
        });
        /** @var PieceJointe $pj */
        foreach ($pjs as $pj) {

            $dpj = $this->newIsd($pj);

            $children = $pj->getFichier()->filter(function ($f) {
                return $f->estNonHistorise();
            });
            foreach ($children as $f) {
                $dpj[] = $this->newIsd($f);
            }

            if (($v = $pj->getValidation()) && $v->estNonHistorise()) {
                $dpj[] = $this->newIsd($v);
            }

            $this->data['pieces-jointes'][] = $dpj;
        }
    }



    private function makeAgrements()
    {
        /* Agréments */
        $agrs = $this->getIntervenant()->getAgrement()->filter(function ($a) {
            return $a->estNonHistorise();
        });
        /** @var Agrement $agr */
        foreach ($agrs as $agr) {
            $key                              = str_replace('_', '-', strtolower($agr->getType()->getCode()));
            $this->data['agrement-' . $key][] = $this->newIsd($agr);
        }
    }



    private function makeContrats()
    {
        /* Contrats */
        $cs = $this->getIntervenant()->getContrat()->filter(function ($c) {
            return $c->estNonHistorise();
        });
        /** @var Contrat $c */
        foreach ($cs as $c) {
            $dc = $this->newIsd($c);

            $children = $c->getFichier()->filter(function ($f) {
                return $f->estNonHistorise();
            });
            foreach ($children as $f) {
                $dc[] = $this->newIsd($f);
            }

            if (($v = $c->getValidation()) && $v->estNonHistorise()) {
                $dc[] = $this->newIsd($v);
            }

            $this->data['contrats'][] = $dc;
        }
    }



    private function makePaiements()
    {
        /* Paiements */
        $miss = $this->getIntervenant()->getMiseEnPaiementIntervenantStructure();
        $meps = [];
        /** @var MiseEnPaiementIntervenantStructure $mis */
        foreach ($miss as $mis) {
            $mep = $mis->getMiseEnPaiement();
            if ($mep->estNonHistorise()) {

                $dm = $this->newIsd($mep);

                $service = $mep->getFormuleResultatService() ? $mep->getFormuleResultatService()->getService() : $mep->getFormuleResultatServiceReferentiel()->getServiceReferentiel();
                $sRub = $mep->getPeriodePaiement() ? 'mep' : 'dmep';

                $this->addIsdSR($service, $sRub, $dm);
            }
        }
    }
}