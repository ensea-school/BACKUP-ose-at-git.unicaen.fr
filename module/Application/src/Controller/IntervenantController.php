<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Application\Form\Intervenant\Traits\EditionFormAwareTrait;
use Application\Form\Intervenant\Traits\HeuresCompFormAwareTrait;
use Application\Processus\Traits\IntervenantProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\FormuleResultatServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Intervenant\Service\NoteServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use LogicException;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Referentiel\Processus\ServiceReferentielProcessusAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use UnicaenImport\Entity\Differentiel\Query;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;
use UnicaenImport\Service\Traits\DifferentielServiceAwareTrait;

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
    use ServiceReferentielProcessusAwareTrait;
    use LocalContextServiceAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use PlafondProcessusAwareTrait;
    use FormuleResultatServiceAwareTrait;
    use StatutServiceAwareTrait;
    use SourceServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use ImportProcessusAwareTrait;
    use DifferentielServiceAwareTrait;
    use NoteServiceAwareTrait;


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



    public function rechercheJsonAction()
    {
        $recherche         = $this->getProcessusIntervenant()->recherche();
        $canShowHistorises = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_VISUALISATION_HISTORISES));
        $recherche->setShowHisto($canShowHistorises);
        $intervenants = [];
        $term         = $this->axios()->fromPost('term');

        if (!empty($term)) {
            $intervenants = $recherche->rechercher($term, 40);
        }

        return $this->axios()->send($intervenants);
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
        $date = new \DateTime();
        $date->sub(new \DateInterval('P7D'));
        $notificationNote = $this->getServiceNote()->countNote($intervenant, $date);
        $this->addIntervenantRecent($intervenant);

        return compact('intervenant', 'tab', 'notificationNote');
    }



    public function definirParDefautAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');

        $definiParDefaut = $this->getServiceIntervenant()->estDefiniParDefaut($intervenant);
        $this->getServiceIntervenant()->definirParDefaut($intervenant, !$definiParDefaut);
        $definiParDefaut = $this->getServiceIntervenant()->estDefiniParDefaut($intervenant);

        return compact('definiParDefaut');
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
            $intervenant->setStatut($this->getServiceStatut()->getAutres());
        }

        $canEdit = $this->isAllowed($intervenant, Privileges::INTERVENANT_EDITION);
        $form->setReadOnly(!$canEdit);
        $form->bind($intervenant);
        //Edition avancée pour éditer le code et la source de l'intervenant
        $canEditAvancee = $this->isAllowed($intervenant, Privileges::INTERVENANT_EDITION_AVANCEE);
        if ($canEditAvancee) {
            $form->activerEditionAvancee();
        }

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
        if (!$typeVolumeHoraire instanceof TypeVolumeHoraire) {
            $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->get($typeVolumeHoraire);
        }
        /* @var $typeVolumeHoraire \Service\Entity\Db\TypeVolumeHoraire */
        if (!isset($typeVolumeHoraire)) {
            throw new LogicException('Type de volume horaire erroné');
        }

        $etatVolumeHoraire = $this->context()->etatVolumeHoraireFromQuery('etat-volume-horaire', $form->get('etat-volume-horaire')->getValue());
        if (!$etatVolumeHoraire instanceof EtatVolumeHoraire) {
            $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->get($etatVolumeHoraire);
        }
        /* @var $etatVolumeHoraire \Service\Entity\Db\EtatVolumeHoraire */
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
                'numero-personnel' => $intervenant->getCodeRh(),
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
