<?php

namespace Enseignement\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Enseignement\Form\EnseignementSaisieFormAwareTrait;
use Enseignement\Processus\EnseignementProcessusAwareTrait;
use Enseignement\Processus\ValidationEnseignementProcessusAwareTrait;
use Enseignement\Service\ServiceServiceAwareTrait;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use EtatSortie\Service\EtatSortieServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Lieu\Entity\Db\Etablissement;
use Lieu\Entity\Db\Structure;
use Lieu\Service\EtablissementServiceAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use Service\Form\RechercheFormAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Workflow\Entity\Db\Validation;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of EnseignementController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EnseignementController extends AbstractController
{
    use EnseignementProcessusAwareTrait;
    use ContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use StructureServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use EnseignementSaisieFormAwareTrait;
    use RechercheFormAwareTrait;
    use ValidationEnseignementProcessusAwareTrait;
    use RegleStructureValidationServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use PlafondProcessusAwareTrait;
    use EtatSortieServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use EtablissementServiceAwareTrait;

    public function prevuAction ()
    {
        $prevu = $this->getServiceTypeVolumeHoraire()->getPrevu();

        return $this->indexAction($prevu);
    }



    public function indexAction (?TypeVolumeHoraire $typeVolumeHoraire = null)
    {
        $this->initFilters();
        $this->em()->getFilters()->enable('historique')->init([
            \OffreFormation\Entity\Db\CheminPedagogique::class,
        ]);

        /* @var $intervenant Intervenant */

        $intervenant       = $this->getEvent()->getParam('intervenant');
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getSaisi();

        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $vm = new ViewModel();
        $vm->setTemplate('enseignement/index');

        /* Liste des services */
        $this->getServiceLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
        $recherche = new Recherche($typeVolumeHoraire, $etatVolumeHoraire);
        $recherche->setIntervenant($intervenant);

        $enseignements = $this->getProcessusEnseignement()->getEnseignements($recherche);

        $vm->setVariables(compact('intervenant', 'typeVolumeHoraire', 'enseignements'));

        return $vm;
    }



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters ()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Enseignement\Entity\Db\Service::class,
            \Enseignement\Entity\Db\VolumeHoraire::class,
            \Workflow\Entity\Db\Validation::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            ElementPedagogique::class,
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

        $intervenantId = (int)$this->params()->fromQuery('intervenant', 0);
        if (!$intervenantId) {
            $service = $this->params()->fromPost('service');
            if (isset($service['intervenant-id'])) {
                $intervenantId = (int)$service['intervenant-id'];
            }
        }
        $intervenant = $intervenantId ? $this->getServiceIntervenant()->get($intervenantId) : null;

        $typeVolumeHoraireCode = $this->params()->fromRoute('type-volume-horaire-code', TypeVolumeHoraire::CODE_PREVU);
        $typeVolumeHoraire     = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);


        $serviceId = (int)$this->params()->fromRoute('service', 0);
        if ($serviceId) {
            $service = $this->getServiceService()->get($serviceId);
        } else {
            $service = $this->getServiceService()->newEntity();
            $service->setIntervenant($intervenant);
        }
        $service->setTypeVolumeHoraire($typeVolumeHoraire);


        $form = $this->getFormServiceEnseignementSaisie();
        $form->setTypeVolumeHoraire($typeVolumeHoraire);
        $form->setIntervenant($intervenant);
        $form->initPeriodes();
        $form->bind($service);

        if ($service->getId()) {
            $title = "Modification d'enseignement";
        } else {
            $form->initFromContext();
            $title = "Ajout d'enseignement";
        }

        $saved = null;

        $form->get('service')->setIntervenant($intervenant);
        $form->get('service')->removeUnusedElements();
        $hDeb    = $service->getVolumeHoraireListe()->getHeures();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if (!$this->isAllowed($service, $typeVolumeHoraire->getPrivilegeEnseignementEdition())) {
                    $this->flashMessenger()->addErrorMessage("Vous n'êtes pas autorisé à créer ou modifier ce service.");
                } else {
                    $form->saveToContext();
                    $this->getProcessusPlafond()->beginTransaction();
                    try {
                        $this->em()->getConnection()->setAutoCommit(true);
                        $service = $this->getServiceService()->save($service);
                        $this->em()->getConnection()->setAutoCommit(false);
                        $saved   = $service;
                        $form->get('service')->get('id')->setValue($service->getId()); // transmet le nouvel ID
                        $hFin = $service->getVolumeHoraireListe()->getHeures();
                        $this->updateTableauxBord($service->getIntervenant());
                        if (!$this->getProcessusPlafond()->endTransaction($service, $typeVolumeHoraire, $hFin < $hDeb)) {
                            $this->updateTableauxBord($service->getIntervenant());
                        }
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($this->translate($e));
                        $this->em()->rollback();
                    }
                }
            } else {
                $this->flashMessenger()->addErrorMessage('La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.');
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('enseignement/saisie');
        $vm->setVariables(compact('form', 'title', 'saved'));

        return $vm;
    }



    private function updateTableauxBord (Intervenant $intervenant, $validation = false)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'formule',
            'validation_enseignement',
            'contrat',
            'service',
        ], $intervenant);

        if (!$validation) {
            $this->getServiceWorkflow()->calculerTableauxBord(['piece_jointe_demande', 'piece_jointe_fournie'], $intervenant);
        }
    }



    public function rafraichirLigneAction ()
    {
        $this->initFilters();

        $params      = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $details     = 1 == (int)$this->params()->fromQuery('details', (int)$this->params()->fromPost('details', 0));
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content', 0);
        $service     = $this->getEvent()->getParam('service');

        if (!$service) {
            // dans le cas où l'ID de service a été existant dans une transaction
            // annulée pour cause de plafond bloquant dépassé
            return new JsonModel();
        }

        $service->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->get($params['type-volume-horaire']));

        $vm = new ViewModel();
        $vm->setTemplate('enseignement/rafraichir-ligne');
        $vm->setVariables(compact('service', 'params', 'details', 'onlyContent'));

        return $vm;
    }



    public function saisieFormRefreshVhAction ()
    {
        $this->initFilters();

        $serviceId         = (int)$this->params()->fromRoute('service');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $service = $this->getServiceService();
        $form    = $this->getFormServiceEnseignementSaisie();
        $form->setTypeVolumeHoraire($typeVolumeHoraire);
        $element = $this->context()->elementPedagogiqueFromPost('element');
        if (!$element instanceof \OffreFormation\Entity\Db\ElementPedagogique) {
            $element = $this->getServiceElementPedagogique()->get($element);
        }
        $etablissement = $this->context()->etablissementFromPost();
        if (!$etablissement instanceof Etablissement) {
            $etablissement = $this->getServiceEtablissement()->get($etablissement);
        }

        if ($serviceId) {
            /* @var $entity Service */
            $entity = $service->get($serviceId);
        } else {
            $entity = $service->newEntity();
        }
        $entity->setTypeVolumeHoraire($typeVolumeHoraire);
        $entity->setEtablissement($etablissement);
        $entity->setElementPedagogique($element);
        //Lorsque le service n'existe pas encore on est obligé de récupérer l'intervenant via le localContext...
        $intervenant = $this->getServiceLocalContext()->getIntervenant();

        $form = $this->getFormServiceEnseignementSaisie();
        $form->setIntervenant($intervenant);
        $form->setTypeVolumeHoraire($typeVolumeHoraire);
        $form->initPeriodes();
        $form->bind($entity);

        if (!$serviceId) $form->initFromContext();

        $vm = new ViewModel();
        $vm->setTemplate('enseignement/saisie-form-refresh-vh');
        $vm->setVariables(compact('form'));

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
        $service = $this->getEvent()->getParam('service');
        /* @var $service Service */

        if (!$service) {
            throw new \LogicException('Le service n\'existe pas');
        }
        $service->setTypeVolumeHoraire($typeVolumeHoraire);
        $privilege = null;
        if ($typeVolumeHoraire->isPrevu()) $privilege = Privileges::ENSEIGNEMENT_PREVU_EDITION;
        if ($typeVolumeHoraire->isRealise()) $privilege = Privileges::ENSEIGNEMENT_REALISE_EDITION;
        if ((!$privilege) || !$this->isAllowed($service, $privilege)) {
            throw new \LogicException("Cette opération n'est pas autorisée.");
        }

        if ($this->getRequest()->isPost()) {
            $this->getProcessusPlafond()->beginTransaction();
            try {
                $this->getServiceService()->delete($service);
                $this->updateTableauxBord($service->getIntervenant());
                $this->flashMessenger()->addSuccessMessage('Suppression effectuée');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
            $this->getProcessusPlafond()->endTransaction($service, $typeVolumeHoraire, true);
        }

        return new MessengerViewModel;
    }



    public function initialisationAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getProcessusPlafond()->beginTransaction();
        $this->getServiceService()->setPrevusFromPrevus($intervenant);
        $this->updateTableauxBord($intervenant);
        $this->getProcessusPlafond()->endTransaction($intervenant, $this->getServiceTypeVolumeHoraire()->getPrevu());
        $errors = [];

        $vm = new ViewModel();
        $vm->setTemplate('enseignement/initialisation');
        $vm->setVariables(compact('errors'));

        return $vm;
    }



    public function constatationAction ()
    {
        $this->initFilters();
        $realise  = $this->getServiceTypeVolumeHoraire()->getRealise();
        $services = $this->params()->fromQuery('services');

        if ($services) {
            $services = explode(',', $services);
            foreach ($services as $n => $sid) {
                $services[$n] = $this->getServiceService()->get($sid);

                $intervenant                         = $services[$n]->getIntervenant();
                $intervenants[$intervenant->getId()] = $intervenant;
            }
        }

        if (empty($services)) {
            $this->flashMessenger()->addErrorMessage('Aucun service précisé : constatation impossible');

            return [];
        }

        if (count($intervenants) > 1) {
            $this->flashMessenger()->addErrorMessage('On ne peut constater les services que d\'un seul intervenant à la fois');

            return [];
        }

        $this->getProcessusPlafond()->beginTransaction();

        foreach ($services as $service) {
            $service->setTypeVolumeHoraire($realise);
            if ($this->isAllowed($service, Privileges::ENSEIGNEMENT_REALISE_EDITION)) {
                $this->getServiceService()->setRealisesFromPrevus($service);
            }
        }
        $this->updateTableauxBord($intervenant);

        if (!$this->getProcessusPlafond()->endTransaction($intervenant, $realise)) {
            $this->flashMessenger()->addErrorMessage('La constatation des services réalisés n\'a donc pas pu se faire.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Les services prévisionnels ont été reportés comme réalisés.');
        }

        $vm = new ViewModel();
        $vm->setTemplate('enseignement/constatation');

        return $vm;
    }



    public function importAgendaPrevisionnelAction ()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getServiceService()->setPrevusFromAgenda($intervenant);
        $this->updateTableauxBord($intervenant);

        return new MessengerViewModel();
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
        // pas de filtre pour qu'une composante puisse voir ses enseignements validée par d'autres en prévisionnel

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $rsv = $this->getServiceRegleStructureValidation()->getBy($typeVolumeHoraire, $intervenant);
        if ($rsv && $rsv->getMessage()) {
            $this->flashMessenger()->addInfoMessage($rsv->getMessage());
        }

        $title = "Validation des enseignements";

        if ($typeVolumeHoraire->isPrevu()) {
            $title .= " prévisionnels";
        } elseif ($typeVolumeHoraire->isRealise()) {
            $title .= " réalisés";
        }

        $services = [
            'valides'     => [],
            'non-valides' => [],
        ];

        $validations = $this->getProcessusValidationEnseignement()->lister($typeVolumeHoraire, $intervenant, $filterStructure);
        foreach ($validations as $validation) {
            $key                  = $validation->getId() ? 'valides' : 'non-valides';
            $vid                  = $this->getProcessusValidationEnseignement()->getValidationId($validation);
            $sList                = $this->getProcessusValidationEnseignement()->getServices($typeVolumeHoraire, $validation);
            $services[$key][$vid] = $sList;
        }

        /* Messages */
        if (empty($services['non-valides'])) {
            if ($role->getIntervenant()) {
                $message = sprintf(
                    "Tous vos enseignements %s ont été validés.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnels" : "réalisés"
                );
            } else {
                $message = sprintf(
                    "Aucun enseignement %s n'est en attente de validation.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnel" : "réalisé"
                );
            }
            $this->flashMessenger()->addSuccessMessage($message);
        }

        $vm = new ViewModel();
        $vm->setVariables(compact('title', 'typeVolumeHoraire', 'intervenant', 'validations', 'services'));
        $vm->setTemplate('enseignement/validation');

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


        $plafondOk = $this->getProcessusPlafond()->controle($intervenant, $typeVolumeHoraire, true);
        if (!$plafondOk) {
            return new MessengerViewModel();
        }

        $validation = $this->getProcessusValidationEnseignement()->creer($intervenant, $structure);

        if ($this->isAllowed($validation, $typeVolumeHoraire->getPrivilegeEnseignementValidation())) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationEnseignement()->enregistrer($typeVolumeHoraire, $validation);
                    $this->updateTableauxBord($validation->getIntervenant(), true);
                    $this->flashMessenger()->addSuccessMessage(
                        "Validation effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de valider ces enseignements.');
        }

        return new MessengerViewModel();
    }



    public function devaliderAction ()
    {
        $this->initFilters();

        $validation = $this->getEvent()->getParam('validation');
        /* @var $validation Validation */

        if ($this->isAllowed($validation, Privileges::ENSEIGNEMENT_DEVALIDATION)) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusValidationEnseignement()->supprimer($validation);
                    $this->updateTableauxBord($validation->getIntervenant(), true);
                    $this->flashMessenger()->addSuccessMessage(
                        "Dévalidation effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de dévalider ces enseignements.');
        }


        return new MessengerViewModel();
    }
}
