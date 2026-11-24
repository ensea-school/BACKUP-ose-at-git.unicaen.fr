<?php

namespace Enseignement\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Enseignement\Form\EnseignementSaisieFormAwareTrait;
use Enseignement\Processus\EnseignementProcessusAwareTrait;
use Enseignement\Processus\ValidationEnseignementProcessusAwareTrait;
use Enseignement\Service\ServiceServiceAwareTrait;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
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
            \Application\Entity\Db\Validation::class,
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
        //On set l'étape du service si elle a été renseigné sinon par défaut cela prendra
        //l'étape porteuse de l'élément pédagogique
        if ($service->getEtape()) {
            $form->get('service')->get('element-pedagogique')->get('etape')->setValue($service->getEtape()->getId());
        }
        $hDeb    = $service->getVolumeHoraireListe()->getHeures();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $isExterne = $form->get('service')->has('interne-externe')
                    && $form->get('service')->get('interne-externe')->getValue() == 'service-externe';

                if (!$this->isAllowed($service, $typeVolumeHoraire->getPrivilegeEnseignementEdition())) {
                    $this->flashMessenger()->addErrorMessage("Vous n'êtes pas autorisé à créer ou modifier ce service.");
                } elseif ($isExterne && $service->getIntervenant() && !$service->getIntervenant()->getStructure()) {
                    $this->flashMessenger()->addErrorMessage("Impossible d'enregistrer un service hors établissement : l'intervenant n'a pas de structure d'affectation.");
                } else {
                    $form->saveToContext();
                    $this->getProcessusPlafond()->beginTransaction();
                    try {
                        //Correction evite les autocommits pour un rollback fonctionnel.
                        $this->em()->getConnection()->setAutoCommit(false);
                        //Uniquement pour le service fait dans l'établissement
                        if (!$isExterne) {
                            //Récupération de l'id de l'étape qui a été selectionné pour la recherche de la formation
                            $etapeId = $form->get('service')
                                            ->get('element-pedagogique')
                                            ->get('etape')
                                            ->getValue();

                            $etape   = $etapeId ? $this->getServiceEtape()->get($etapeId) : null;
                            $service = $service->setEtape($etape);
                        }

                        $service = $this->getServiceService()->save($service);
                        $this->em()->getConnection()->setAutoCommit(false);
                        $saved   = $service;
                        $form->get('service')->get('id')->setValue($service->getId()); // transmet le nouvel ID
                        $hFin = $service->getVolumeHoraireListe()->getHeures();
                        $this->updateTableauxBord($service->getIntervenant());
                        $entities = [$service];
                        foreach ($service->getVolumeHoraire() as $volumeHoraire) {
                            $entities[] = $volumeHoraire;
                        }
                        if (!$this->getProcessusPlafond()->endTransaction($entities, $typeVolumeHoraire, $hFin < $hDeb)) {
                            $saved = null; // rien n'a été enregistré : pas de rafraîchissement de la ligne
                            // Retour en autocommit, sinon le recalcul ci-dessous reste dans une transaction jamais validée
                            // qui verrouille les tables de calcul (et bloque les autres connexions)
                            $this->em()->getConnection()->setAutoCommit(true);
                            $this->updateTableauxBord($service->getIntervenant());
                        }
                    } catch (\Exception $e) {
                        $saved = null;
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
            'plafond_intervenant',
            'plafond_structure',
            'plafond_referentiel',
            'plafond_element',
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



    public function transfertDonneesAction()
    {
        $intervenantId = (int)$this->params()->fromQuery('intervenant', 0);
        $intervenant = $intervenantId ? $this->getServiceIntervenant()->get($intervenantId) : null;
        $mode = $this->params()->fromQuery('mode', '');
        $rows = [];
        if ($mode === 'prevu-to-realise') {
            $this->initFilters();
        }

        if (!$intervenant) {
            return new JsonModel(['rows' => [], 'message' => 'Intervenant introuvable.']);
        }

        if ($mode === 'prevu-to-prevu') {
            foreach ($this->getTransfertPrevuEnseignementData($intervenant) as $item) {
                $rows[] = [
                    'id' => $item['source-id'],
                    'label' => $item['label'],
                    'heures' => $item['hours-total'],
                    'transferable' => $item['transferable'],
                    'warning' => $item['warning'],
                    'warningMessage' => $item['warning-message'] ?? null,
                    'existingHours' => $item['existing-hours'] ?? 0,
                    'period' => $item['period'] ?? null,
                    'component' => $item['component'] ?? null,
                    'reason' => $item['reason'],
                ];
            }
        } elseif ($mode === 'prevu-to-realise') {
            $realise = $this->getServiceTypeVolumeHoraire()->getRealise();
            $recherche = new Recherche($this->getServiceTypeVolumeHoraire()->getPrevu(), $this->getServiceEtatVolumeHoraire()->getValide());
            $recherche->setIntervenant($intervenant);
            foreach ($this->getProcessusEnseignement()->getEnseignements($recherche) as $service) {
                $service->setTypeVolumeHoraire($realise);
                $prevus = $service->getVolumeHoraireListe()->createChild()
                    ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu())
                    ->setValidation(true);
                $hours = $prevus->getHeures();
                if ($hours <= 0) {
                    continue;
                }
                $element = $service->getElementPedagogique();
                $hasRight = $this->isAllowed($service, Privileges::ENSEIGNEMENT_REALISE_EDITION);
                $inScope = $this->getServiceService()->canTransferPrevusToRealise($service);
                $allowed = $hasRight && $inScope;
                $realiseHours = $service->getVolumeHoraireListe()->createChild()
                    ->setTypeVolumeHoraire($realise)->getHeures();
                $rows[] = [
                    'id' => $service->getId(),
                    'label' => $element ? $element->getCode() . ' — ' . $element->getLibelle() : 'Enseignement hors offre de formation',
                    'heures' => $hours,
                    'transferable' => $allowed,
                    'warning' => $realiseHours > 0,
                    'warningMessage' => null,
                    'existingHours' => $realiseHours,
                    'period' => $element && $element->getPeriode() ? $element->getPeriode()->getLibelleCourt() : null,
                    'component' => $element && $element->getStructure() ? $element->getStructure()->getLibelleCourt() : null,
                    'reason' => !$hasRight
                        ? 'Vous ne disposez pas des droits de saisie sur cet enseignement dans votre composante.'
                        : (!$inScope ? 'Cet enseignement est hors de votre périmètre de gestion.' : null),
                ];
            }
        }

        return new JsonModel(['rows' => $rows]);
    }



    private function getTransfertPrevuEnseignementData(Intervenant $intervenant): array
    {
        $data = $this->getServiceService()->getPrevusFromPrevusData($intervenant, true);
        foreach ($data as &$item) {
            if (!$item['transferable']) continue;

            $target = $item['service'] ?? $this->getServiceService()->newEntity()
                ->setIntervenant($intervenant)
                ->setElementPedagogique($item['element-pedagogique'])
                ->setEtablissement($item['etablissement']);
            $target->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
            if (!$this->isAllowed($target, Privileges::ENSEIGNEMENT_PREVU_EDITION)) {
                $item['transferable'] = false;
                $item['reason'] = 'Vous ne disposez pas des droits de saisie sur cet enseignement dans votre composante.';
            }
        }
        unset($item);

        return $data;
    }


    public function transfertExecuterAction()
    {
        $payload = json_decode((string)$this->getRequest()->getContent(), true) ?: [];
        $intervenantId = (int)($payload['intervenant'] ?? 0);
        $intervenant = $intervenantId ? $this->getServiceIntervenant()->get($intervenantId) : null;
        $mode = $payload['mode'] ?? '';
        $ids = array_values(array_unique(array_map('intval', $payload['ids'] ?? [])));
        if ($mode === 'prevu-to-realise') {
            $this->initFilters();
        }

        if (!$intervenant) {
            return new JsonModel(['success' => false, 'message' => 'Intervenant introuvable.']);
        }

        if (!$ids) {
            return new JsonModel(['success' => false, 'message' => 'Aucun enseignement sélectionné.']);
        }

        if (!in_array($mode, ['prevu-to-prevu', 'prevu-to-realise'], true)) {
            return new JsonModel(['success' => false, 'message' => 'Type de transfert inconnu.']);
        }

        if ($mode === 'prevu-to-prevu') {
            $eligibleIds = [];
            foreach ($this->getTransfertPrevuEnseignementData($intervenant) as $item) {
                if ($item['transferable']) {
                    $eligibleIds[] = (int)$item['source-id'];
                }
            }
            $ids = array_values(array_intersect($ids, $eligibleIds));
            if (!$ids) {
                return new JsonModel(['success' => false, 'message' => 'Aucun des enseignements sélectionnés ne peut être transféré.']);
            }
            $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        } else {
            if (!$this->isAllowed($intervenant, Privileges::ENSEIGNEMENT_REALISE_EDITION)) {
                return new JsonModel(['success' => false, 'message' => "Vous n’êtes pas autorisé à modifier le service réalisé."]);
            }
            $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();
            $services = [];
            foreach ($ids as $id) {
                $service = $this->getServiceService()->get($id);
                if (!$service) {
                    continue;
                }
                $service->setTypeVolumeHoraire($typeVolumeHoraire);
                if ($service->getIntervenant()->getId() !== $intervenant->getId()
                    || !$this->isAllowed($service, Privileges::ENSEIGNEMENT_REALISE_EDITION)
                    || !$this->getServiceService()->canTransferPrevusToRealise($service)) {
                    continue;
                }
                $prevu = $service->getVolumeHoraireListe()->createChild()
                    ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu())
                    ->setValidation(true);
                if ($prevu->getHeures() > 0) {
                    $services[] = $service;
                }
            }
            if (!$services) {
                return new JsonModel(['success' => false, 'message' => 'Aucun des enseignements sélectionnés ne peut être transféré.']);
            }
        }

        $this->getProcessusPlafond()->beginTransaction();
        try {
            if ($mode === 'prevu-to-prevu') {
                $this->getServiceService()->setPrevusFromPrevus($intervenant, $ids);
            } else {
                foreach ($services as $service) {
                    $service->setTypeVolumeHoraire($typeVolumeHoraire);
                    $this->getServiceService()->setRealisesFromPrevus($service);
                }
            }
            $this->updateTableauxBord($intervenant);
            $messages = [];
            foreach ($this->getProcessusPlafond()->getServicePlafond()->controle($intervenant, $typeVolumeHoraire) as $controle) {
                if ($controle->isBloquant() && $controle->isDepassement()) $messages[] = (string)$controle;
            }
            if ($messages) {
                $this->em()->rollback();
                $this->getProcessusPlafond()->getBdd()->setDoctrineConnection(null);
                return new JsonModel([
                    'success' => false,
                    'blocked' => true,
                    'message' => 'La bascule a été annulée : aucun changement n’a été enregistré. ' . implode(' ', array_unique($messages)),
                ]);
            }
            $ok = $this->getProcessusPlafond()->endTransaction($intervenant, $typeVolumeHoraire);
        } catch (\Throwable $e) {
            if ($this->em()->getConnection()->isTransactionActive()) {
                $this->em()->rollback();
            }
            $this->getProcessusPlafond()->getBdd()->setDoctrineConnection(null);

            return new JsonModel([
                'success' => false,
                'message' => 'La bascule a été annulée : aucun changement n’a été enregistré. ' . $this->translate($e),
            ]);
        }
        if (!$ok) {
            return new JsonModel(['success' => false, 'message' => 'La bascule a été annulée par le contrôle des plafonds ; aucun changement n’a été enregistré.']);
        }

        return new JsonModel(['success' => true]);
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
