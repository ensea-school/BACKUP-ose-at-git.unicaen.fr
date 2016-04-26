<?php

namespace Application\Controller;

use Application\Assertion\ContratAssertion;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Validation;
use Application\Entity\Db\VolumeHoraire;
use Application\Exception\DbException;
use Application\Form\Intervenant\Traits\ContratRetourAwareTrait;
use Application\Form\Intervenant\Traits\ContratValidationAwareTrait;
use Application\Processus\Traits\ContratProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContratAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\ParametresAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Constants;
use UnicaenApp\Controller\Plugin\Upload\UploaderPlugin;
use UnicaenApp\Exporter\Pdf;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Entity\Db\Contrat;
use Zend\View\Model\JsonModel;
use BjyAuthorize\Exception\UnAuthorizedException;

/**
 * Description of ContratController
 *
 * @method UploaderPlugin uploader()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratController extends AbstractController
{
    use ContextAwareTrait;
    use ContratAwareTrait;
    use ServiceServiceAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;
    use ContratValidationAwareTrait;
    use ContratRetourAwareTrait;
    use ParametresAwareTrait;
    use ContratProcessusAwareTrait;

    /**
     * @var Contrat
     */
    private $contrat;



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
        $structure   = $role->getStructure();

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
                $this->flashMessenger()->addSuccessMessage('Le projet ' . ($contrat->estUnAvenant() ? 'd\'avenant' : 'de contrat') . ' a bien été créé.');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
            }
        }

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $intervenant->getSourceCode()]);
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
                $this->flashMessenger()->addSuccessMessage("Suppression $contratToString effectuée avec succès.");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
            }
        };

        return new MessengerViewModel;
    }



    public function validerAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        $form            = $this->getFormIntervenantContratValidation()->setContrat($contrat)->init2();
        $contratToString = $contrat->toString(true, true);
        $title           = "Validation $contratToString <small>" . $contrat->getIntervenant() . "</small>";

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_VALIDATION)) {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas le droit de valider ce projet ' . ($contrat->estUnAvenant() ? 'd\'avenant' : 'de contrat'));
            $form = null;

            return compact('form', 'title');
        }

        if ($this->getProcessusContrat()->doitEtreRequalifie($contrat)) {
            $message = "<p><strong>NB :</strong> à l'issue de sa validation, " . lcfirst($contrat->toString(true)) .
                " deviendra un avenant car un contrat a déjà été validé par une autre composante.</p>" .
                "<p><strong>Vous devrez donc impérativement imprimer à nouveau le document !</strong></p>";
            $this->flashMessenger()->addWarningMessage($message);
        }

        $form->requestSave($this->getRequest(), function () use ($contrat) {
            try {
                $this->getProcessusContrat()->valider($contrat);

                $this->flashMessenger()->addSuccessMessage(
                    "Validation " . lcfirst($contrat->toString(true, true)) . " enregistrée avec succès."
                );
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
            }
        });

        return compact('form', 'title');
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

                    $this->flashMessenger()->addSuccessMessage(
                        "Dévalidation " . lcfirst($contrat->toString(true, true)) . " effectuée avec succès."
                    );
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
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
            $this->flashMessenger()->addSuccessMessage(
                "Saisie du retour $contratToString signé enregistrée avec succès."
            );
        });

        return compact('form', 'done', 'title');
    }



    /**
     *
     */
    public function exporterAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        $intervenant = $contrat->getIntervenant();

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_VISUALISATION)) {
            throw new UnAuthorizedException("Interdit !");
        }

        $estUnAvenant    = $contrat->estUnAvenant();
        $contratToString = (string)$contrat;
        $nomIntervenant  = (string)$intervenant;
        $dateNaissance   = $intervenant->getDateNaissanceToString();
        $estATV          = $intervenant->getStatut()->getTemAtv();
        $estUnProjet     = $contrat->getValidation() ? false : true;
        $contratIniModif = $estUnAvenant && $contrat->getContrat()->getStructure() === $contrat->getStructure() ? true : false;
        $dateSignature   = $estUnProjet ? $contrat->getHistoCreation() : $contrat->getValidation()->getHistoCreation();
        $servicesRecaps  = $this->getProcessusContrat()->getServicesRecaps($contrat); // récap de tous les services au sein de la structure d'ens
        $totalHETD       = $contrat->getTotalHetd() ?: $this->getProcessusContrat()->getIntervenantTotalHetd($intervenant);

        if ($intervenant->getDossier()) {
            $adresseIntervenant    = $intervenant->getDossier()->getAdresse();
            $numeroINSEE           = $intervenant->getDossier()->getNumeroInsee();
            $nomCompletIntervenant = $intervenant->getDossier()->getCivilite() . ' ' . $nomIntervenant;
        } else {
            $adresseIntervenant    = $intervenant->getAdressePrincipale(true);
            $numeroINSEE           = $intervenant->getNumeroInsee() . ' ' . $intervenant->getNumeroInseeCle();
            $nomCompletIntervenant = $intervenant->getCivilite() . ' ' . $nomIntervenant;
        }

        $fileName = sprintf("contrat_%s_%s_%s.pdf",
            $contrat->getStructure()->getSourceCode(),
            $intervenant->getNomUsuel(),
            $intervenant->getSourceCode());

        $variables = [
            'estUnAvenant'            => $estUnAvenant,
            'estUnProjet'             => $estUnProjet,
            'contratIniModif'         => $contratIniModif,
            'etablissement'           => $this->getServiceParametres()->get('contrat_etablissement'),
            'etablissementRepresente' => $this->getServiceParametres()->get('contrat_etablissement_represente'),
            'civilitePresident'       => $this->getServiceParametres()->get('contrat_civilite_president'),
            'lieuSignature'           => $this->getServiceParametres()->get('contrat_lieu_signature'),
            'nomIntervenant'          => $nomIntervenant,
            'f'                       => $intervenant->estUneFemme(),
            'dateNaissance'           => $dateNaissance,
            'adresseIntervenant'      => nl2br($adresseIntervenant),
            'numeroINSEE'             => $numeroINSEE,
            'estATV'                  => $estATV,
            'nomCompletIntervenant'   => $nomCompletIntervenant,
            'annee'                   => $intervenant->getAnnee(),
            'dateSignature'           => $dateSignature->format(Constants::DATE_FORMAT),
            'servicesRecaps'          => $servicesRecaps,
            'totalHETD'               => \UnicaenApp\Util::formattedFloat($totalHETD, \NumberFormatter::DECIMAL, 2),
        ];

        // Création du pdf, complétion et envoi au navigateur
        $exp = new Pdf($this->getServiceLocator()->get('view_manager')->getRenderer());
        $exp->setHeaderSubtitle($contratToString)
            ->setMarginBottom(25)
            ->setMarginTop(25);
        if ($estUnProjet) {
            $exp->setWatermark("Projet");
        }

        $variables['mentionRetourner'] = "EXEMPLAIRE À CONSERVER";
        $exp->addBodyScript('application/contrat/contrat-pdf.phtml', false, $variables);

        $variables['mentionRetourner'] = "EXEMPLAIRE À RETOURNER SIGNÉ";
        $exp->addBodyScript('application/contrat/contrat-pdf.phtml', true, $variables, 1);

        $exp->export($fileName, Pdf::DESTINATION_BROWSER_FORCE_DL);
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

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_AJOUTER_FICHIER)){
            throw new UnAuthorizedException('Vous n\'avez pas de droit de déposer ce fichier');
        }

        $result = $this->uploader()->upload();

        if ($result instanceof JsonModel) {
            return $result;
        }
        if (is_array($result)) {
            $this->getServiceContrat()->creerFichiers($result['files'], $contrat);
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

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_LISTER_FICHIERS)){
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

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_VISUALISATION)){
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

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_SUPPRIMER_FICHIER)){
            throw new UnAuthorizedException('Vous n\'avez pas de droit de supprimer ce fichier');
        }

        if ($fichier) {
            $contrat->removeFichier($fichier);
            $this->em()->remove($fichier);
        }

        $this->em()->flush();

        return $this->redirect()->toRoute('contrat/lister-fichier', ['contrat' => $contrat->getId()], [], true);
    }

}
