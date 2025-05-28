<?php

namespace Referentiel\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Laminas\View\Model\ViewModel;
use Lieu\Entity\Db\Structure;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Referentiel\Form\SaisieAwareTrait;
use Referentiel\Processus\ServiceReferentielProcessusAwareTrait;
use Referentiel\Processus\ValidationReferentielProcessusAwareTrait;
use Referentiel\Service\ServiceReferentielServiceAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\RechercheServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of ServiceReferentielController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielController extends AbstractController
{
    use ContextServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use RechercheServiceAwareTrait;
    use ServiceReferentielServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use SaisieAwareTrait;
    use ServiceReferentielProcessusAwareTrait;
    use ValidationReferentielProcessusAwareTrait;
    use WorkflowServiceAwareTrait;
    use PlafondProcessusAwareTrait;


    public function prevuAction ()
    {
        $prevu = $this->getServiceTypeVolumeHoraire()->getPrevu();

        return $this->indexAction($prevu);
    }



    public function indexAction (?TypeVolumeHoraire $typeVolumeHoraire = null)
    {
        $this->initFilters();

        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $this->getServiceLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
        $recherche = new Recherche($typeVolumeHoraire, $this->getServiceEtatVolumeHoraire()->getSaisi());
        $recherche->setIntervenant($intervenant);

        $referentiels = $this->getProcessusServiceReferentiel()->getReferentiels($recherche);

        $viewModel = new ViewModel();
        $viewModel->setVariables(compact('typeVolumeHoraire', 'intervenant', 'referentiels'));
        $viewModel->setTemplate('referentiel/index');

        return $viewModel;
    }



    protected function initFilters ()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Referentiel\Entity\Db\ServiceReferentiel::class,
            \Referentiel\Entity\Db\VolumeHoraireReferentiel::class,
            \Workflow\Entity\Db\Validation::class,
        ]);
    }



    public function realiseAction ()
    {
        $realise = $this->getServiceTypeVolumeHoraire()->getRealise();

        return $this->indexAction($realise);
    }



    public function saisieAction ()
    {
        $this->initFilters();
        $this->em()->getFilters()->enable('annee')->init([
            FonctionReferentiel::class,
        ]);
        $this->em()->getFilters()->enable('historique')->init([
            \Lieu\Entity\Db\Structure::class,
        ]);
        $id = (int)$this->params()->fromRoute('id');

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

        //Si volume referentiel est validé alors on passe les motifs de non paiement en lecture seule
        $disabled               = false;
        $listeVolumeReferentiel = $entity->getVolumeHoraireReferentiel();
        foreach ($listeVolumeReferentiel as $vhr) {
            /**
             * @var $vhr VolumeHoraireReferentiel
             */

            if ($vhr->isValide()) {
                $disabled = true;
            }
        }
        if ($disabled) {
            $form->get('service')->get('motif-non-paiement')->setAttribute('disabled', 'disabled');
            $form->get('service')->get('motif-non-paiement')->setAttribute('title', 'Vous ne pouvez pas mettre de motif de non paiement sur un volume horaire déjà validé');
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
                //$this->updateTableauxBord($intervenant, $typeVolumeHoraire);
                if (!$this->getProcessusPlafond()->endTransaction($entity, $typeVolumeHoraire, $hFin < $hDeb)) {
                    // rien ici
                }
                $this->updateTableauxBord($intervenant, $typeVolumeHoraire);
            } else {
                $this->flashMessenger()->addErrorMessage('La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.');
            }
        }

        $vm = new ViewModel();
        $vm->setVariables(compact('form', 'title'));
        $vm->setTemplate('referentiel/saisie');

        return $vm;
    }



    private function updateTableauxBord (Intervenant $intervenant, ?TypeVolumeHoraire $typeVolumeHoraire=null, bool $validation = false)
    {
        $tbls = [TblProvider::FORMULE, TblProvider::VALIDATION_REFERENTIEL, TblProvider::REFERENTIEL];
        if ($typeVolumeHoraire && $typeVolumeHoraire->isRealise()){
            if ($validation){
                $tbls[] = TblProvider::PAIEMENT;
            }
        }else{
            if ($validation) {
                $tbls[] = TblProvider::CONTRAT;
            }else{
                $tbls[] = TblProvider::PIECE_JOINTE_FOURNIE;
            }
        }

        $this->getServiceWorkflow()->calculerTableauxBord($tbls, $intervenant);
    }



    public function rafraichirLigneAction ()
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

        $vm = new ViewModel();
        $vm->setVariables(compact('service', 'params', 'details', 'onlyContent'));
        $vm->setTemplate('referentiel/rafraichir-ligne');

        return $vm;
    }



    public function initialisationAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getProcessusPlafond()->beginTransaction();
        $this->getServiceServiceReferentiel()->setPrevusFromPrevus($intervenant);
        $this->getProcessusPlafond()->endTransaction($intervenant, $this->getServiceTypeVolumeHoraire()->getPrevu());
        $this->updateTableauxBord($intervenant);
        $errors = [];

        $vm = new ViewModel();
        $vm->setTemplate('referentiel/initialisation');
        $vm->setVariables(compact('errors'));
        return $vm;
    }



    public function constatationAction ()
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
                    $this->updateTableauxBord($service->getIntervenant(), $typeVolumeHoraire);
                    $this->getProcessusPlafond()->endTransaction($service, $typeVolumeHoraire);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('enseignement/constatation');

        return $vm;
    }



    public function suppressionAction ()
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
                $this->updateTableauxBord($service->getIntervenant(), $typeVolumeHoraire);
                $this->flashMessenger()->addSuccessMessage('Suppression effectuée');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
            $this->getProcessusPlafond()->endTransaction($service, $typeVolumeHoraire, true);
        }

        return new MessengerViewModel;
    }



    public function validationPrevuAction ()
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();

        return $this->validationAction($typeVolumeHoraire);
    }



    private function validationAction (TypeVolumeHoraire $typeVolumeHoraire)
    {
        $this->initFilters();

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $filterStructure = null;//$role->getStructure(); // pour filtrer les affichages à la structure concernée uniquement

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

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

        $vm = new ViewModel();
        $vm->setVariables(compact('title', 'typeVolumeHoraire', 'intervenant', 'validations', 'services'));
        $vm->setTemplate('referentiel/validation');

        return $vm;
    }



    public function validationRealiseAction ()
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();

        return $this->validationAction($typeVolumeHoraire);
    }



    public function validerAction ()
    {
        $this->initFilters();

        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        /* @var TypeVolumeHoraire $typeVolumeHoraire */

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var Intervenant $intervenant */

        $structure = $this->getEvent()->getParam('structure');
        /* @var Structure $structure */


        $validation = $this->getProcessusValidationReferentiel()->creer($intervenant, $structure);

        if ($this->isAllowed($validation, $typeVolumeHoraire->getPrivilegeReferentielValidation())) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationReferentiel()->enregistrer($typeVolumeHoraire, $validation);
                    $this->updateTableauxBord($intervenant, $typeVolumeHoraire, true);
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



    public function devaliderAction ()
    {
        $this->initFilters();

        $validation = $this->getEvent()->getParam('validation');
        /* @var $structure Structure */

        if ($this->isAllowed($validation, Privileges::REFERENTIEL_DEVALIDATION)) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationReferentiel()->supprimer($validation);
                    $this->updateTableauxBord($validation->getIntervenant(), null,true);
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

}
