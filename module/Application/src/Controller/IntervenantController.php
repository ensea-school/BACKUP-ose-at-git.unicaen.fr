<?php

namespace Application\Controller;

use Application\Entity\Db\RegleStructureValidation;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Application\Entity\Service\Recherche;
use Application\Form\Intervenant\Traits\EditionFormAwareTrait;
use Application\Form\Intervenant\Traits\HeuresCompFormAwareTrait;
use Application\Form\Intervenant\Traits\RegleStructureValidationFormAwareTrait;
use Application\Processus\Traits\IntervenantProcessusAwareTrait;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Application\Processus\Traits\ServiceProcessusAwareTrait;
use Application\Processus\Traits\ServiceReferentielProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\CampagneSaisieServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\FormuleResultatServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\RegleStructureValidationServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use LogicException;
use Application\Entity\Db\Intervenant;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenImport\Entity\Differentiel\Query;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;
use UnicaenImport\Service\Traits\DifferentielServiceAwareTrait;
use Laminas\View\Model\ViewModel;

/**
 * Description of IntervenantController
 *
 */
class  IntervenantController extends AbstractController
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use HeuresCompFormAwareTrait;
    use SessionContainerTrait;
    use EditionFormAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use IntervenantProcessusAwareTrait;
    use ServiceProcessusAwareTrait;
    use ServiceReferentielProcessusAwareTrait;
    use LocalContextServiceAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use PlafondProcessusAwareTrait;
    use FormuleResultatServiceAwareTrait;
    use RegleStructureValidationServiceAwareTrait;
    use RegleStructureValidationFormAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use SourceServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use DossierServiceAwareTrait;
    use ImportProcessusAwareTrait;
    use DifferentielServiceAwareTrait;


    public function indexAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();


        if ($intervenant = $role->getIntervenant()) {
            $etapeCourante = $this->getServiceWorkflow()->getEtapeCourante();
            if ($etapeCourante && $this->getServiceWorkflow()->isAllowed($etapeCourante)) {
                if ($etapeCourante && $url = $etapeCourante->getUrl()) {
                    return $this->redirect()->toUrl($url);
                }
            } else {
                return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => $intervenant->getId()]);
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

        $critere   = $this->params()->fromPost('critere');
        $recherche = $this->getProcessusIntervenant()->recherche();

        $canShowHistorises = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_VISUALISATION_HISTORISES));
        $recherche->setShowHisto($canShowHistorises);

        $intervenants = $recherche->rechercher($critere, 21);

        return compact('intervenants');
    }



    public function voirAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $tab         = $this->params()->fromQuery('tab');

        if (!$intervenant) {
            throw new \LogicException('Intervenant introuvable');
        }

        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
            $vh = new ViewModel();
            $vh->setTemplate('application/intervenant/menu');

            return $vh;
        }

        $this->addIntervenantRecent($intervenant);

        return compact('intervenant', 'tab');
    }



    public function definirParDefautAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');

        $definiParDefaut = $this->getServiceIntervenant()->estDefiniParDefaut($intervenant);
        $this->getServiceIntervenant()->definirParDefaut($intervenant, !$definiParDefaut);
        $definiParDefaut = $this->getServiceIntervenant()->estDefiniParDefaut($intervenant);

        return compact('definiParDefaut');
    }



    public function servicesAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Service::class,
            \Application\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\CheminPedagogique::class,
            \Application\Entity\Db\ServiceReferentiel::class,
            \Application\Entity\Db\VolumeHoraireReferentiel::class,
            \Application\Entity\Db\Validation::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            \Application\Entity\Db\ElementPedagogique::class,
        ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
            $vh = new ViewModel();
            $vh->setTemplate('application/intervenant/menu');

            return $vh;
        }

        $typeVolumeHoraire = $this->params()->fromRoute('type-volume-horaire-code', TypeVolumeHoraire::CODE_PREVU);
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraire);

        $this->getProcessusPlafond()->controle($intervenant, $typeVolumeHoraire);

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
                    $this->getServiceWorkflow()->calculerTableauxBord('cloture_realise', $intervenant);
                    $this->flashMessenger()->addSuccessMessage("La saisie du service réalisé a bien été réouverte", 'success');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            } else {
                if (!$this->isAllowed($intervenant, Privileges::CLOTURE_CLOTURE)) {
                    throw new \Exception("Vous n'avez pas le droit de clôturer la saisie de services réalisés d'un intervenant");
                }
                try {
                    $this->getServiceValidation()->save($validation);
                    $this->getServiceWorkflow()->calculerTableauxBord('cloture_realise', $intervenant);
                    $this->flashMessenger()->addSuccessMessage("La saisie du service réalisé a bien été clôturée", 'success');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
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
        $intervenant  = $this->getEvent()->getParam('intervenant');
        $title        = "Saisie d'un intervenant";
        $form         = $this->getFormIntervenantEdition();
        $errors       = [];
        $actionDetail = $this->params()->fromRoute('action-detail');
        if ($intervenant) {
            $definiParDefaut = $this->getServiceIntervenant()->estDefiniParDefaut($intervenant);
        } else {
            $definiParDefaut = false;
        }

        $isNew = !$intervenant;
        if (!$intervenant) {
            $intervenant = $this->getServiceIntervenant()->newEntity();
        }

        if ($actionDetail == 'dupliquer') {
            $intervenant = $intervenant->dupliquer();
            $intervenant->setSource($this->getServiceSource()->getOse());
            $intervenant->setStatut($this->getServiceStatutIntervenant()->getAutres());
        }

        $canEdit = $this->isAllowed($intervenant, Privileges::INTERVENANT_EDITION);
        $form->setReadOnly(!$canEdit);
        $form->bind($intervenant);

        $ancienStatut = $intervenant->getStatut();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $oriData  = $form->getHydrator()->extract($intervenant);
            $postData = $request->getPost()->toArray();
            $data     = array_merge($oriData, $postData);
            $form->setData($data);
            if ((!$form->isReadOnly()) && $form->isValid()) {
                try {
                    if ($form->get('intervenant-edition-login')->getValue() && $form->get('intervenant-edition-password')->getValue()) {
                        $nom           = $intervenant->getNomUsuel();
                        $prenom        = $intervenant->getPrenom();
                        $dateNaissance = $intervenant->getDateNaissance();
                        $login         = $form->get('intervenant-edition-login')->getValue();
                        $password      = $form->get('intervenant-edition-password')->getValue();
                        $utilisateur   = $this->getServiceUtilisateur()->creerUtilisateur($nom, $prenom, $dateNaissance, $login, $password);
                        $utilisateur->setCode($intervenant->getUtilisateurCode() ?: $intervenant->getCode());
                        $this->getServiceUtilisateur()->save($utilisateur);
                        if ($utilisateur->getCode() != $intervenant->getUtilisateurCode()) {
                            $intervenant->setUtilisateurCode($utilisateur->getCode());
                            $intervenant->setSyncUtilisateurCode(false);
                        }
                    }
                    $this->getServiceIntervenant()->save($intervenant);
                    if ($intervenant->getStatut() != $ancienStatut) {
                        $dossier = $this->getServiceDossier()->getByIntervenant($intervenant);
                        if ($dossier->getId()) { // Il y a un dossier
                            $dossier->setStatut($intervenant->getStatut());
                            $this->getServiceDossier()->save($dossier); // On sauvegarde le dossier
                        }
                    }
                    $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
                    $form->get('id')->setValue($intervenant->getId()); // transmet le nouvel ID
                    if ($isNew) {
                        $etape = $this->getServiceWorkflow()->getEtapeCourante($intervenant);
                        if ($etape) {
                            return $this->redirect()->toUrl($etape->getUrl());
                        }
                    }

                    return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => $intervenant->getId()], ['query' => ['tab' => 'edition']]);
                } catch (\Exception $e) {
                    $errors[] = $this->translate($e);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/intervenant/saisir');
        $vm->setVariables(compact('intervenant', 'form', 'errors', 'title', 'definiParDefaut', 'actionDetail'));

        return $vm;
    }



    public function synchronisationAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');

        try {
            $isImportable = $this->getServiceIntervenant()->isImportable($intervenant);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
            $isImportable = false;
        }
        $data = [];

        if ($isImportable) {
            $query = new Query('INTERVENANT');
            $query->setNotNull([]); // Aucune colonne ne doit être non nulle !!
            $query->setLimit(101);
            $query->setColValues(['ANNEE_ID' => $intervenant->getAnnee()->getId(), 'CODE' => $intervenant->getCode()]);
            $data = $this->getServiceDifferentiel()->make($query, $query::SQL_FULL, false)->fetchAll();
        }

        return compact('intervenant', 'isImportable', 'data');
    }



    public function synchroniserAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $this->getProcessusImport()->execMaj('INTERVENANT', 'CODE', $intervenant->getCode());
        $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);

        return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => $intervenant->getId()], ['query' => ['tab' => 'synchronisation']]);
    }



    public function voirHeuresCompAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant \Application\Entity\Db\Intervenant */

        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

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

        $form->setData([
            'type-volume-horaire' => $typeVolumeHoraire->getId(),
            'etat-volume-horaire' => $etatVolumeHoraire->getId(),
        ]);

        $data = $this->getServiceFormuleResultat()->getData(
            $intervenant,
            $typeVolumeHoraire,
            $etatVolumeHoraire
        );

        return compact('form', 'intervenant', 'data');
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

        if (!$intervenant) {
            throw new \Exception('Intervenant introuvable');
        }
        $intervenantCode = $intervenant->getCode();

        $intSuppr = $this->getProcessusIntervenant()->suppression($intervenant);

        if ($ids = $this->params()->fromPost('ids')) {
            try {
                if (!empty($ids)) {
                    $res = $intSuppr->delete($ids);
                    $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
                    if ($res) {
                        $this->flashMessenger()->addSuccessMessage('Données de l\'intervenant supprimées');
                    } else {
                        $this->flashMessenger()->addErrorMessage('Une ou plusieurs erreurs ont été rencontrées');
                    }
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }
        $tree = $intSuppr->getTree();
        if (!$tree) {
            $intervenant = $this->getServiceIntervenant()->getByCode($intervenantCode);
            if ($intervenant && $intervenant->estHistorise()) $intervenant = null;
        }

        return compact('intervenant', 'tree');
    }



    public function historiserAction()
    {
        /* @var $intervenant \Application\Entity\Db\Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if (!$intervenant) {
            throw new \Exception('Intervenant introuvable');
        }
        $this->getServiceIntervenant()->delete($intervenant);

        return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => 'code:' . $intervenant->getCode()]);
    }



    public function restaurerAction()
    {
        /* @var $intervenant \Application\Entity\Db\Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if (!$intervenant) {
            throw new \Exception('Intervenant introuvable');
        }
        $intervenant->dehistoriser();
        $this->getServiceIntervenant()->save($intervenant);
        $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);

        return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => $intervenant->getId()]);
    }



    public function validationVolumeHoraireTypeIntervenantAction()
    {
        $serviceRVS = $this->getServiceRegleStructureValidation();
        $listeRsv   = $serviceRVS->getList();

        return compact('listeRsv');
    }



    public function validationVolumeHoraireTypeIntervenantSaisieAction()
    {
        $regleStructureValidation = $this->getEvent()->getParam('regleStructureValidation');
        $form                     = $this->getFormRegleStructureValidationSaisie();
        $title                    = 'Édition de la régle de validation';
        $form->bindRequestSave($regleStructureValidation, $this->getRequest(), function (RegleStructureValidation $rsv) {
            try {
                $this->getServiceRegleStructureValidation()->save($rsv);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $message = $this->translate($e);

                if (false !== strpos($message, 'ORA - 00001')) {
                    $this->flashMessenger()->addErrorMessage("Règle non enregistrée car elle existe déjà dans OSE");
                } else {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        });

        return compact('form', 'title');
    }



    /**
     *
     * @return array
     */
    protected function getIntervenantsRecents()
    {
        $container = $this->getSessionContainer();
        //$container->recents = [];
        if (isset($container->recents)) {
            $recents = $container->recents;
            foreach ($recents as $i => $recent) {
                if (isset($recent['code'])) {
                    $intervenant = $this->getServiceIntervenant()->getByCode($recent['code']);
                } else {
                    $intervenant = null;
                }
                if (!$intervenant) {
                    unset($recents[$i]);
                    unset($container->recents[$i]);
                }
            }

            return $recents;
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

        if (count($container->recents) > 4 && !isset($container->recents[$intervenant->getCode()])) {
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

        if (!isset($container->recents[$intervenant->getCode()])) {
            $container->recents[$intervenant->getCode()] = [
                'civilite'         => $intervenant->getCivilite() ? $intervenant->getCivilite()->getLibelleLong() : null,
                'nom'              => $intervenant->getNomUsuel(),
                'prenom'           => $intervenant->getPrenom(),
                'date-naissance'   => $intervenant->getDateNaissance(),
                'structure'        => (string)$intervenant->getStructure(),
                'statut'           => (string)$intervenant->getStatut(),
                'code'             => $intervenant->getCode(),
                'numero-personnel' => $intervenant->getCode(),
                '__horo_ajout__'   => (int)date('U'),
            ];
        } else {
            if (!isset($container->recents[$intervenant->getCode()]['statut'])) {
                $container->recents[$intervenant->getCode()]['statut'] = [$container->recents[$intervenant->getCode()]['statut']];
            }
            if (is_array($container->recents[$intervenant->getCode()]['statut'])) {
                $container->recents[$intervenant->getCode()]['statut'][] = (string)$intervenant->getStatut();
            } else {
                $container->recents[$intervenant->getCode()]['statut'] = (string)$intervenant->getStatut();
            }
        }

        uasort($container->recents, function ($a, $b) {
            return $a['nom'] . ' ' . $a['prenom'] > $b['nom'] . ' ' . $b['prenom'] ? 1 : 0;
        });

        return $this;
    }
}
