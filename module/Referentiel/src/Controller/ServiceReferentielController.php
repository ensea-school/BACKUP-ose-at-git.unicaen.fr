<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Referentiel\Entity\Db\ServiceReferentiel;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Form\ServiceReferentiel\Traits\SaisieAwareTrait;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Application\Processus\Traits\ServiceReferentielProcessusAwareTrait;
use Application\Processus\Traits\ValidationReferentielProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\ServiceReferentielServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielServiceAwareTrait;
use Service\Entity\Recherche;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of ServiceReferentielController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielController extends AbstractController
{
    use ContextServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use ServiceReferentielServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use VolumeHoraireReferentielServiceAwareTrait;
    use SaisieAwareTrait;
    use ValidationServiceAwareTrait;
    use ServiceReferentielProcessusAwareTrait;
    use ValidationReferentielProcessusAwareTrait;
    use WorkflowServiceAwareTrait;
    use PlafondProcessusAwareTrait;


    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Referentiel\Entity\Db\ServiceReferentiel::class,
            \Referentiel\Entity\Db\VolumeHoraireReferentiel::class,
            \Application\Entity\Db\Validation::class,
        ]);
    }



    public function indexAction()
    {
        $typeVolumeHoraireCode = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU');
        $viewHelperParams      = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $role                  = $this->getServiceContext()->getSelectedIdentityRole();
        /** @var Intervenant $intervenant */
        $intervenant = $this->params()->fromRoute('intervenant');
        $viewModel   = new \Laminas\View\Model\ViewModel();

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
            $this->getEvent()->setParam('typeVolumeHoraire', $recherche->getTypeVolumeHoraire());
            $this->getEvent()->setParam('etatVolumeHoraire', $recherche->getEtatVolumeHoraire());
        }

        /* Préparation et affichage */
        if ('afficher' === $action) {
            $services = $this->getProcessusServiceReferentiel()->getServices($intervenant, $recherche);
        } else {
            $services = [];
        }

        $renderReferentiel = $intervenant && $intervenant->getStatut()->estPermanent();
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
        $params            = $viewHelperParams;

        $viewModel->setVariables(compact('services', 'typeVolumeHoraire', 'action', 'role', 'intervenant', 'renderReferentiel', 'params'));

        return $viewModel;
    }



    public function saisieAction()
    {
        $this->initFilters();
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Structure::class,
        ]);
        $id                = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $service = $this->getServiceServiceReferentiel();
        $role    = $this->getServiceContext()->getSelectedIdentityRole();
        $form    = $this->getFormServiceReferentielSaisie();
        $form->get('type-volume-horaire')->setValue($typeVolumeHoraire->getId());

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
        if ($assertionEntity->getStructure() == null) {
            $assertionEntity->setStructure($role->getStructure());
        }
        if (!$this->isAllowed($assertionEntity, $typeVolumeHoraire->getPrivilegeReferentielEdition())) {
            throw new \LogicException("Cette opération n'est pas autorisée.");
        }
        $hDeb    = $entity->getVolumeHoraireReferentielListe()->getHeures();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->saveToContext();
            if ($form->isValid()) {
                $this->getProcessusPlafond()->beginTransaction();
                try {
                    $entity->setIntervenant($intervenant); // car après $form->isValid(), $entity->getIntervenant() === null
                    $entity = $service->save($entity);
                    $form->get('service')->get('id')->setValue($entity->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
                $hFin = $entity->getVolumeHoraireReferentielListe()->getHeures();
                $this->updateTableauxBord($intervenant);
                if (!$this->getProcessusPlafond()->endTransaction($intervenant, $typeVolumeHoraire, $hFin < $hDeb)) {
                    $this->updateTableauxBord($intervenant);
                }
            } else {
                $this->flashMessenger()->addErrorMessage('La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.');
            }
        }

        return compact('form', 'title');
    }



    public function rafraichirLigneAction()
    {
        $this->initFilters();

        $params      = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $details     = 1 == (int)$this->params()->fromQuery('details', (int)$this->params()->fromPost('details', 0));
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content', 0);
        $service     = $this->getEvent()->getParam('serviceReferentiel');
        /* @var $service ServiceReferentiel */

        if (isset($params['type-volume-horaire'])) {
            $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->get((int)$params['type-volume-horaire']);
            $service->setTypeVolumeHoraire($typeVolumeHoraire);
        }

        return compact('service', 'params', 'details', 'onlyContent');
    }



    public function initialisationAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getProcessusPlafond()->beginTransaction();
        $this->getServiceServiceReferentiel()->setPrevusFromPrevus($intervenant);
        $this->getProcessusPlafond()->endTransaction($intervenant, $this->getServiceTypeVolumeHoraire()->getPrevu());
        $this->updateTableauxBord($intervenant);
        $errors = [];

        return compact('errors');
    }



    public function constatationAction()
    {
        $this->initFilters();
        $services = $this->params()->fromQuery('services');
        if ($services) {
            $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();

            $services = explode(',', $services);
            foreach ($services as $sid) {
                $service = $this->getServiceServiceReferentiel()->get($sid);
                $service->setTypeVolumeHoraire($typeVolumeHoraire);
                if ($this->isAllowed($service, Privileges::REFERENTIEL_REALISE_EDITION)) {
                    $this->getProcessusPlafond()->beginTransaction();
                    $this->getServiceServiceReferentiel()->setRealisesFromPrevus($service);
                    $this->updateTableauxBord($service->getIntervenant());
                    $this->getProcessusPlafond()->endTransaction($service->getIntervenant(), $typeVolumeHoraire);
                }
            }
        }

        return new MessengerViewModel;
    }



    public function suppressionAction()
    {
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $id      = (int)$this->params()->fromRoute('id', null);
        $service = $this->getServiceServiceReferentiel()->get($id);
        /* @var $service ServiceReferentiel */

        if (!$service) {
            throw new \LogicException('Le service référentiel n\'existe pas');
        }
        $service->setTypeVolumeHoraire($typeVolumeHoraire);
        if (!$this->isAllowed($service, $typeVolumeHoraire->getPrivilegeReferentielEdition())) {
            throw new \LogicException("Cette opération n'est pas autorisée.");
        }
        if ($this->getRequest()->isPost()) {
            $this->getProcessusPlafond()->beginTransaction();
            try {
                $this->getServiceServiceReferentiel()->delete($service);
                $this->updateTableauxBord($service->getIntervenant());
                $this->flashMessenger()->addSuccessMessage('Suppression effectuée');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
            $this->getProcessusPlafond()->endTransaction($service->getIntervenant(), $typeVolumeHoraire, true);
        }

        return new MessengerViewModel;
    }



    public function validationAction()
    {
        $this->initFilters();

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $filterStructure = null;//$role->getStructure(); // pour filtrer les affichages à la structure concernée uniquement

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', 'PREVU'));

        $title = "Validation du référentiel";

        if ($typeVolumeHoraire->isPrevu()) {
            $title .= " prévisionnel";
        } elseif ($typeVolumeHoraire->isRealise()) {
            $title .= " réalisé";
        }

        $services = [
            'valides'     => [],
            'non-valides' => [],
        ];

        $validations = $this->getProcessusValidationReferentiel()->lister($typeVolumeHoraire, $intervenant, $filterStructure);
        foreach ($validations as $validation) {
            $key                  = $validation->getId() ? 'valides' : 'non-valides';
            $vid                  = $this->getProcessusValidationReferentiel()->getValidationId($validation);
            $sList                = $this->getProcessusValidationReferentiel()->getServices($typeVolumeHoraire, $validation);
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

        if ($this->isAllowed($validation, $typeVolumeHoraire->getPrivilegeReferentielValidation())) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationReferentiel()->enregistrer($typeVolumeHoraire, $validation);
                    $this->updateTableauxBord($intervenant, true);
                    $this->flashMessenger()->addSuccessMessage(
                        "Validation effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
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
                    $this->updateTableauxBord($validation->getIntervenant(), true);
                    $this->flashMessenger()->addSuccessMessage(
                        "Dévalidation effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de dévalider ce référentiel.');
        }

        return new MessengerViewModel();
    }



    private function updateTableauxBord(Intervenant $intervenant, $validation = false)
    {
        $this->getServiceWorkflow()->calculerTableauxBord(['formule', 'validation_referentiel', 'referentiel'], $intervenant);
        if (!$validation) {
            $this->getServiceWorkflow()->calculerTableauxBord(['referentiel', 'piece_jointe_fournie'], $intervenant);
        }
    }

}
