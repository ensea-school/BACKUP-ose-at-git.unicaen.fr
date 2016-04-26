<?php

namespace Application\Controller;

use Application\Form\ServiceReferentiel\Traits\SaisieAwareTrait;
use Application\Processus\Traits\ValidationReferentielProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\LocalContextAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielAwareTrait;
use Application\Exception\DbException;
use Application\Entity\Service\Recherche;
use Application\Service\Traits\ContextAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of ServiceReferentielController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielController extends AbstractController
{
    use ContextAwareTrait;
    use LocalContextAwareTrait;
    use ServiceAwareTrait;
    use ServiceReferentielAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;
    use VolumeHoraireReferentielAwareTrait;
    use SaisieAwareTrait;
    use ValidationAwareTrait;
    use ValidationReferentielProcessusAwareTrait;



    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\ServiceReferentiel::class,
            \Application\Entity\Db\VolumeHoraireReferentiel::class,
            \Application\Entity\Db\Validation::class,
        ]);
    }



    /**
     *
     * @param Intervenant|null $intervenant
     * @param Recherche        $recherche
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getFilteredServices($intervenant, $recherche)
    {
        //              \Test\Util::sqlLog($this->getServiceService()->getEntityManager());
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $serviceReferentiel              = $this->getServiceServiceReferentiel();
        $volumeHoraireReferentielService = $this->getServiceVolumeHoraireReferentiel();

        $this->initFilters();
        $qb = $serviceReferentiel->initQuery()[0];

        $serviceReferentiel
            ->join('applicationIntervenant', $qb, 'intervenant', ['id', 'nomUsuel', 'prenom', 'sourceCode'])
            ->join($volumeHoraireReferentielService, $qb, 'volumeHoraireReferentiel', ['id', 'heures']);

        $volumeHoraireReferentielService->leftJoin('applicationEtatVolumeHoraire', $qb, 'etatVolumeHoraireReferentiel', ['id', 'code', 'libelle', 'ordre']);

        $serviceReferentiel->finderByContext($qb);
        $serviceReferentiel->finderByFilterObject($recherche, new \Zend\Stdlib\Hydrator\ClassMethods(false), $qb, null, ['typeVolumeHoraire', 'etatVolumeHoraire']);

        if ($intervenant) {
            $serviceReferentiel->finderByIntervenant($intervenant, $qb);
        }
        if (!$intervenant && $role->getStructure()) {
            $serviceReferentiel->finderByStructure($role->getStructure(), $qb);
        }

        return $qb;
    }



    public function indexAction()
    {
        $typeVolumeHoraireCode = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU');
        $totaux                = $this->params()->fromQuery('totaux', 0) == '1';
        $viewHelperParams      = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $role                  = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant           = $this->context()->intervenantFromRoute();
        $viewModel             = new \Zend\View\Model\ViewModel();

        if ($intervenant && $intervenant->estPermanent()) {
            $serviceRefProto = $this->getServiceServiceReferentiel()->newEntity()
                ->setIntervenant($intervenant)
                ->setTypeVolumeHoraire($this->getTypeVolumeHoraire());

            $canAddServiceReferentiel = $intervenant->estPermanent() && $this->isAllowed($serviceRefProto, 'create');
        } else {
            $canAddServiceReferentiel = false;
        }

        if (!$intervenant) {
            $action             = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
            $params             = $this->getEvent()->getRouteMatch()->getParams();
            $params['action']   = 'recherche';
            $rechercheViewModel = $this->forward()->dispatch('Application\Controller\Service', $params);
            $viewModel->addChild($rechercheViewModel, 'recherche');

            $recherche = $this->getServiceService()->loadRecherche();
        } else {
            $this->getServiceLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
            $action    = 'afficher'; // Affichage par défaut
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire($this->getServiceTypeVolumehoraire()->getByCode($typeVolumeHoraireCode));
            $recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());

            $params = [
                'intervenant' => $intervenant->getSourceCode(),
                'action'      => 'formule-totaux-hetd',
            ];
            $this->getEvent()->setParam('typeVolumeHoraire', $recherche->getTypeVolumeHoraire());
            $this->getEvent()->setParam('etatVolumeHoraire', $recherche->getEtatVolumeHoraire());
        }

        /* Préparation et affichage */
        if ('afficher' === $action || $totaux) {
            $qb       = $this->getFilteredServices($intervenant, $recherche);
            $services = $this->getServiceServiceReferentiel()->getList($qb);
            $this->getServiceServiceReferentiel()->setTypeVolumeHoraire($services, $recherche->getTypeVolumeHoraire());
        } else {
            $services = [];
        }

        $renderReferentiel = $intervenant && $intervenant->estPermanent();
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
        $params            = $viewHelperParams;

        $viewModel->setVariables(compact('services', 'typeVolumeHoraire', 'action', 'role', 'intervenant', 'renderReferentiel', 'canAddServiceReferentiel', 'params'));

        if ($totaux) {
            $viewModel->setTemplate('application/service-referentiel/rafraichir-totaux');
        }

        return $viewModel;
    }



    public function saisieAction()
    {
        $this->initFilters();
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Structure::class,
            \Application\Entity\Db\FonctionReferentiel::class,
        ]);
        $id                = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $service = $this->getServiceServiceReferentiel();
        //$role    = $this->getServiceContext()->getSelectedIdentityRole();
        $form = $this->getFormServiceReferentielSaisie();
        $form->get('type-volume-horaire')->setValue($typeVolumeHoraire->getId());
        $errors = [];

        $intervenant = $this->getServiceLocalContext()->getIntervenant();

        if ($id) {
            $entity = $service->get($id);
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $form->bind($entity);
            $title = "Modification de référentiel";
        } else {
            $entity = $service->newEntity();
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $entity->setIntervenant($intervenant);
            $form->bind($entity);
            $form->initFromContext();
            $title = "Ajout de référentiel";
        }

        $assertionEntity = $service->newEntity();
        $assertionEntity
            ->setTypeVolumeHoraire($typeVolumeHoraire)
            ->setIntervenant($intervenant);
        if (!$this->isAllowed($assertionEntity, 'create') || !$this->isAllowed($assertionEntity, 'update')) {
            throw new \LogicException("Cette opération n'est pas autorisée.");
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->saveToContext();
            if ($form->isValid()) {
                try {
                    $entity->setIntervenant($intervenant); // car après $form->isValid(), $entity->getIntervenant() === null
                    $entity = $service->save($entity);
                    $form->get('service')->get('id')->setValue($entity->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            } else {
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(compact('form', 'errors', 'title'));

        return $viewModel;
    }



    public function rafraichirLigneAction()
    {
        $this->initFilters();

        $params      = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $details     = 1 == (int)$this->params()->fromQuery('details', (int)$this->params()->fromPost('details', 0));
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content', 0);
        $service     = $this->context()->serviceReferentielFromRoute('id'); // remplacer id par service au besoin, à cause des routes définies en config.

        return compact('service', 'params', 'details', 'onlyContent');
    }



    public function initialisationAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getServiceServiceReferentiel()->setPrevusFromPrevus($intervenant);
        $errors = [];

        return compact('errors');
    }



    public function constatationAction()
    {
        $this->initFilters();
        $services = $this->params()->fromQuery('services');
        if ($services) {
            $services = explode(',', $services);
            foreach ($services as $sid) {
                $service = $this->getServiceServiceReferentiel()->get($sid);
                if ($this->isAllowed($service, 'update')) {
                    $this->getServiceServiceReferentiel()->setRealisesFromPrevus($service);
                }
            }
        }
    }



    public function suppressionAction()
    {
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $id      = (int)$this->params()->fromRoute('id', 0);
        $service = $this->getServiceServiceReferentiel()->get($id);
        $title   = "Suppression de référentiel";
        $form    = new \Application\Form\Supprimer('suppr');
        $form->setServiceLocator($this->getServiceLocator()->get('formElementManager'));
        $form->init();
        $form->add(new \Zend\Form\Element\Hidden('type-volume-horaire'));
        $viewModel = new \Zend\View\Model\ViewModel();

        $intervenant     = $this->getServiceLocalContext()->getIntervenant();
        $assertionEntity = $this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant);
        if (!$this->isAllowed($assertionEntity, 'delete')) {
            throw new \LogicException("Cette opération n'est pas autorisée.");
        }

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->get('type-volume-horaire')->setValue($typeVolumeHoraire->getId());

        if ($this->getRequest()->isPost()) {
            $errors = [];
            try {
                if ($typeVolumeHoraire->getCode() === \Application\Entity\Db\TypeVolumeHoraire::CODE_REALISE) {
                    // destruction des seuls volumes horaires REALISES associés, pas les PREVUS
                    foreach ($service->getVolumeHoraireReferentiel() as $vh) {
                        if ($vh->getTypeVolumeHoraire() === $typeVolumeHoraire) {
                            $this->getServiceVolumeHoraire()->delete($vh);
                        }
                    }
                } else {
                    // destruction du service même
                    $this->getServiceServiceReferentiel()->delete($service);
                }
            } catch (\Exception $e) {
                $e        = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
            $viewModel->setVariable('errors', $errors);
        }

        $viewModel->setVariables(compact('entity', 'context', 'title', 'form'));

        return $viewModel;
    }



    public function validationAction()
    {
        $this->initFilters();

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $filterStructure = $role->getStructure(); // pour filtrer les affichages à la structure concernée uniquement

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', 'PREVU'));

        $title = "Validation du référentiel";

        if ($typeVolumeHoraire->isPrevu()) {
            $title .= " prévisionnel";
        } elseif ($typeVolumeHoraire->isRealise()) {
            $title .= " réalisé";
        }

        $services    = [
            'valides'     => [],
            'non-valides' => [],
        ];

        $validations = $this->getProcessusValidationReferentiel()->lister($typeVolumeHoraire, $intervenant, $filterStructure);
        foreach( $validations as $validation ){
            $key = $validation->getId() ? 'valides' : 'non-valides';
            $vid = $this->getProcessusValidationReferentiel()->getValidationId($validation);
            $sList = $this->getProcessusValidationReferentiel()->getServices( $typeVolumeHoraire, $validation );
            $services[$key][$vid] = $sList;
        }


        /* Messages */
        if (empty($services['non-valides'])) {
            if ($role->getIntervenant()) {
                $message = sprintf(
                    "Tous votre référentiel %s a été validé.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnel" : "réalisé"
                );
            } else {
                $message = sprintf(
                    "Aucun référentiel %s n'est en attente de validation.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnel" : "réalisé"
                );
            }
            $this->flashMessenger()->addSuccessMessage($message);
        }

        return compact('title', 'typeVolumeHoraire', 'intervenant', 'validations', 'services');
    }



    public function validerAction()
    {
        $this->initFilters();

        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        /* @var $typeVolumeHoraire TypeVolumeHoraire */

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $structure = $this->getEvent()->getParam('structure');
        /* @var $structure Structure */


        $validation = $this->getProcessusValidationReferentiel()->creer($intervenant, $structure);

        if ($this->isAllowed($validation, Privileges::REFERENTIEL_VALIDATION)) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationReferentiel()->enregistrer($typeVolumeHoraire, $validation);

                    $this->flashMessenger()->addSuccessMessage(
                        "Validation effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
                }
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de valider ce référentiel.');
        }

        return new MessengerViewModel();
    }



    public function devaliderAction()
    {
        $this->initFilters();

        $validation = $this->getEvent()->getParam('validation');
        /* @var $structure Structure */

        if ($this->isAllowed($validation, Privileges::REFERENTIEL_DEVALIDATION)) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationReferentiel()->supprimer($validation);

                    $this->flashMessenger()->addSuccessMessage(
                        "Dévalidation effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
                }
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de dévalider ce référentiel.');
        }

        return new MessengerViewModel();
    }



    /**
     * @var TypeVolumeHoraire
     */
    private $typeVolumeHoraire;



    /**
     * @return TypeVolumeHoraire
     */
    private function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $typeVolumeHoraireCode   = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU');
            $this->typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getByCode($typeVolumeHoraireCode);
        }

        return $this->typeVolumeHoraire;
    }

}
