<?php

namespace Contrat\Controller;

use Administration\Entity\Db\Parametre;
use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\UnAuthorizedException;
use Contrat\Assertion\ContratAssertion;
use Contrat\Entity\Db\Contrat;
use Contrat\Form\ContratRetourFormAwareTrait;
use Contrat\Form\EnvoiMailContratFormAwareTrait;
use Contrat\Processus\ContratProcessusAwareTrait;
use Contrat\Service\ContratServiceAwareTrait;
use Contrat\Service\ContratServiceListeServiceAwareTrait;
use Contrat\Service\TblContratServiceAwareTrait;
use DateTime;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Enseignement\Service\ServiceServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\NoteServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use LogicException;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\Controller\Plugin\Upload\UploaderPlugin;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenSignature\Entity\Db\Process;
use UnicaenSignature\Entity\Db\ProcessStep;
use UnicaenSignature\Service\ProcessServiceAwareTrait;
use Workflow\Entity\Db\Validation;
use Workflow\Service\WorkflowServiceAwareTrait;

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
    use TblContratServiceAwareTrait;
    use ProcessServiceAwareTrait;
    use MailServiceAwareTrait;

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



    public function indexAction()
    {
        $this->initFilters();

        $intervenant = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new LogicException('Intervenant non précisé ou inexistant');
        }
        $structure = $this->getServiceContext()->getStructure();

        $title = "Contrat/avenants <small>{$intervenant}</small>";


        $contrats = $this->getServiceTblContrat()->getContratsByIntervenant($intervenant, $structure);


        $contratsNonContractualises = [];
        $contratsContractualises    = [];
        $isMission = 0;
        foreach ($contrats as $contrat) {
            if (empty($contrat->getContrat())) {
                $contratsNonContractualises[$contrat->getUuid()] = $contrat;
            } else {
                $contratsContractualises[$contrat->getUuid()] = $contrat;
            }
            if (!empty($contrat->getTypesMissionLibelles())) {
                $isMission = 1;
            }

        }

        $contratDirectResult        = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect              = ($contratDirectResult == Parametre::CONTRAT_DIRECT);
        $contratSignatureActivation = false;
        $infosSignature             = [];
        $libelleCircuitSignature    = null;
        if (!empty($this->getServiceParametres()->get('signature_electronique_parapheur'))
            && $intervenant->getStatut()->getContratEtatSortie()?->isSignatureActivation()
            && $intervenant->getStatut()->getContratEtatSortie()?->getSignatureCircuit()) {
            $contratSignatureActivation = true;
            $libelleCircuitSignature    = $intervenant->getStatut()->getContratEtatSortie()->getSignatureCircuit()->getLabel();

            /**
             * @var Contrat     $contrat
             * @var Process     $process
             * @var ProcessStep $step
             */
            //On récupère les informations du process
            $qb       = $this->getServiceContrat()->finderByIntervenant($intervenant);
            $contrats = $this->getServiceContrat()->getList($qb);
            foreach ($contrats as $keyContrat => $contrat) {
                if ($contrat->getProcessSignature()) {
                    $infosSignature[$keyContrat] = [];
                    $this->em()->refresh($contrat);
                    $process = $contrat->getProcessSignature();
                    if (!empty($process)) {
                        $infosSignature[$keyContrat]['processSignature']  = $this->getProcessService()->getInfosProcess($process);
                        $infosSignature[$keyContrat]['urlFichierContrat'] = $this->getServiceContrat()->getUrlSignedContrat($contrat);
                    }
                }
            }
        }
        //Récupération email intervenant (Perso puis unicaen)
        $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);
        $emailPerso         = ($dossierIntervenant) ? $dossierIntervenant->getEmailPerso() : '';
        $emailIntervenant   = (!empty($emailPerso)) ? $emailPerso : $intervenant->getEmailPro();

        return compact(
            'title',
            'intervenant',
            'contratsNonContractualises',
            'contratsContractualises',
            'emailIntervenant',
            'contratDirect',
            'contratSignatureActivation',
            'infosSignature',
            'libelleCircuitSignature',
            'isMission',
        );
    }



    public function creerAction()
    {
        $this->initFilters();

        $intervenant = $this->getEvent()->getParam('intervenant');
        $uuid        = $this->params()->fromRoute('uuid');
        /* @var $intervenant Intervenant */

        $contratDirectResult = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect       = ($contratDirectResult == Parametre::CONTRAT_DIRECT);

        if (!$intervenant) {
            throw new LogicException('L\'intervenant n\'est pas précisé');
        }

        $volumeHorairesCreation = $this->getServiceTblContrat()->getInformationContratByUuid($uuid);
        $contrat                = new Contrat();
        $contrat                = $this->getProcessusContrat()->creer($contrat, $volumeHorairesCreation);


        if (!$this->isAllowed($contrat, Privileges::CONTRAT_CREATION)) {
            $this->flashMessenger()->addSuccessMessage("La création de contrat/avenant pour $intervenant n'est pas possible.");
        } else {
            try {

                $this->getProcessusContrat()->enregistrer($contrat, $uuid);
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

        $tblContratContrat = $this->getServiceTblContrat()->getInformationContratById($contrat->getId());
        $contrat               = $this->getProcessusContrat()->creer($contrat, $tblContratContrat);

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

        $contratDateSansFichierResult = $this->getServiceParametres()->get(Parametre::CONTRAT_DATE);
        $contratDateSansFichier       = ($contratDateSansFichierResult == Parametre::CONTRAT_DATE);

        if ($contrat->getDateRetourSigne() != null || $contrat->getFichier()->count() > 0 || $contratDateSansFichier) {
            $form->bindRequestSave($contrat, $this->getRequest(), function () use ($contrat, $contratToString) {

                $this->getServiceContrat()->save($contrat);
                $this->updateTableauxBord($contrat->getIntervenant());
                $this->flashMessenger()->addSuccessMessage(
                    "Saisie du retour $contratToString signé enregistrée avec succès."
                );
            });
        } else {
            $canSaisieDateSigne = false;
        }

        $contratDateResult = $this->getServiceParametres()->get(Parametre::CONTRAT_DATE);
        $contratDate       = ($contratDateResult == Parametre::CONTRAT_DATE);

        return compact('form', 'done', 'title', 'canSaisieDateSigne', 'contratDate');
    }



    public function exporterAction()
    {
        /* @var Contrat $contrat */
        $contrat = $this->getEvent()->getParam('contrat');
        //On teste si on a le droit de télécharger le contrat
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
                    $vLienContrat = '';
                    $urlContrat   = $this->url()->fromRoute('intervenant/contrat', ['intervenant' => $intervenant->getId()], ['force_canonical' => true], true);
                    $vLienContrat = '<a href="' . $urlContrat . '">' . $urlContrat . '</a>';
                    $html         = str_replace([':intervenant', ':utilisateur', ':annee', ':url'], [$vIntervenant, $vUtilisateur, $vAnnee, $vLienContrat], $html);
                    $subject      = $this->getServiceParametres()->get('contrat_modele_mail_objet');
                    $subject      = str_replace(':intervenant', $vIntervenant, $subject);
                    $from         = $this->getRequest()->getPost('expediteur-mail');
                    $to           = $this->getRequest()->getPost('destinataire-mail-hide');
                    $cci          = $this->getRequest()->getPost('destinataire-cc-mail');
                    $pieceJointe  = $this->getRequest()->getPost('contrat-piece-jointe');
                    $mail         = $this->getProcessusContrat()->prepareMail($contrat, $html, $from, $to, $cci, $subject, $pieceJointe);
                    /*Create Note from email for this intervenant*/
                    $this->getServiceNote()->createNoteFromEmail($intervenant, $subject, $html);
                    $this->getMailService()->send($mail);
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
        $contrat         = $this->getEvent()->getParam('contrat');
        $fichierDemandee = $this->getEvent()->getParam('fichier');

        if (!$this->isAllowed($contrat, Privileges::CONTRAT_VISUALISATION)) {
            throw new UnAuthorizedException('Vous n\'avez pas de droit de télécharger ce fichier');
        }

        $fichiersContrat = $contrat->getFichier();
        foreach ($fichiersContrat as $fichier) {
            if ($fichier->getId() == $fichierDemandee->getId()) {
                $this->uploader()->download($fichier);
            }
        }

        throw new \Exception('Le fichier n\'existe pas ou bien il appartient à un autre intervenant');

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



    public function creerProcessSignatureAction()
    {

        /**
         * @var Contrat $contrat
         */
        $contrat = $this->getEvent()->getParam('contrat');
        try {
            $this->getServiceContrat()->creerProcessContratSignatureElectronique($contrat);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $contrat->getIntervenant()->getId()], [], true);
    }



    public function supprimerProcessSignatureAction()
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
        //Mise à jour des tableaux de bord nécessaires
        /**
         * @var Contrat $contrat
         */
        $intervenant = $contrat->getIntervenant();
        $this->updateTableauxBord($intervenant);

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $contrat->getIntervenant()->getId()], [], true);
    }



    public function rafraichirProcessSignatureAction()
    {
        /**
         * @var Contrat $contrat
         */

        $contrat     = $this->getEvent()->getParam('contrat');
        $intervenant = $contrat->getIntervenant();
        try {

            $this->getServiceContrat()->rafraichirProcessSignatureElectronique($contrat);
            $this->updateTableauxBord($intervenant);
            $this->flashMessenger()->addSuccessMessage('Signature électronique mise à jour');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('intervenant/contrat', ['intervenant' => $contrat->getIntervenant()->getId()], [], true);
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $errors = $this->getServiceWorkflow()->calculerTableauxBord([
                                                                        TblProvider::FORMULE,
                                                                        TblProvider::CONTRAT,
                                                                    ], $intervenant);
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->flashMessenger()->addErrorMessage($error->getMessage());
            }
        }
    }

}
