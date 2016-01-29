<?php

namespace Application\Controller;

use Application\Exception\DbException;
use Application\Form\Intervenant\Traits\EditionFormAwareTrait;
use Application\Form\Intervenant\Traits\HeuresCompFormAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use LogicException;
use Application\Entity\Db\Intervenant;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\TypeHeuresAwareTrait;

/**
 * Description of IntervenantController
 *
 */
class IntervenantController extends AbstractController implements WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait;
    use ContextAwareTrait;
    use IntervenantAwareTrait;
    use TypeHeuresAwareTrait;
    use HeuresCompFormAwareTrait;
    use SessionContainerTrait;
    use EditionFormAwareTrait;





    public function indexAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($intervenant = $role->getIntervenant()) {
            // redirection selon le workflow
            $wf  = $this->getWorkflowIntervenant()->setIntervenant($intervenant);
            $url = $wf->getCurrentStepUrl();
            if (!$url) {
                $url = $wf->getStepUrl($wf->getLastStep());
            }

            return $this->redirect()->toUrl($url);
        }

        return $this->redirect()->toRoute('intervenant/rechercher');
    }



    public function rechercherAction()
    {
        $recents = $this->getIntervenantsRecents();
        return compact('recents');
    }



    public function rechercheAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Intervenant::class,
        ]);

        $critere      = $this->params()->fromPost('critere');
        $intervenants = $this->getServiceIntervenant()->recherche($critere, 21);

        return compact('intervenants');
    }



    public function voirAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');

        if (! $intervenant){
            throw new \LogicException('Intervenant introuvable');
        }

        $this->addIntervenantRecent($intervenant);

        return compact('intervenant', 'role');
    }



    public function ficheAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');

        return compact('intervenant', 'role');
    }



    public function apercevoirAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $title       = "Aperçu d'un intervenant";

        return compact('intervenant', 'title');
    }



    public function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
        ]);

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $title       = "Saisie d'un intervenant";
        $form        = $this->getFormIntervenantEdition();
        $errors = [];

        if ($intervenant) {
            $form->bind($intervenant);
        } else {
            $intervenant = $this->getServiceIntervenant()->newEntity();
            $form->setObject($intervenant);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceIntervenant()->save($intervenant);
                    $form->get('id')->setValue($intervenant->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('intervenant', 'form', 'errors', 'title');
    }



    public function voirHeuresCompAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Service::class,
            \Application\Entity\Db\VolumeHoraire::class,
        ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant \Application\Entity\Db\Intervenant */
        $form = $this->getFormIntervenantHeuresComp();

        $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromQuery('type-volume-horaire', $form->get('type-volume-horaire')->getValue());
        /* @var $typeVolumeHoraire \Application\Entity\Db\TypeVolumeHoraire */
        if (!isset($typeVolumeHoraire)) {
            throw new LogicException('Type de volume horaire erroné');
        }

        $etatVolumeHoraire = $this->context()->etatVolumeHoraireFromQuery('etat-volume-horaire', $form->get('etat-volume-horaire')->getValue());
        /* @var $etatVolumeHoraire \Application\Entity\Db\EtatVolumeHoraire */
        if (!isset($etatVolumeHoraire)) {
            throw new LogicException('Etat de volume horaire erroné');
        }

        $typesHeures = $this->getServiceTypeHeures()->getList();

        $form->setData([
            'type-volume-horaire' => $typeVolumeHoraire->getId(),
            'etat-volume-horaire' => $etatVolumeHoraire->getId(),
        ]);

        $data = [
            'structure-affectation'         => $intervenant->getStructure(),
            'heures-service-statutaire'     => $intervenant->getStatut()->getServiceStatutaire(),
            'heures-modification-service'   => $intervenant->getFormuleIntervenant()->getUniqueFormuleServiceModifie()->getHeures(),
            'heures-decharge'               => $intervenant->getFormuleIntervenant()->getUniqueFormuleServiceModifie()->getHeuresDecharge(),
            'services'                      => [],
            'referentiel'                   => [],
            'types-intervention'            => [],
            'has-ponderation-service-compl' => false,
            'th-taux'                       => [],
            'th-service'                    => [],
            'th-compl'                      => [],
        ];

        $referentiels = $intervenant->getFormuleIntervenant()->getFormuleServiceReferentiel();
        foreach ($referentiels as $referentiel) {
            /* @var $referentiel \Application\Entity\Db\FormuleServiceReferentiel */

            if (!isset($data['referentiel'][$referentiel->getStructure()->getId()])) {
                $data['referentiel'][$referentiel->getStructure()->getId()] = [
                    'structure'  => $referentiel->getStructure(),
                    'heures'     => 0,
                    'hetd'       => 0,
                    'hetd-compl' => 0,
                ];
            }
            $data['referentiel'][$referentiel->getStructure()->getId()]['heures'] += $referentiel->getHeures($typeVolumeHoraire, $etatVolumeHoraire);
            $frr = $referentiel->getServiceReferentiel()->getUniqueFormuleResultatServiceReferentiel($typeVolumeHoraire, $etatVolumeHoraire);
            $data['referentiel'][$referentiel->getStructure()->getId()]['hetd'] += $frr ? $frr->getHeuresServiceReferentiel() : 0;
            $data['referentiel'][$referentiel->getStructure()->getId()]['hetd-compl'] += $frr ? $frr->getHeuresComplReferentiel() : 0;
        }

        $services = $intervenant->getFormuleIntervenant()->getFormuleService();
        foreach ($services as $service) {
            $dsId = $service->getId();
            $ds   = [];

            /* @var $service \Application\Entity\Db\FormuleService */
            $typesIntervention = [];
            $totalHeures       = 0;

            $fvhs = $service->getFormuleVolumeHoraire($typeVolumeHoraire, $etatVolumeHoraire);
            foreach ($fvhs as $fvh) {
                /* @var $fvh \Application\Entity\Db\FormuleVolumeHoraire */
                $totalHeures += $fvh->getHeures();
                if (!isset($typesIntervention[$fvh->getTypeIntervention()->getId()])) {
                    $typesIntervention[$fvh->getTypeIntervention()->getId()] = [
                        'type-intervention' => $fvh->getTypeIntervention(),
                        'heures'            => 0,
                        'hetd'              => 0,
                    ];
                }
                $typesIntervention[$fvh->getTypeIntervention()->getId()]['heures'] += $fvh->getHeures();
                $hetd = $fvh->getVolumeHoraire()->getFormuleResultatVolumeHoraire()->first()->getTotal();
                $typesIntervention[$fvh->getTypeIntervention()->getId()]['hetd'] += $hetd;
            }

            if ($totalHeures > 0) {
                $frs = $service->getService()->getUniqueFormuleResultatService($typeVolumeHoraire, $etatVolumeHoraire);
                if (1.0 !== $service->getPonderationServiceCompl()) {
                    $data['has-ponderation-service-compl'] = true;
                }
                $ds = [
                    'element-etablissement'     => $service->getService()->getElementPedagogique() ? $service->getService()->getElementPedagogique() : $service->getService()->getEtablissement(),
                    'taux'                      => [],
                    'structure'                 => $service->getService()->getElementPedagogique() ? $service->getService()->getElementPedagogique()->getStructure() : $service->getService()->getIntervenant()->getStructure(),
                    'ponderation-service-compl' => $service->getPonderationServiceCompl(),
                    'heures'                    => [],
                    'hetd'                      => [
                        'total' => 0,
                    ],
                ];

                foreach ($typesHeures as $typeHeures) {
                    /* @var $typeHeures \Application\Entity\Db\TypeHeures */
                    // taux
                    try {
                        $h = $service->getTaux($typeHeures);
                    } catch (\Exception $ex) {
                        $h = 0.0;
                    }
                    if ($h > 0) {
                        $ds['taux'][$typeHeures->getId()]      = $h;
                        $data['th-taux'][$typeHeures->getId()] = $typeHeures;
                    }

                    // HETD service
                    try {
                        $h = $frs->getHeuresService($typeHeures);
                    } catch (\Exception $ex) {
                        $h = 0.0;
                    }
                    if ($h > 0) {
                        $ds['hetd']['service'][$typeHeures->getId()] = $h;
                        $data['th-service'][$typeHeures->getId()]    = $typeHeures;
                    }

                    // HETD compl
                    try {
                        $h = $frs->getHeuresCompl($typeHeures);
                    } catch (\Exception $ex) {
                        $h = 0.0;
                    }
                    if ($h > 0) {
                        $ds['hetd']['compl'][$typeHeures->getId()] = $h;
                        $data['th-compl'][$typeHeures->getId()]    = $typeHeures;
                    }
                }

                foreach ($typesIntervention as $ti) {
                    if ($ti['heures'] > 0) {
                        $data['types-intervention'][$ti['type-intervention']->getId()] = $ti['type-intervention'];
                        $ds['heures'][$ti['type-intervention']->getId()]               = $ti['heures'];
                        $ds['hetd'][$ti['type-intervention']->getId()]                 = $ti['hetd'];
                    }
                }
                $data['services'][$dsId] = $ds;
            }
        }

        usort($data['types-intervention'], function ($ti1, $ti2) {
            return $ti1->getOrdre() > $ti2->getOrdre();
        });
        usort($data['th-taux'], function ($ti1, $ti2) {
            return $ti1->getOrdre() > $ti2->getOrdre();
        });
        usort($data['th-service'], function ($ti1, $ti2) {
            return $ti1->getOrdre() > $ti2->getOrdre();
        });
        usort($data['th-compl'], function ($ti1, $ti2) {
            return $ti1->getOrdre() > $ti2->getOrdre();
        });

        return compact('form', 'intervenant', 'typeVolumeHoraire', 'etatVolumeHoraire', 'data');
    }



    public function formuleTotauxHetdAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        $etatVolumeHoraire = $this->getEvent()->getParam('etatVolumeHoraire');
        $formuleResultat   = $intervenant->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        return compact('formuleResultat');
    }



    public function feuilleDeRouteAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        if ($intervenant->estPermanent()) {
            throw new \LogicException("Pas encore implémenté pour un permanent");
        }

        $title = sprintf("Feuille de route <small>%s</small>", $intervenant);

        $wf = $this->getWorkflowIntervenant()->setIntervenant($intervenant);
        /* @var $wf \Application\Service\Workflow\WorkflowIntervenant */
        $wf->init();

        if ($wf->getCurrentStep()) {
//            var_dump($wf->getStepUrl($wf->getCurrentStep()));
        }

        return compact('intervenant', 'title', 'wf', 'role');
    }



    /**
     *
     * @return array
     */
    protected function getIntervenantsRecents()
    {
        $container = $this->getSessionContainer();

        if (isset($container->recents)) {
            return $container->recents;
        } else {
            return [];
        }
    }



    /**
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return \Application\Controller\IntervenantController
     */
    protected function addIntervenantRecent(Intervenant $intervenant)
    {
        $container = $this->getSessionContainer();
        if (!isset($container->recents)) {
            $container->recents = [];
        }

        if (count($container->recents) > 4 && !isset($container->recents[$intervenant->getSourceCode()])) {
            $prem = (int)date('U');
            foreach ($container->recents as $i) {
                $horo = $i['__horo_ajout__'];
                if ($horo) {
                    if ($prem >= $horo) $prem = $horo;
                }
            }
            foreach ($container->recents as $index => $i) {
                $horo = $i['__horo_ajout__'];
                if ($horo == $prem) {
                    unset($container->recents[$index]);
                }
            }
        }

        $container->recents[$intervenant->getSourceCode()] = [
            'civilite'         => $intervenant->getCivilite()->getLibelleLong(),
            'nom'              => $intervenant->getNomUsuel(),
            'prenom'           => $intervenant->getPrenom(),
            'date-naissance'   => $intervenant->getDateNaissance(),
            'structure'        => $intervenant->getStructure()->getLibelleCourt(),
            'numero-personnel' => $intervenant->getSourceCode(),
            '__horo_ajout__'   => (int)date('U'),
        ];

        uasort($container->recents, function ($a, $b) {
            return $a['nom'] . ' ' . $a['prenom'] > $b['nom'] . ' ' . $b['prenom'];
        });

        return $this;
    }
}
