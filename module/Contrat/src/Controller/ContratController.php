<?php

namespace Contrat\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Parametre;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Contrat\Assertion\ContratAssertion;
use Contrat\Entity\Db\Contrat;
use Contrat\Entity\Db\ContratServiceListe;
use Contrat\Form\ContratRetourFormAwareTrait;
use Contrat\Form\EnvoiMailContratFormAwareTrait;
use Contrat\Processus\ContratProcessusAwareTrait;
use Contrat\Service\ContratServiceAwareTrait;
use Contrat\Service\ContratServiceListeServiceAwareTrait;
use DateTime;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Enseignement\Service\ServiceServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\NoteServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use Lieu\Entity\Db\Structure;
use LogicException;
use PHPUnit\Exception;
use Service\Entity\Db\TypeService;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Unicaen\OpenDocument\Document;
use UnicaenApp\Controller\Plugin\Upload\UploaderPlugin;
use UnicaenApp\View\Model\MessengerViewModel;

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
    use ContratRetourFormAwareTrait;
    use ParametresServiceAwareTrait;
    use ContratProcessusAwareTrait;
    use DossierServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use EnvoiMailContratFormAwareTrait;
    use NoteServiceAwareTrait;
    use ContratServiceListeServiceAwareTrait;


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
            throw new LogicException('Intervenant non précisé ou inexistant');
        }
        $structure = $role->getStructure();

        $title = "Contrat/avenants <small>{$intervenant}</small>";


        $avenantResult = $this->getServiceParametres()->get('avenant');
        switch ($avenantResult) {
            case Parametre::AVENANT_AUTORISE :
                $avenant_param = 1;
            break;
            case Parametre::AVENANT_STRUCT :
                $avenant_param = 0;
            break;
            default :
                $avenant_param = -1;
        }

        $sContrat     = $this->getServiceContrat();
        $qbTest       = $sContrat->finderByIntervenant($intervenant);
        $contratsTest = $sContrat->getList($qbTest);
        if (empty($contratsTest)) {
            $hasContrat = false;
        } else {
            $hasContrat = true;
        }


        $sContrat = $this->getServiceContrat();
        $qb       = $sContrat->finderByIntervenant($intervenant);
        if ($structure && $avenant_param >= 0) {
            $qb->andWhere();
            $sContrat->finderByStructure($structure, $qb);
        }

        $contrats = $sContrat->getList($qb);

        $services = [
            'contractualises'     => [],
            'non-contractualises' => [],
        ];

        foreach ($contrats as $contrat) {
            $services['contractualises'][$contrat->getId()] = [];
        }

        $sContratListe     = $this->getServiceContratServiceListe();
        $needToSeeResult   = $sContratListe->getListeServiceContratIntervenant($intervenant);
        $missionNotContrat = [];

        /** @var ContratServiceListe $serviceTest */
        foreach ($needToSeeResult as $serviceTest) {
            if ($serviceTest->getContrat() != null) {
                if (!isset($services['contractualises'][$serviceTest->getContrat()->getId()][$serviceTest->getTypeService()->getCode()])) {
                    $services['contractualises'][$serviceTest->getContrat()->getId()][$serviceTest->getTypeService()->getCode()] = [];
                }
                $services ['contractualises'][$serviceTest->getContrat()->getId()][$serviceTest->getTypeService()->getCode()][] = $serviceTest;
            } else {
                if (!isset($services['non-contractualises'][$serviceTest->getStructure()->getId()])) {
                    $services['non-contractualises'][$serviceTest->getStructure()->getId()] = [];
                    foreach (TypeService::CODES as $code) {
                        $services['non-contractualises'][$serviceTest->getStructure()->getId()][$code] = [];
                    }
                }
                $services ['non-contractualises'][$serviceTest->getStructure()->getId()][$serviceTest->getTypeService()->getCode()][$serviceTest->getId()] = $serviceTest;
                if ($serviceTest->getTypeService()->getCode() == TypeService::CODE_MISSION) {
                    $mission = $this->getServiceContrat()->getContratInitialMission($serviceTest->getMission());
                    if ($mission == null) {
                        $missionNotContrat[$serviceTest->getMission()->getId()] = true;
                    }
                }
            }
        }


        //Récupération email intervenant (Perso puis unicaen)
        $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);
        $emailPerso         = ($dossierIntervenant) ? $dossierIntervenant->getEmailPerso() : '';
        $emailIntervenant   = (!empty($emailPerso)) ? $emailPerso : $intervenant->getEmailPro();


        $contratDirectResult        = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect              = ($contratDirectResult == Parametre::CONTRAT_DIRECT);
        $contratSignatureActivation = false;
        if (!empty($this->getServiceParametres()->get('signature_electronique_parapheur'))
            && $intervenant->getStatut()->isContratSignatureActivation()) {
            $contratSignatureActivation = true;
        }


        return compact('title', 'intervenant', 'contrats', 'services', 'emailIntervenant', 'hasContrat', 'avenant_param', 'contratDirect', 'missionNotContrat', 'contratSignatureActivation');
    }



    public function creerAction()
    {
        $this->initFilters();

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $serviceNC = $this->getEvent()->getParam('serviceNC');
        $structure = $this->getEvent()->getParam('structure');
        /* @var $structure Structure */

        $contratDirectResult = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect       = ($contratDirectResult == Parametre::CONTRAT_DIRECT);

        if (!$intervenant) {
            throw new LogicException('L\'intervenant n\'est pas précisé');
        }

//        if (!$structure) {
//            throw new LogicException('La structure n\'est pas précisée');
//        }

        $contrat = $this->getProcessusContrat()->creer($intervenant, $structure);


        if (!$this->isAllowed($contrat, Privileges::CONTRAT_CREATION)) {
            $this->flashMessenger()->addSuccessMessage("La création de contrat/avenant pour $intervenant n'est pas possible.");
        } else {
            try {

                $this->getProcessusContrat()->enregistrer($contrat);
                if ($contratDirect) {
                    $this->getProcessusContrat()->valider($contrat);
                }

                $this->updateTableauxBord($contrat->getIntervenant());
                if ($contratDirect) {
                    $this->flashMessenger()->addSuccessMessage(($contrat->estUnAvenant() ? 'L\'avenant' : 'Le contrat') . ' a bien été créé.');
                } else {
                    $this->flashMessenger()->addSuccessMessage('Le projet ' . ($contrat->estUnAvenant() ? 'd\'avenant' : 'de contrat') . ' a bien été créé.');
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $intervenant->getId()]);
    }



    public function creerMissionAction()
    {
        $this->initFilters();

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $mission   = $this->getEvent()->getParam('mission');
        $structure = $mission->getStructure();
        /* @var $structure Structure */

        $contratDirectResult = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect       = ($contratDirectResult == Parametre::CONTRAT_DIRECT);

        if (!$intervenant) {
            throw new LogicException('L\'intervenant n\'est pas précisé');
        }

//        if (!$structure) {
//            throw new LogicException('La structure n\'est pas précisée');
//        }

        $contrat = $this->getProcessusContrat()->creer($intervenant, $structure, $mission);

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_CREATION)) {
            $this->flashMessenger()->addSuccessMessage("La création de contrat/avenant pour $intervenant n'est pas possible.");
        } else {
            try {

                $this->getProcessusContrat()->enregistrer($contrat, $mission);
                if ($contratDirect) {
                    $this->getProcessusContrat()->valider($contrat);
                }

                $this->updateTableauxBord($contrat->getIntervenant());
                if ($contratDirect) {
                    $this->flashMessenger()->addSuccessMessage(($contrat->estUnAvenant() ? 'L\'avenant' : 'Le contrat') . ' a bien été créé.');
                } else {
                    $this->flashMessenger()->addSuccessMessage('Le projet ' . ($contrat->estUnAvenant() ? 'd\'avenant' : 'de contrat') . ' a bien été créé.');
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $intervenant->getId()]);
    }



    /**
     * Suppression d'un projet de contrat/avenant par la composante d'intervention.
     *
     * @return \Laminas\View\Model\ViewModel
     * @throws LogicException
     */
    public function supprimerAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        $contratToString = lcfirst($contrat->toString(true, true));

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_SUPPRESSION)) {
            throw new LogicException("La suppression $contratToString n'est pas possible.");
        }

        if ($this->getRequest()->isPost()) {
            try {
                $contratDirectResult = $this->getServiceParametres()->get('contrat_direct');
                $contratDirect       = ($contratDirectResult == Parametre::CONTRAT_DIRECT);
                if ($contratDirect && $contrat->getValidation()) {
                    $this->getProcessusContrat()->devalider($contrat);
                }
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
     * @return \Laminas\View\Model\ViewModel
     * @throws LogicException
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
     * @return \Laminas\View\Model\ViewModel
     * @throws LogicException
     */
    public function saisirRetourAction()
    {
        $this->initFilters();

        $contrat = $this->getEvent()->getParam('contrat');
        /* @var $contrat Contrat */

        $done = false;

        $form            = $this->getFormIntervenantContratRetourForm()->setContrat($contrat)->init2();
        $contratToString = $contrat->toString(true, true);
        $title           = "Retour $contratToString signé <small>" . $contrat->getIntervenant() . "</small>";


        if (!$this->isAllowed($contrat, Privileges::CONTRAT_SAISIE_DATE_RETOUR_SIGNE)) {
            throw new UnAuthorizedException('Vous n\'avez pas les droits requis pour saisir la date de retour du contrat signé.');
        }
        $canSaisieDateSigne = true;

        $contratDateSansFichierResult = $this->getServiceParametres()->get('contrat_date');
        $contratDateSansFichier       = ($contratDateSansFichierResult == Parametre::CONTRAT_DATE);

        if ($contrat->getDateRetourSigne() != null || $contrat->getFichier()->count() > 0 || $contratDateSansFichier) {
            $form->bindRequestSave($contrat, $this->getRequest(), function() use ($contrat, $contratToString){

                $this->getServiceContrat()->save($contrat);
                $this->updateTableauxBord($contrat->getIntervenant());
                $this->flashMessenger()->addSuccessMessage(
                    "Saisie du retour $contratToString signé enregistrée avec succès."
                );
            });
        } else {
            $canSaisieDateSigne = false;
        }

        $contratDateResult = $this->getServiceParametres()->get('contrat_date');
        $contratDate       = ($contratDateResult == Parametre::CONTRAT_DATE);

        return compact('form', 'done', 'title', 'canSaisieDateSigne', 'contratDate');
    }



    public function exporterAction()
    {
        /* @var Contrat $contrat */
        $contrat = $this->getEvent()->getParam('contrat');

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_EXPORT)) {
            throw new UnAuthorizedException("Génération du contrat interdite.");
        }

        $this->getServiceContrat()->generer($contrat);
        die();
    }



    public function envoyerMailAction()
    {
        /** @var Contrat $contrat */
        $contrat = $this->getEvent()->getParam('contrat');


        $title = 'Envoi du contrat à l\'intervenant';

        if (!$this->isAllowed($contrat, ContratAssertion::PRIV_EXPORT)) {
            throw new UnAuthorizedException("Interdiction d'envoyer le contrat par email");
        }
        $intervenant        = $contrat->getIntervenant();
        $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);
        $emailDossierPerso  = ($dossierIntervenant) ? $dossierIntervenant->getEmailPerso() : '';
        $emailIntervenant   = (!empty($emailDossierPerso)) ? $emailDossierPerso : $intervenant->getEmailPro();
        $emailExpediteur    = (!empty($this->getServiceParametres()->get('contrat_mail_expediteur'))) ? $this->getServiceParametres()->get('contrat_mail_expediteur') : $this->getServiceContext()->getUtilisateur()->getEmail();
        $form               = $this->getFormContratEnvoiMailContrat();
        $form->get('destinataire-mail')->setValue($emailIntervenant);
        $form->get('destinataire-mail-hide')->setValue($emailIntervenant);
        $form->get('expediteur-mail')->setValue($emailExpediteur);


        if ($this->getRequest()->isPost()) {
            if (!empty($emailIntervenant)) {
                try {
                    //Utilisation ici du parametre email
                    $html = $this->getServiceParametres()->get('contrat_modele_mail');
                    //Ajout pour transformer les sauts de lignes en html <br/>
                    $html = nl2br($html);
                    //Personnalisation des variables
                    if ($contrat->getIntervenant()->getCivilite() != null) {
                        $vIntervenant = $contrat->getIntervenant()->getCivilite()->getLibelleCourt() . " " . $contrat->getIntervenant()->getNomUsuel();
                    } elseif ($dossierIntervenant != null && $dossierIntervenant->getCivilite() != null) {
                        $vIntervenant = $dossierIntervenant->getCivilite()->getLibelleCourt() . " " . $contrat->getIntervenant()->getNomUsuel();
                    } else {
                        $vIntervenant = $contrat->getIntervenant()->getNomUsuel();
                    }
                    $vUtilisateur = $this->getServiceContext()->getUtilisateur()->getDisplayName();
                    $vAnnee       = $this->getServiceContext()->getAnnee()->getLibelle();
                    $html         = str_replace([':intervenant', ':utilisateur', ':annee'], [$vIntervenant, $vUtilisateur, $vAnnee], $html);
                    $subject      = $this->getServiceParametres()->get('contrat_modele_mail_objet');
                    $subject      = str_replace(':intervenant', $vIntervenant, $subject);
                    $from         = $this->getRequest()->getPost('expediteur-mail');
                    $to           = $this->getRequest()->getPost('destinataire-mail-hide');
                    $cci          = $this->getRequest()->getPost('destinataire-cc-mail');

                    $message = $this->getProcessusContrat()->prepareMail($contrat, $html, $from, $to, $cci, $subject);
                    /*Create Note from email for this intervenant*/
                    $this->getServiceNote()->createNoteFromEmail($intervenant, $subject, $html);
                    $mail           = $this->mail()->send($message);
                    $dateEnvoiEmail = new DateTime();
                    $contrat->setDateEnvoiEmail($dateEnvoiEmail);
                    $this->getServiceContrat()->save($contrat);
                    $this->flashMessenger()->addSuccessMessage('Contrat bien envoyé par email à ' . $emailIntervenant);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }

            return $this->getResponse();
        }

        return compact('form', 'title');
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
     * @return array
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



    public function envoyerSignatureElectroniqueAction()
    {
        /*
         * 1 - On vérifie que l'on a bien le privilège pour envoyer dans le parapheur le contrat pour signature
         * 2 - On génére le PDF et on le stock dans contrat_fichier pour avoir le fichier physique sur le serveur
         * 3 - On récupére les informations des personnes signataires (intervenant, role devant signer le contrat pour ce statut)
         * 4 - On créer la signature electronique en envoyant le document dans le parapheur configuré
         * 5 - Un process doit vérfier régulièrement l'état d'avancement du contrat dans le parapheur
         * 6 - Si signature complète, on met à jour la date de retour signé sur le contrat pour pouvoir avancer dans le workflow
         * 7 - On récupère le document signé et on remplace le fichier dans contrat_fichier
         *
         * 2 -
         * */

        /**
         * @var Contrat $contrat
         */
        $contrat = $this->getEvent()->getParam('contrat');
        try {
            $this->getServiceContrat()->envoyerContratSignatureElectronique($contrat);
            $contratLibelle = "Contrat N°" . $contrat->getId();
            $this->flashMessenger()->addSuccessMessage($contratLibelle . " envoyé avec succés pour signature électronique");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $contrat->getIntervenant()->getId()], [], true);
    }



    public function supprimerSignatureElectroniqueAction()
    {
        //TODO : vérifier qu'on a bien le droit de supprimer cette signature, pour ce contrat
        $contrat        = $this->getEvent()->getParam('contrat');
        $libelleContrat = "Contrat N°" . $contrat->getId();
        try {
            if ($this->getServiceContrat()->supprimerSignatureElectronique($contrat)) {
                $this->flashMessenger()->addSuccessMessage("Signature électronique supprimée avec succés pour le " . $libelleContrat);
            } else {
                $this->flashMessenger()->addErrorMessage("Impossible de supprimer la signature électronique pour le " . $libelleContrat);
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }


        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $contrat->getIntervenant()->getId()], [], true);
    }



    public function rafraichirSignatureElectroniqueAction()
    {
        $contrat   = $this->getEvent()->getParam('contrat');
        $signature = $contrat->getSignature();
        if ($signature) {
            try {
                $this->getServiceContrat()->updateSignatureElectronique($contrat);
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $contrat->getIntervenant()->getId()], [], true);
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'formule',
            'contrat',
            'mission',
        ], $intervenant);
    }
}