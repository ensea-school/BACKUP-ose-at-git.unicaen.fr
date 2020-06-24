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
use Application\Processus\Traits\PlafondProcessusAwareTrait;
use Application\Processus\Traits\ServiceProcessusAwareTrait;
use Application\Processus\Traits\ServiceReferentielProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\CampagneSaisieServiceAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\FormuleResultatServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\RegleStructureValidationServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use LogicException;
use Application\Entity\Db\Intervenant;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Zend\View\Model\ViewModel;

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

        $critere      = $this->params()->fromPost('critere');
        $intervenants = $this->getProcessusIntervenant()->recherche()->rechercher($critere, 21);

        return compact('intervenants');
    }



    public function voirAction()
    {
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $tab         = $this->params()->fromQuery('tab');

        if (!$intervenant) {
            throw new \LogicException('Intervenant introuvable');
        }

        $this->addIntervenantRecent($intervenant);

        return compact('intervenant', 'role', 'tab');
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
        $role         = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant  = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
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
            $intervenant->setStructure($this->getServiceContext()->getStructure());
            $intervenant->setStatut($this->getServiceStatutIntervenant()->getAutres());
            $intervenant->setAnnee($this->getServiceContext()->getAnnee());
            $intervenant->setSource($this->getServiceSource()->getOse());
            $intervenant->setCode(uniqid('OSE'));
        }

        if ($actionDetail == 'dupliquer') {
            $intervenant = $intervenant->dupliquer();
            $intervenant->setSource($this->getServiceSource()->getOse());
            $intervenant->setSourceCode(null);
            $intervenant->setStatut($this->getServiceStatutIntervenant()->getAutres());
        }

        $canEdit = $this->isAllowed($intervenant, Privileges::INTERVENANT_EDITION);
        $form->setReadOnly(!$canEdit);
        $form->bind($intervenant);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $oriData  = $form->getHydrator()->extract($intervenant);
            $postData = $request->getPost()->toArray();
            $data     = array_merge($oriData, $postData);
            $form->setData($data);
            if ((!$form->isReadOnly()) && $form->isValid()) {
                try {
                    $form->protection($intervenant);
                    $this->getServiceIntervenant()->save($intervenant);
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

        return compact('intervenant', 'form', 'errors', 'title', 'definiParDefaut');
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

        if ($intervenant) {
            $data = $this->getProcessusIntervenant()->suppression()->getData($intervenant);
        } else {
            $data = null;
        }


        $ids = $this->params()->fromPost('ids');

        if ($ids) {
            try {
                if ($data) $data = $this->getProcessusIntervenant()->suppression()->deleteRecursive($data, $ids);
                if ($intervenant) {
                    $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
                }
                if (!$data) {
                    $this->flashMessenger()->addSuccessMessage('Fiche intervenant supprimée intégralement. Vous allez être redirigé(e) vers la page de recherche des intervenants.');
                } else {
                    $this->flashMessenger()->addSuccessMessage('Données bien supprimées');
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        } else {
            $this->flashMessenger()->addWarningMessage(
                'Attention : La suppression d\'une fiche entraine la suppression de toutes les données associées pour l\'année en cours'
            );
        }

        return compact('intervenant', 'data');
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

        if (empty($regleStructureValidation)) {
            $title                    = 'Création d\'une nouvelle régle';
            $regleStructureValidation = $this->getServiceRegleStructureValidation()->newEntity();
        } else {
            $title = 'Édition d\'une règle';
        }

        $form->bindRequestSave($regleStructureValidation, $this->getRequest(), function (RegleStructureValidation $rsv) {
            try {
                $this->getServiceRegleStructureValidation()->save($rsv);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $message = $this->translate($e);

                if (false !== strpos($message, 'ORA-00001')) {
                    $this->flashMessenger()->addErrorMessage("Règle non enregistrée car elle existe déjà dans OSE");
                } else {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        });

        return compact('form', 'title');
    }



    public function validationVolumeHoraireTypeIntervenantDeleteAction()
    {
        $regleStructureValidation = $this->getEvent()->getParam('regleStructureValidation');

        try {
            $this->getServiceRegleStructureValidation()->delete($regleStructureValidation);
            $this->flashMessenger()->addSuccessMessage("Règle supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('regleStructureValidation'));
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
                'numero-personnel' => $intervenant->getSourceCode(),
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
            return $a['nom'] . ' ' . $a['prenom'] > $b['nom'] . ' ' . $b['prenom'];
        });

        return $this;
    }
}
