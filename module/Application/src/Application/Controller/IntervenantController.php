<?php

namespace Application\Controller;

use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Application\Entity\Service\Recherche;
use Application\Exception\DbException;
use Application\Form\Intervenant\Traits\EditionFormAwareTrait;
use Application\Form\Intervenant\Traits\HeuresCompFormAwareTrait;
use Application\Processus\Traits\IntervenantProcessusAwareTrait;
use Application\Processus\Traits\ServiceProcessusAwareTrait;
use Application\Processus\Traits\ServiceReferentielProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\CampagneSaisieServiceAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\LocalContextAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use LogicException;
use Application\Entity\Db\Intervenant;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\TypeHeuresAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Zend\View\Model\ViewModel;

/**
 * Description of IntervenantController
 *
 */
class IntervenantController extends AbstractController
{
    use WorkflowServiceAwareTrait;
    use ContextAwareTrait;
    use IntervenantAwareTrait;
    use TypeHeuresAwareTrait;
    use HeuresCompFormAwareTrait;
    use SessionContainerTrait;
    use EditionFormAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;
    use IntervenantProcessusAwareTrait;
    use ServiceProcessusAwareTrait;
    use ServiceReferentielProcessusAwareTrait;
    use LocalContextAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use ValidationAwareTrait;



    public function indexAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($intervenant = $role->getIntervenant()) {
            $etapeCourante = $this->getServiceWorkflow()->getEtapeCourante();
            if ($this->getServiceWorkflow()->isAllowed($etapeCourante)) {
                if ($etapeCourante && $url = $etapeCourante->getUrl()) {
                    return $this->redirect()->toUrl($url);
                }
            } else {
                return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => $intervenant->getRouteParam()]);
            }
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
        $intervenants = $this->getProcessusIntervenant()->rechercher($critere, 21);

        return compact('intervenants');
    }



    public function voirAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');

        if (!$intervenant) {
            throw new \LogicException('Intervenant introuvable');
        }

        $this->addIntervenantRecent($intervenant);

        return compact('intervenant', 'role');
    }



    public function servicesAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Service::class,
            \Application\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\ServiceReferentiel::class,
            \Application\Entity\Db\VolumeHoraireReferentiel::class,
            \Application\Entity\Db\Validation::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            \Application\Entity\Db\ElementPedagogique::class,
        ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
            $vh = new ViewModel();
            $vh->setTemplate('application/intervenant/menu');

            return $vh;
        }

        $typeVolumeHoraire = $this->params()->fromRoute('type-volume-horaire-code', TypeVolumeHoraire::CODE_PREVU);
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraire);

        $campagneSaisie = $this->getServiceCampagneSaisie()->getBy($intervenant->getStatut()->getTypeIntervenant(), $typeVolumeHoraire);
        if (!$campagneSaisie->estOuverte()) {
            $role = $this->getServiceContext()->getSelectedIdentityRole();
            if ($role->getIntervenant()) {
                $this->flashMessenger()->addErrorMessage($campagneSaisie->getMessage($role));
            } else {
                $this->flashMessenger()->addWarningMessage($campagneSaisie->getMessage($role));
            }
        }

        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getSaisi();

        $vm = new ViewModel();

        /* Liste des services */
        $this->getServiceLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
        $recherche = new Recherche($typeVolumeHoraire, $etatVolumeHoraire);

        if ($intervenant->getStatut()->getPeutSaisirService() && $this->isAllowed($intervenant, Privileges::ENSEIGNEMENT_VISUALISATION)) {
            $services = $this->getProcessusService()->getServices($intervenant, $recherche);
        } else {
            $services = false;
        }

        /* Services référentiels (si nécessaire) */
        if ($intervenant->getStatut()->getPeutSaisirReferentiel() && $this->isAllowed($intervenant, Privileges::REFERENTIEL_VISUALISATION)) {
            $servicesReferentiel = $this->getProcessusServiceReferentiel()->getServices($intervenant, $recherche);
        } else {
            $servicesReferentiel = false;
        }

        /* Totaux HETD */
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $this->getEvent()->setParam('typeVolumeHoraire', $typeVolumeHoraire);
        $this->getEvent()->setParam('etatVolumeHoraire', $etatVolumeHoraire);
        $params['action'] = 'formuleTotauxHetd';
        $widget           = $this->forward()->dispatch('Application\Controller\Intervenant', $params);
        if ($widget) $vm->addChild($widget, 'formuleTotauxHetd');

        /* Clôture de saisie (si nécessaire) */
        if ($typeVolumeHoraire->isRealise() && $intervenant->getStatut()->getPeutCloturerSaisie()) {
            $cloture = $this->getServiceValidation()->getValidationClotureServices($intervenant);
        } else {
            $cloture = null;
        }

        $vm->setVariables(compact('intervenant', 'typeVolumeHoraire', 'services', 'servicesReferentiel', 'cloture', 'role'));

        return $vm;
    }



    public function cloturerAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Validation::class,
        ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $validation = $this->getServiceValidation()->getValidationClotureServices($intervenant);

        if ($this->getRequest()->isPost()) {
            if ($validation->getId()) {
                if (!$this->isAllowed($intervenant, Privileges::CLOTURE_REOUVERTURE)) {
                    throw new \Exception("Vous n'avez pas le droit de déclôturer la saisie de services réalisés d'un intervenant");
                }
                try {
                    $this->getServiceValidation()->delete($validation);
                    $this->flashMessenger()->addSuccessMessage("La saisie du service réalisé a bien été réouverte", 'success');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
                }
            } else {
                if (!$this->isAllowed($intervenant, Privileges::CLOTURE_CLOTURE)) {
                    throw new \Exception("Vous n'avez pas le droit de clôturer la saisie de services réalisés d'un intervenant");
                }
                try {
                    $this->getServiceValidation()->save($validation);
                    $this->flashMessenger()->addSuccessMessage("La saisie du service réalisé a bien été clôturée", 'success');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
                }
            }
        }

        return new MessengerViewModel;
    }



    public function ficheAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');

        return compact('intervenant', 'role');
    }



    public function saisirAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $title       = "Saisie d'un intervenant";
        $form        = $this->getFormIntervenantEdition();
        $errors      = [];

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



    public function supprimerAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant \Application\Entity\Db\Intervenant */

        if ($intervenant){
            $data = $this->getProcessusIntervenant()->getSuppressionData($intervenant);
        }else{
            $data = null;
        }


        $ids = $this->params()->fromPost('ids');

        if ($ids){
            try {
                if ($data) $data = $this->getProcessusIntervenant()->deleteRecursive($data, $ids);

                if (!$data){
                    $this->flashMessenger()->addSuccessMessage('Fiche intervenant supprimée intégralement. Vous allez être redirigé(e) vers la page de recherche des intervenants.');
                }else{
                    $this->flashMessenger()->addSuccessMessage('Données bien supprimées');
                }
            }catch(\Exception $e ){
                $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
            }
        }else{
            $this->flashMessenger()->addWarningMessage(
                'Attention : La suppression d\'une fiche entraine la suppression de toutes les données associées pour l\'année en cours'
            );
        }

        return compact('intervenant', 'data');
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
