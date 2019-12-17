<?php

namespace Application\Controller;

use Application\Assertion\ContratAssertion;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\ModeleContrat;
use Application\Entity\Db\Service;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Validation;
use Application\Entity\Db\VolumeHoraire;
use Application\Form\Contrat\Traits\ModeleFormAwareTrait;
use Application\Form\Intervenant\Traits\ContratRetourAwareTrait;
use Application\Processus\Traits\ContratProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContratServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ModeleContratServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\TauxHoraireHETDServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Controller\Plugin\Upload\UploaderPlugin;
use UnicaenApp\Util;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Entity\Db\Contrat;
use Zend\View\Model\JsonModel;
use BjyAuthorize\Exception\UnAuthorizedException;
use Zend\View\Renderer\PhpRenderer;

/**
 * Description of ContratController
 *
 * @method UploaderPlugin uploader()
 *
 */
class ContratController extends AbstractController
{
    use ContextServiceAwareTrait;
    use ContratServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use ContratRetourAwareTrait;
    use ParametresServiceAwareTrait;
    use ContratProcessusAwareTrait;
    use TauxHoraireHETDServiceAwareTrait;
    use DossierServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use ModeleContratServiceAwareTrait;
    use ModeleFormAwareTrait;

    private $viewRenderer;



    public function __construct(PhpRenderer $viewRenderer)
    {

        $this->viewRenderer = $viewRenderer;
    }



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Contrat::class,
            Service::class,
            VolumeHoraire::class,
            Validation::class,
        ]);
    }



    /**
     * Point d'entrée sur les contrats/avenants.
     *
     * @return array
     */
    public function indexAction()
    {
        $this->initFilters();

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }
        $structure = $role->getStructure();

        $title = "Contrat/avenants <small>{$intervenant}</small>";

        $sContrat = $this->getServiceContrat();
        $qb       = $sContrat->finderByIntervenant($intervenant);
        if ($structure) {
            $sContrat->finderByStructure($structure, $qb);
        }
        $contrats = $sContrat->getList($qb);

        /* Récupération des services par contrat et par structure (pour les non contractualisés) */
        $services = [
            'contractualises'     => [],
            'non-contractualises' => [],
        ];

        foreach ($contrats as $contrat) {
            $services['contractualises'][$contrat->getId()] = $this->getProcessusContrat()->getServices($intervenant, $contrat, $role->getStructure());
        }

        $nc = $this->getProcessusContrat()->getServices($intervenant, null, $role->getStructure());
        foreach ($nc as $service) {
            $sid = $service->getElementPedagogique()->getStructure()->getId();
            if (!isset($services['non-contractualises'][$sid])) {
                $services['non-contractualises'][$sid] = [];
            }
            $services['non-contractualises'][$sid][] = $service;
        }

        return compact('title', 'intervenant', 'contrats', 'services');
    }



    public function creerAction()
    {
        $this->initFilters();

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $structure = $this->getEvent()->getParam('structure');
        /* @var $structure Structure */

        if (!$intervenant) {
            throw new \LogicException('L\'intervenant n\'est pas précisé');
        }

        if (!$structure) {
            throw new \LogicException('La structure n\'est pas précisée');
        }

        $contrat = $this->getProcessusContrat()->creer($intervenant, $structure);

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_CREATION)) {
            $this->flashMessenger()->addSuccessMessage("La création de contrat/avenant pour $intervenant n'est pas possible.");
        } else {
            try {
                $this->getProcessusContrat()->enregistrer($contrat);
                $this->updateTableauxBord($contrat->getIntervenant());
                $this->flashMessenger()->addSuccessMessage('Le projet ' . ($contrat->estUnAvenant() ? 'd\'avenant' : 'de contrat') . ' a bien été créé.');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $intervenant->getRouteParam()]);
    }



    /**
     * Suppression d'un projet de contrat/avenant par la composante d'intervention.
     *
     * @return \Zend\View\Model\ViewModel
     * @throws \LogicException
     */
    public function supprimerAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        $contratToString = lcfirst($contrat->toString(true, true));

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_SUPPRESSION)) {
            throw new \LogicException("La suppression $contratToString n'est pas possible.");
        }

        if ($this->getRequest()->isPost()) {
            try {
                $this->getProcessusContrat()->supprimer($contrat);
                $this->updateTableauxBord($contrat->getIntervenant());
                $this->flashMessenger()->addSuccessMessage("Suppression $contratToString effectuée avec succès.");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        };

        return new MessengerViewModel;
    }



    public function validerAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        //$form            = $this->getFormIntervenantContratValidation()->setContrat($contrat)->init2();
        //$contratToString = $contrat->toString(true, true);

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_VALIDATION)) {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de valider ce projet ' . ($contrat->estUnAvenant() ? 'd\'avenant' : 'de contrat'));

            //$form = null;

            return new MessengerViewModel;
        }

        if ($this->getProcessusContrat()->doitEtreRequalifie($contrat)) {
            $message = "<p><strong>NB :</strong> à l'issue de sa validation, " . lcfirst($contrat->toString(true)) .
                " deviendra un avenant car un contrat a déjà été validé par une autre composante.</p>" .
                "<p><strong>Vous devrez donc impérativement imprimer à nouveau le document !</strong></p>";
            $this->flashMessenger()->addWarningMessage($message);
        }

        if ($this->getRequest()->isPost()) {
            try {
                $this->getProcessusContrat()->valider($contrat);
                $this->updateTableauxBord($contrat->getIntervenant());

                $this->flashMessenger()->addSuccessMessage(
                    "Validation " . lcfirst($contrat->toString(true, true)) . " enregistrée avec succès."
                );
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return new MessengerViewModel;
    }



    /**
     * Dévalidation du contrat/avenant par la composante d'intervention.
     *
     * @return \Zend\View\Model\ViewModel
     * @throws \LogicException
     */
    public function devaliderAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        if ($this->isAllowed($contrat, Privileges::CONTRAT_DEVALIDATION)) {
            if ($this->getRequest()->isPost()) {
                try {
                    $this->getProcessusContrat()->devalider($contrat);
                    $this->updateTableauxBord($contrat->getIntervenant());

                    $this->flashMessenger()->addSuccessMessage(
                        "Dévalidation " . lcfirst($contrat->toString(true, true)) . " effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de dévalider ' . ($contrat->estUnAvenant() ? 'cet avenant' : 'ce contrat'));
        }

        return new MessengerViewModel;
    }



    /**
     * Saisie de la date de retour du contrat/avenant signé par l'intervenant.
     *
     * @return \Zend\View\Model\ViewModel
     * @throws \LogicException
     */
    public function saisirRetourAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        $done = false;

        $form            = $this->getFormIntervenantContratRetour()->setContrat($contrat)->init2();
        $contratToString = $contrat->toString(true, true);
        $title           = "Retour $contratToString signé <small>" . $contrat->getIntervenant() . "</small>";


        if (!$this->isAllowed($contrat, Privileges::CONTRAT_SAISIE_DATE_RETOUR_SIGNE)) {
            throw new UnAuthorizedException('Vous n\'avez pas les droits requis pour saisir la date de retour du contrat signé.');
        }

        $form->bindRequestSave($contrat, $this->getRequest(), function () use ($contrat, $contratToString) {

            $this->getServiceContrat()->save($contrat);
            $this->updateTableauxBord($contrat->getIntervenant());
            $this->flashMessenger()->addSuccessMessage(
                "Saisie du retour $contratToString signé enregistrée avec succès."
            );
        });

        return compact('form', 'done', 'title');
    }



    public function exporterAction()
    {
        /* @var Contrat $contrat */
        $contrat = $this->getEvent()->getParam('contrat');

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_EXPORT)) {
            throw new UnAuthorizedException("Génération du contrat interdite.");
        }

        $this->getServiceModeleContrat()->generer($contrat);
        die();
    }



    public function envoyerMailAction()
    {
        $contrat = $this->getEvent()->getParam('contrat');

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_EXPORT)) {
            throw new UnAuthorizedException("Interdiction d'envoyer le contrat par email");
        }

        if (!empty($contrat->getIntervenant()->getEmail())) {
            $html    = $this->viewRenderer->render('application/contrat/mail/contrat', [
                'contrat' => $contrat,
            ]);
            $message = $this->getServiceModeleContrat()->prepareMail($contrat, $html);
            $this->mail()->send($message);
        }

        return $this->getResponse();
    }



    /**
     * Dépôt du contrat signé.
     *
     * @return Response
     */
    public function deposerFichierAction()
    {
        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_AJOUTER_FICHIER)) {
            throw new UnAuthorizedException('Vous n\'avez pas de droit de déposer ce fichier');
        }

        $result = $this->uploader()->upload();

        if ($result instanceof JsonModel) {
            return $result;
        }
        if (is_array($result)) {
            $this->getServiceContrat()->creerFichiers($result['files'], $contrat);
            $this->updateTableauxBord($contrat->getIntervenant());
        }

        return $this->redirect()->toRoute('contrat/lister-fichier', [], [], true);
    }



    /**
     * Listing des fichiers déposés pour le contrat.
     *
     * @return aarray
     */
    public function listerFichierAction()
    {
        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_LISTER_FICHIERS)) {
            throw new UnAuthorizedException('Vous n\'avez pas de droit de visualiser les fichierzs dépôsés');
        }

        return [
            'contrat' => $contrat,
        ];
    }



    /**
     * Téléchargement d'un fichier.
     *
     * @throws UnAuthorizedException
     */
    public function telechargerFichierAction()
    {
        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_VISUALISATION)) {
            throw new UnAuthorizedException('Vous n\'avez pas de droit de télécharger ce fichier');
        }

        $fichier = $this->getEvent()->getParam('fichier');

        $this->uploader()->download($fichier);
    }



    /**
     * Suppression d'un fichier déposé.
     *
     * @return Response
     * @throws UnAuthorizedException
     */
    public function supprimerFichierAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        $fichier = $this->getEvent()->getParam('fichier');

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_SUPPRIMER_FICHIER)) {
            throw new UnAuthorizedException('Vous n\'avez pas de droit de supprimer ce fichier');
        }

        if ($fichier) {
            $contrat->removeFichier($fichier);
            $this->em()->remove($fichier);
        }

        $this->em()->flush();
        $this->updateTableauxBord($contrat->getIntervenant());

        return $this->redirect()->toRoute('contrat/lister-fichier', ['contrat' => $contrat->getId()], [], true);
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'formule',
            'contrat',
        ], $intervenant);
    }



    public function modelesListeAction()
    {
        $modeles = $this->getServiceModeleContrat()->getList();

        return compact('modeles');
    }



    public function modelesEditerAction()
    {
        /* @var $modeleContrat ModeleContrat */
        $modeleContrat = $this->getEvent()->getParam('modeleContrat');

        $form = $this->getFormContratModele();

        if (!$modeleContrat) {
            $title         = 'Ajout d\'un modèle de contrat';
            $modeleContrat = new ModeleContrat();
        } else {
            $title = 'Modification d\'un modèle de contrat';
        }

        $form->bindRequestSave($modeleContrat, $this->getRequest(), function (ModeleContrat $mc) {
            try {
                $this->getServiceModeleContrat()->save($mc);
                $this->flashMessenger()->addSuccessMessage('Modèle de contrat bien enregistré');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function modelesSupprimerAction()
    {
        /* @var $modeleContrat ModeleContrat */
        $modeleContrat = $this->getEvent()->getParam('modeleContrat');

        try {
            $this->getServiceModeleContrat()->delete($modeleContrat);
            $this->flashMessenger()->addSuccessMessage("Modèle de contrat supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }



    public function modelesTelechargerAction()
    {
        /* @var $modeleContrat ModeleContrat */
        $modeleContrat = $this->getEvent()->getParam('modeleContrat');

        $fichier = new Fichier();
        $fichier->setNom(Util::reduce($modeleContrat->getLibelle()) . '.odt');
        $fichier->setTypeMime('application/vnd.oasis.opendocument.text');
        if ($modeleContrat->hasFichier()) {
            $fichier->setContenu(stream_get_contents($modeleContrat->getFichier(), -1, 0));
        } else {
            $fichier->setContenu(file_get_contents($this->getServiceModeleContrat()->getModeleGeneriqueFile()));
        }
        $this->uploader()->download($fichier);
    }
}