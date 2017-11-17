<?php

namespace Application\Processus\Intervenant;

use Application\Entity\Db\Intervenant;
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
use Application\Service\Traits\AgrementServiceAwareTrait;
use Application\Service\Traits\ContratAwareTrait;
use Application\Service\Traits\DbEventServiceAwareTrait;
use Application\Service\Traits\DossierAwareTrait;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\MiseEnPaiementAwareTrait;
use Application\Service\Traits\ModificationServiceDuAwareTrait;
use Application\Service\Traits\PieceJointeAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\VolumeHoraireAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielAwareTrait;


class SuppressionDataProcessus
{

    private static $instance;

    use IntervenantAwareTrait;
    use DossierAwareTrait;
    use ModificationServiceDuAwareTrait;
    use ValidationAwareTrait;
    use MiseEnPaiementAwareTrait;
    use FichierServiceAwareTrait;
    use PieceJointeAwareTrait;
    use AgrementServiceAwareTrait;
    use VolumeHoraireAwareTrait;
    use VolumeHoraireReferentielAwareTrait;
    use ServiceServiceAwareTrait;
    use ServiceReferentielAwareTrait;
    use \Application\Service\Traits\IntervenantAwareTrait;
    use ContratAwareTrait;
    use DbEventServiceAwareTrait;

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



    public static function delete(IntervenantSuppressionData $isd, array $ids)
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance->deleteData($isd, $ids);
    }



    private function deleteData(IntervenantSuppressionData $isd, array $ids)
    {
        $isd->order();

        $entities = [];
        foreach ($ids as $id) {
            $i = $isd->findOneByAbsoluteId($id);
            if ($i && $e = $i->getEntity()) {
                if (!$i->isUnbreakable()) {
                    $entities[(new \ReflectionClass($e))->getShortName()][] = $i;
                }
                $p = $i->getParent();
                $i->remove();
                if ($p && !$p->getEntity() && !$p->hasChildren()) {
                    $p->remove();
                }
            }
        }


        $this->getServiceDbEvent()->stopManager();

        /* Mises en paiement */
        if (isset($entities['MiseEnPaiement'])) {
            foreach ($entities['MiseEnPaiement'] as $v) {
                $this->getServiceMiseEnPaiement()->delete($v->getEntity(), false);
            }
        }

        /* Fichiers */
        if (isset($entities['Fichier'])) {
            foreach ($entities['Fichier'] as $v) {
                $this->getServiceFichier()->delete($v->getEntity(), false);
            }
        }

        /* Avenants */
        if (isset($entities['Contrat'])) {
            foreach ($entities['Contrat'] as $v) {
                /** @var Contrat $avenant */
                $avenant = $v->getEntity();
                if ($avenant->estUnAvenant()){
                    $this->getServiceContrat()->delete($v->getEntity(), false);
                }
            }
        }

        /* Contrats */
        if (isset($entities['Contrat'])) {
            foreach ($entities['Contrat'] as $v) {
                $this->getServiceContrat()->delete($v->getEntity(), false);
            }
        }

        /* Validations */
        if (isset($entities['Validation'])) {
            foreach ($entities['Validation'] as $v) {
                $this->getServiceValidation()->delete($v->getEntity(), false);
            }
        }

        /* Volumes horaire */
        if (isset($entities['VolumeHoraire'])) {
            foreach ($entities['VolumeHoraire'] as $v) {
                $this->getServiceVolumeHoraire()->delete($v->getEntity(), false);
            }
        }

        /* Volume horaire Référentiel */
        if (isset($entities['VolumeHoraireReferentiel'])) {
            foreach ($entities['VolumeHoraireReferentiel'] as $v) {
                $this->getServiceVolumeHoraireReferentiel()->delete($v->getEntity(), false);
            }
        }

        /* Service */
        if (isset($entities['Service'])) {
            foreach ($entities['Service'] as $v) {
                $this->getServiceService()->delete($v->getEntity(), false);
            }
        }

        /* Service référentiel */
        if (isset($entities['ServiceReferentiel'])) {
            foreach ($entities['ServiceReferentiel'] as $v) {
                $this->getServiceServiceReferentiel()->delete($v->getEntity(), false);
            }
        }

        /* Agréments */
        if (isset($entities['AgrementService'])) {
            foreach ($entities['AgrementService'] as $v) {
                $this->getServiceAgrement()->delete($v->getEntity(), false);
            }
        }

        /* Pièces justificatives */
        if (isset($entities['PieceJointe'])) {
            foreach ($entities['PieceJointe'] as $v) {
                $this->getServicePieceJointe()->delete($v->getEntity(), false);
            }
        }

        /* Dossier */
        if (isset($entities['Dossier'])) {
            foreach ($entities['Dossier'] as $v) {
                $this->getServiceDossier()->delete($v->getEntity(), false);
            }
        }

        /* Modifications de service du */
        if (isset($entities['ModificationServiceDu'])) {
            foreach ($entities['ModificationServiceDu'] as $v) {
                $this->getServiceModificationServiceDu()->delete($v->getEntity(), false);
            }
        }

        /* Fiche intervenant */
        if (isset($entities['Intervenant'])) {
            foreach ($entities['Intervenant'] as $v) {
                $this->getServiceIntervenant()->delete($v->getEntity(), false);
            }
        }

        $this->getServiceDbEvent()->startManager();
        $this->getServiceDbEvent()->forcerCalculer($this->getIntervenant());

        if (in_array($isd->getAbsoluteId(), $ids)) {
            return null;
        }

        return $isd;
    }



    private function newIsd($entity = null)
    {
        if (is_object($entity) && method_exists($entity, 'getId')) {
            $id = $entity->getId();
        } else {
            $id = (string)$entity;
        }

        $isd = new IntervenantSuppressionData($id);
        if ($entity) {
            $isd->setEntity($entity);
            $isd->setId($entity->getId());
        }

        return $isd;
    }



    private function addIsdSR($service, $sRub = null, IntervenantSuppressionData $isd = null)
    {
        $k1        = $service instanceof Service ? 'service' : 'referentiel';
        $structure = $service->getStructure() ?: $service->getIntervenant()->getStructure();
        $k2        = $structure->getId();
        $k3        = $service->getId();
        $k4        = $sRub;

        if (!$this->data[$k1][$k2]) {
            $this->data[$k1][$k2] = $this->newIsd($structure)->setUnbreakable(true);
        }

        if (!$this->data[$k1][$k2][$k3]) {
            $this->data[$k1][$k2][$k3] = $this->newIsd($service);
        }

        if ($sRub) {
            if (!$this->data[$k1][$k2][$k3][$k4]) {
                $this->data[$k1][$k2][$k3][$k4] = clone($this->srsr[$sRub]);
            }

            $this->data[$k1][$k2][$k3][$k4][] = $isd;
        }
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
            'dmep'                          => 'Demande de mise en paiement',
            'mep'                           => 'Mise en paiement',
        ];

        $this->data = $this->newIsd($this->getIntervenant());
        foreach ($rubriques as $k => $l) {
            $rd = $this->newIsd();
            $rd->setId($k);
            $rd->setLabel($l);

            $this->data[] = $rd;
        }

        $ordre = 0;
        foreach ($sousRubriques as $k => $l) {
            $ordre++;
            $srd = $this->newIsd();
            $srd->setId($k);
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
            $this->data['modifs-service-du'][] = $this
                ->newIsd($msd)
                ->setIcon('glyphicon glyphicon-calendar');
        }
    }



    private function makeDossier()
    {
        /* Récup des données personnelles */
        $dossier = $this->getServiceDossier()->getByIntervenant($this->getIntervenant());
        if ($dossier && $dossier->estNonHistorise()) {
            $d = $this->newIsd($dossier)->setIcon('glyphicon glyphicon-user');

            $validation = $this->getServiceDossier()->getValidation($this->getIntervenant());
            if ($validation) {
                $d[] = $this->newIsd($validation)->setIcon('glyphicon glyphicon-ok');
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

                $this->addIsdSR($s);

                /** @var VolumeHoraire $vh */
                foreach ($vhs as $vh) {
                    $sRub = $vh->getTypeVolumeHoraire()->getCode();

                    $vs = $vh->getValidation()->filter(function ($v) {
                        return $v->estNonHistorise();
                    });

                    $vhd = $this->newIsd($vh)->setIcon('glyphicon glyphicon-calendar');
                    foreach ($vs as $v) {
                        $vhd[] = $this->newIsd($v)->setIcon('glyphicon glyphicon-ok');
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

                $this->addIsdSR($ref);

                /** @var VolumeHoraireReferentiel $vh */
                foreach ($vhs as $vh) {
                    $sRub = $vh->getTypeVolumeHoraire()->getCode();

                    $vs = $vh->getValidation()->filter(function ($v) {
                        return $v->estNonHistorise();
                    });

                    $vhd = $this->newIsd($vh)->setIcon('glyphicon glyphicon-calendar');
                    foreach ($vs as $v) {
                        $vhd[] = $this->newIsd($v)->setIcon('glyphicon glyphicon-ok');
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

            $dpj = $this->newIsd($pj)->setIcon('glyphicon glyphicon-envelope');

            $children = $pj->getFichier()->filter(function ($f) {
                return $f->estNonHistorise();
            });
            foreach ($children as $f) {
                $dpj[] = $this->newIsd($f)->setIcon('glyphicon glyphicon-file');
            }

            if (($v = $pj->getValidation()) && $v->estNonHistorise()) {
                $dpj[] = $this->newIsd($v)->setIcon('glyphicon glyphicon-ok');
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
            $this->data['agrement-' . $key][] = $this->newIsd($agr)->setIcon('glyphicon glyphicon-ok-sign');
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
                $dc[] = $this->newIsd($v)->setIcon('glyphicon glyphicon-ok');
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

                $dm = $this->newIsd($mep)->setIcon('glyphicon glyphicon-euro');

                $service = $mep->getFormuleResultatService() ? $mep->getFormuleResultatService()->getService() : $mep->getFormuleResultatServiceReferentiel()->getServiceReferentiel();
                $sRub    = $mep->getPeriodePaiement() ? 'mep' : 'dmep';

                $this->addIsdSR($service, $sRub, $dm);
            }
        }
    }
}