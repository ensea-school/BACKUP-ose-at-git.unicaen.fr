<?php

namespace Intervenant\Controller;

use Application\Constants;
use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Form\EditionFormAwareTrait;
use Intervenant\Processus\IntervenantProcessusAwareTrait;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\NoteServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Mission\Service\CandidatureServiceAwareTrait;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Referentiel\Processus\ServiceReferentielProcessusAwareTrait;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use UnicaenImport\Entity\Differentiel\Query;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;
use UnicaenImport\Service\Traits\DifferentielServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Utilisateur\Service\UtilisateurServiceAwareTrait;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of IntervenantController
 *
 */
class  IntervenantController extends AbstractController
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;
    use IntervenantServiceAwareTrait;
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
    use StatutServiceAwareTrait;
    use SourceServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use ImportProcessusAwareTrait;
    use DifferentielServiceAwareTrait;
    use NoteServiceAwareTrait;
    use DossierServiceAwareTrait;
    use CandidatureServiceAwareTrait;


    public function indexAction()
    {
        if ($intervenant = $this->getServiceContext()->getIntervenant()) {
            $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
            $etapeCourante = $feuilleDeRoute->getCourante();
            if ($etapeCourante && $etapeCourante->isAllowed()) {
                if ($url = $etapeCourante->url) {
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



    public function rechercheJsonAction()
    {
        $recherche = $this->getProcessusIntervenant()->recherche();
        $canShowHistorises = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_VISUALISATION_HISTORISES));
        $recherche->setShowHisto($canShowHistorises);
        $intervenants = [];
        $term = $this->axios()->fromPost('term');

        if (!empty($term)) {
            $intervenants = $recherche->rechercher($term, 40);
        }

        return new AxiosModel($intervenants);
    }



    public function rechercheAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $res = $this->getProcessusIntervenant()->recherche()->rechercherLocalement($term, 50, ':ID');

        $result = [];
        foreach ($res as $key => $r) {
            $feminin = $r['civilite'] == 'Madame';

            $details = [];
            if ($r['civilite']) {
                $details['civilite'] = $feminin ? 'M<sup>me</sup>' : 'M.';
            }
            $details['nom'] = strtoupper($r['nom']);
            $details['prenom'] = ucfirst($r['prenom']);
            $details['naissance'] = 'né' . ($feminin ? 'e' : '') . ' le ' . $r['date-naissance']->format(Constants::DATE_FORMAT);
            $details['code'] = 'N°' . $r['numero-personnel'];
            if ($r['structure']) {
                $details['structure'] = $r['structure'];
            }
            if ($r['statut']) {
                $details['statut'] = $r['statut'];
            }

            $result[$key] = [
                'id'    => $key,
                'label' => $details['nom'] . ' ' . $details['prenom'],
                'extra' => "<small>(" . implode(', ', $details) . ")</small>",
            ];
        }

        return new JsonModel($result);
    }



    public function voirAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $tab = $this->params()->fromQuery('tab');

        if (!$intervenant) {
            throw new \LogicException('Intervenant introuvable');
        }

        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
            $vh = new ViewModel();
            $vh->setTemplate('intervenant/intervenant/menu');

            return $vh;
        }
        $notificationNote = $this->getServiceNote()->countNote($intervenant);
        $this->addIntervenantRecent($intervenant);

        return compact('intervenant', 'tab', 'notificationNote');
    }



    /**
     *
     * @param \Intervenant\Entity\Db\Intervenant $intervenant
     *
     * @return IntervenantController
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
        $intervenant = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');

        return compact('intervenant');
    }



    public function saisirAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $title = "Saisie d'un intervenant";
        $form = $this->getFormIntervenantEdition();
        $errors = [];
        $actionDetail = $this->params()->fromRoute('action-detail');
        if ($intervenant) {
            $definiParDefaut = $this->getServiceIntervenant()->estDefiniParDefaut($intervenant);
        } else {
            $definiParDefaut = false;
        }

        $isNew = !$intervenant;
        if (!$intervenant) {
            $intervenant = $this->getServiceIntervenant()->newEntity();
            $intervenant->setSource($this->getServiceSource()->getOse());
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
            $oriData = $form->getHydrator()->extract($intervenant);
            $postData = $request->getPost()->toArray();
            $data = array_merge($oriData, $postData);
            $form->setData($data);
            if ((!$form->isReadOnly()) && $form->isValid()) {
                try {
                    if ($form->get('intervenant-edition-login')->getValue() && $form->get('intervenant-edition-password')->getValue()) {
                        $nom = $intervenant->getNomUsuel();
                        $prenom = $intervenant->getPrenom();
                        $dateNaissance = $intervenant->getDateNaissance();
                        $login = $form->get('intervenant-edition-login')->getValue();
                        $password = $form->get('intervenant-edition-password')->getValue();
                        $utilisateur = $this->getServiceUtilisateur()->creerUtilisateur($nom, $prenom, $dateNaissance, $login, $password);
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
                        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
                        $etape = $feuilleDeRoute->getCourante();
                        if ($etape && $etape->url) {
                            return $this->redirect()->toUrl($etape->url);
                        }
                    }

                    return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => $intervenant->getId()], ['query' => ['tab' => 'edition']]);
                } catch (\Exception $e) {
                    $errors[] = $this->translate($e);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('intervenant/intervenant/saisir');
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



    public function supprimerAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant \Intervenant\Entity\Db\Intervenant */

        if (!$intervenant) {
            throw new \Exception('Intervenant introuvable');
        }
        $intervenantCode = $intervenant->getCode();

        $intSuppr = $this->getProcessusIntervenant()->suppression($intervenant);

        $ids = $intSuppr->idsFromPost($this->params()->fromPost('ids'));
        if ($ids) {
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
        /* @var $intervenant \Intervenant\Entity\Db\Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if (!$intervenant) {
            throw new \Exception('Intervenant introuvable');
        }
        $this->getServiceIntervenant()->delete($intervenant);

        return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => 'code:' . $intervenant->getCode()]);
    }



    public function restaurerAction()
    {
        /* @var $intervenant \Intervenant\Entity\Db\Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if (!$intervenant) {
            throw new \Exception('Intervenant introuvable');
        }
        $intervenant->dehistoriser();
        $this->getServiceIntervenant()->save($intervenant);
        $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);

        return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => $intervenant->getId()]);
    }
}
