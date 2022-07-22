<?php

namespace Application\Controller;

use Application\Entity\Db\ElementPedagogique;
use Enseignement\Entity\Db\Service;
use Application\Entity\Db\Validation;
use Application\Form\Service\Saisie;
use Service\Form\RechercheFormAwareTrait;
use Application\Form\Service\Traits\SaisieAwareTrait;
use Enseignement\Processus\EnseignementProcessusAwareTrait;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Enseignement\Processus\ValidationEnseignementProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Laminas\Http\Request;
use Application\Entity\Db\Intervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;

/**
 * Description of ServiceController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceController extends AbstractController
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
    use SaisieAwareTrait;
    use RechercheFormAwareTrait;
    use ValidationEnseignementProcessusAwareTrait;
    use RegleStructureValidationServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use PlafondProcessusAwareTrait;
    use EtatSortieServiceAwareTrait;


    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Enseignement\Entity\Db\Service::class,
            \Enseignement\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\Validation::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            ElementPedagogique::class,
        ]);
    }



    public function rafraichirLigneAction()
    {
        $this->initFilters();

        $params      = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $details     = 1 == (int)$this->params()->fromQuery('details', (int)$this->params()->fromPost('details', 0));
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content', 0);
        $service     = $this->getEvent()->getParam('service');

        return compact('service', 'params', 'details', 'onlyContent');
    }



    public function volumesHorairesRefreshAction()
    {
        $this->initFilters();

        $id                = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));
        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }
        $service = $this->getServiceService();
        $form    = $this->getFormServiceSaisie();
        $form->setTypeVolumeHoraire($typeVolumeHoraire);
        $element       = $this->context()->elementPedagogiqueFromPost('element');
        $etablissement = $this->context()->etablissementFromPost();

        if ($id) {
            $entity = $service->get($id);
            /* @var $entity \Enseignement\Entity\Db\Service */
        } else {
            $entity = $service->newEntity();
        }
        $entity->setTypeVolumeHoraire($typeVolumeHoraire);
        $entity->setEtablissement($etablissement);
        $entity->setElementPedagogique($element);
        $form->bind($entity);

        if (!$id) $form->initFromContext();

        return compact('form');
    }



    public function initialisationAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getProcessusPlafond()->beginTransaction();
        $this->getServiceService()->setPrevusFromPrevus($intervenant);
        $this->updateTableauxBord($intervenant);
        $this->getProcessusPlafond()->endTransaction($intervenant, $this->getServiceTypeVolumeHoraire()->getPrevu());
        $errors = [];

        return compact('errors');
    }



    public function importAgendaPrevisionnelAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getServiceService()->setPrevusFromAgenda($intervenant);
        $this->updateTableauxBord($intervenant);

        return new MessengerViewModel();
    }



    public function constatationAction()
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

        return [];
    }



    public function suppressionAction()
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
            $this->getProcessusPlafond()->endTransaction($service->getIntervenant(), $typeVolumeHoraire, true);
        }

        return new MessengerViewModel;
    }



    public function saisieAction()
    {
        $this->initFilters();
        $id                = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire'));

        $intervenant = $this->context()->intervenantFromQuery('intervenant');
        if (!$intervenant) {
            $service = $this->params()->fromPost('service');
            if (isset($service['intervenant-id'])) {
                $intervenant = $this->getServiceIntervenant()->get($service['intervenant-id']);
            }
        }

        if (empty($typeVolumeHoraire)) {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        } else {
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get($typeVolumeHoraire);
        }


        $service = $this->getServiceService();
        $form    = $this->getFormServiceSaisie();
        $form->setTypeVolumeHoraire($typeVolumeHoraire);

        if ($id) {
            $entity = $service->get($id);
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $form->bind($entity);
            $title = "Modification d'enseignement";
        } else {
            $entity = $service->newEntity();
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $entity->setIntervenant($intervenant);
            $form->bind($entity);
            $form->initFromContext();
            $title = "Ajout d'enseignement";
        }

        $form->get('service')->setIntervenant($intervenant);
        $form->get('service')->removeUnusedElements();
        $hDeb    = $entity->getVolumeHoraireListe()->getHeures();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if (!$this->isAllowed($entity, $typeVolumeHoraire->getPrivilegeEnseignementEdition())) {
                    $this->flashMessenger()->addErrorMessage("Vous n'êtes pas autorisé à créer ou modifier ce service.");
                } else {
                    $form->saveToContext();
                    $this->getProcessusPlafond()->beginTransaction();
                    try {
                        $entity = $service->save($entity);
                        $form->get('service')->get('id')->setValue($entity->getId()); // transmet le nouvel ID
                        $hFin = $entity->getVolumeHoraireListe()->getHeures();
                        $this->updateTableauxBord($entity->getIntervenant());
                        if (!$this->getProcessusPlafond()->endTransaction($entity->getIntervenant(), $typeVolumeHoraire, $hFin < $hDeb)) {
                            $this->updateTableauxBord($entity->getIntervenant());
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

        return compact('form', 'title');
    }



    public function validationAction()
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

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', 'PREVU'));

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



    public function devaliderAction()
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



    private function updateTableauxBord(Intervenant $intervenant, $validation = false)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'formule',
            'validation_enseignement',
            'contrat',
        ], $intervenant);

        if (!$validation) {
            $this->getServiceWorkflow()->calculerTableauxBord(['service', 'piece_jointe_demande', 'piece_jointe_fournie'], $intervenant);
        }
    }
}
