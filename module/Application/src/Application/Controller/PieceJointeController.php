<?php

namespace Application\Controller;

use Application\Assertion\FichierAssertion;
use Application\Assertion\PieceJointeAssertion;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointe;
use Application\Form\Joindre;
use Application\Rule\Intervenant\PiecesJointesFourniesRule;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\PieceJointe as PieceJointeService;
use Application\Service\Process\PieceJointeProcess;
use Application\Service\Service;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Common\Exception\PieceJointe\AucuneAFournirException;
use Common\Exception\PieceJointe\PieceJointeException;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of UploadController
 *
 * @method Doctrine\ORM\EntityManager               em()
 * @method Application\Controller\Plugin\Context    context()
 * @method UnicaenApp\Controller\Plugin\AppInfos    appInfos() 
 * @method UnicaenApp\Controller\Plugin\Mail        mail()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PieceJointeController extends AbstractActionController implements ContextProviderAwareInterface, WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var string
     */
    private $title;
    
    /**
     * @var ViewModel
     */
    private $view;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->view = new ViewModel();
    }
    
    /**
     * 
     * @return ViewModel
     * @throws MessageException
     */
    public function indexAction()
    {
        $this->title = "Pièces justificatives <small>{$this->getIntervenant()}</small>";
        $role        = $this->getContextProvider()->getSelectedIdentityRole();

        $dossier = $this->getIntervenant()->getDossier();
        if (!$dossier) {
            throw new MessageException("L'intervenant {$this->getIntervenant()} n'a aucune donnée personnelle enregistrée.");
        }
        
        $typesPieceJointeStatut = $this->getPieceJointeProcess()->getTypesPieceJointeStatut();
        $piecesJointesFournies  = $this->getPieceJointeProcess()->getPiecesJointesFournies();
        $assertionPj            = (new PieceJointe())->setDossier($dossier); // entité transmise à l'assertion
        $formUpload             = $this->getFormJoindre();
        
        $this->view->setVariables(array(
            'intervenant'            => $this->getIntervenant(),
            'totalHeuresReelles'     => $this->getPieceJointeProcess()->getTotalHeuresReellesIntervenant(),
            'annee'                  => $this->getContextProvider()->getGlobalContext()->getAnnee(),
            'typesPieceJointeStatut' => $typesPieceJointeStatut,
            'piecesJointesFournies'  => $piecesJointesFournies,
            'dossier'                => $dossier,
            'assertionPj'            => $assertionPj,
            'formUpload'             => $formUpload,
            'role'                   => $role,
            'title'                  => $this->title,
        ));

        $this->statusAction();
        
        return $this->view;
    }
    
    /**
     * 
     * @return ViewModel
     */
    public function statusAction()
    {
        $messages = [];
        
        // recherche si toutes les PJ obligatoires ont été fournies
        $rule = $this->getRulePiecesJointesFournies();
        $complet = $rule->execute();
        if ($complet) {
            $messages['success'][] = "Toutes les pièces justificatives obligatoires ont été fournies.";
        }
        else {
            $messages['danger'][] = "Il manque des pièces justificatives obligatoires.";
        }
        
        // recherche si des PJ restent à valider
        $validations = [];
        $typesPieceJointeAttendus = $this->getPieceJointeProcess()->getTypesPieceJointeAttendus();
        $piecesJointesFournies    = $this->getPieceJointeProcess()->getPiecesJointesFournies();
        foreach ($piecesJointesFournies as $pj) { /* @var $pj PieceJointe */
            if ($pj->getValidation()) {
                $validations[] = $pj->getValidation();
            }
        }
        if (count($validations) < count($piecesJointesFournies)) {
            $messages['danger'][] = "Elles doivent encore être validées par votre composante.";
        }
        elseif (count($typesPieceJointeAttendus) === count($validations)) {
            $messages['success'][] = "Toutes les pièces justificatives fournies ont été validées par votre composante.";
        }
        
        $this->view->setVariables(array(
            'urlStatus' => $this->url()->fromRoute('piece-jointe/intervenant/status', [], [], true),
            'messages'  => $messages,
        ));

        return $this->view;
    }
    
    /**
     * Listing des fichiers déposés pour un type de pièce jointe donné.
     * 
     * @return type
     */
    public function listerAction()
    {
        $pj   = $this->getPieceJointeProcess()->getPieceJointeFournie($this->getTypePieceJointe());
        $form = $this->getFormJoindre();

        $form->setAttribute('action', $this->url()->fromRoute('piece-jointe/intervenant/ajouter', [], [], true));
               
        return [
            'typePieceJointe' => $this->getTypePieceJointe(),
            'pj'              => $pj,
            'form'            => $form,
        ];
    }
    
    /**
     * Dépôt d'un nouveau fichier pour un type de pièce jointe donné.
     * 
     * @return Response
     */
    public function ajouterAction()
    {
        $intervenant     = $this->getIntervenant();
        $typePieceJointe = $this->getTypePieceJointe();
        $form            = $this->getFormJoindre();
        $request         = $this->getRequest();
        
        if ($request->isXmlHttpRequest()) {
            $redir = $this->redirect()->toRoute('piece-jointe/intervenant/lister', [], [], true);
        }
        else {
            $redir = $this->redirect()->toRoute('piece-jointe/intervenant', [], [], true);
        }
        
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getServicePieceJointe()->createFromFiles($data['files'], $intervenant, $typePieceJointe);
                $this->notifyPiecesJointesFournies();
                
                return $redir;
            }
        }
        
        return $redir;
    }
    
    private function notifyPiecesJointesFournies()
    {
        // notif ssi toutes les PJ obligatoires ont été founies
        if (!$this->getRulePiecesJointesFournies()->execute()) { 
           return;
        }
        // pas de nottif si c'est un gestionnaire qui dépose des PJ
        if ($this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\ComposanteRole) {
            return;
        }
                
        // extraction des messages d'info (ce sont les feuilles du tableau)
        $messages = \UnicaenApp\Util::extractArrayLeafNodes($this->statusAction()->getVariable('messages'));
        
        // corps au format HTML
        $renderer = $this->getServiceLocator()->get('view_manager')->getRenderer();  /* @var $renderer \Zend\View\Renderer\PhpRenderer */
        $html = $renderer->render('application/piece-jointe/partial/mail', [
            'messages'    => $messages,
            'intervenant' => $this->getIntervenant(),
            'url'         => $this->url()->fromRoute('piece-jointe/intervenant', [], ['force_canonical' => true], true),
        ]);
        $part          = new \Zend\Mime\Part($html);
        $part->type    = \Zend\Mime\Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body          = new \Zend\Mime\Message();
        $body->addPart($part);
        
        // init
        $message       = new \Zend\Mail\Message();
        $message->setEncoding('UTF-8')
                ->setFrom('ne_pas_repondre@unicaen.fr', "Application " . ($app = $this->appInfos()->getNom()))
                ->setSubject(sprintf("[%s] Pièces justificatives déposées par %s", $app, $this->getIntervenant()))
                ->setBody($body);
        
        // destinataires
        $destinataires = $this->getPieceJointeProcess()->getDestinatairesMail();
        if (!$destinataires) {
            throw new RuntimeException(sprintf("Aucun destinataire trouvé concernant %s.", $this->getIntervenant()));
        }
        $message->addTo($destinataires);
        
        // envoi
        $this->mail()->send($message);
    }

    /**
     * @return PiecesJointesFourniesRule
     */
    private function getRulePiecesJointesFournies()
    {
        $rule = $this->getServiceLocator()->get('PiecesJointesFourniesRule');
        $rule
                ->setIntervenant($this->getIntervenant())
                ->setTotalHeuresReellesIntervenant($this->getPieceJointeProcess()->getTotalHeuresReellesIntervenant());
        
        return $rule;
    }
    
    /**
     * Téléchargement d'un fichier.
     * 
     * @throws UnAuthorizedException
     */
    public function telechargerAction()
    {
        $pj      = $this->getPieceJointe();
        $fichier = $this->getFichier();
        
        if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_TELECHARGER)) {
            throw new UnAuthorizedException("Interdit!");
        }
        
        $content     = stream_get_contents($fichier->getContenu());
        $contentType = $fichier->getType() ?: 'application/octet-stream';
        
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename=' . $fichier->getNom());
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($content));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        
        echo $content;
        exit;
    }
    
    /**
     * Suppression d'un fichier déposé.
     * 
     * NB: la pièce jointe est supprimée s'il ne reste plus aucun fichier déposé.
     * 
     * @return Response
     * @throws UnAuthorizedException
     */
    public function supprimerAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }
        
        $pj      = $this->getPieceJointe();
        $tpj     = $pj->getType();
        $fichier = $this->getFichier(false);
        
        if ($fichier) {
            if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_DELETE)) {
                throw new UnAuthorizedException("Suppression du fichier interdite!");
            }
            $pj->removeFichier($fichier);
            $this->em()->remove($fichier);
        }
        
        if (!count($pj->getFichier())) {
            if (!$this->isAllowed($pj, PieceJointeAssertion::PRIVILEGE_DELETE)) {
                throw new UnAuthorizedException("Suppression de la pièce jointe interdite!");
            }
            $this->em()->remove($pj);
        }
        
        $this->em()->flush();
            
        return $this->redirect()->toRoute('piece-jointe/intervenant/lister', ['typePieceJointe' => $tpj->getId()], [], true);
    }
    
    public function validerAction()
    {
        $pj      = $this->getPieceJointe();
        $tpj     = $pj->getType();
        $fichier = $this->getFichier(false);
        
        if ($fichier) {
            if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_VALIDER)) {
                throw new UnAuthorizedException('Validation du fichier suivant interdite : ' . $fichier);
            }
            $this->getServicePieceJointe()->validerFichier($fichier, $pj, $this->getIntervenant());
        }
        else {
            if (!$this->isAllowed($pj, PieceJointeAssertion::PRIVILEGE_VALIDER)) {
                throw new UnAuthorizedException('Validation de la pièce justificative suivante interdite : ' . $pj);
            }
            $this->getServicePieceJointe()->valider($pj, $this->getIntervenant());
            
            return $this->redirect()->toRoute('piece-jointe/intervenant', [], [], true);
        }
        
        return $this->redirect()->toRoute('piece-jointe/intervenant/lister', ['typePieceJointe' => $tpj->getId()], [], true);
    }
    
    public function devaliderAction()
    {
        $pj      = $this->getPieceJointe();
        $tpj     = $pj->getType();
        $fichier = $this->getFichier(false);
        
        if ($fichier) {
            if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_DEVALIDER)) {
                throw new UnAuthorizedException('Dévalidation du fichier suivant interdite : ' . $fichier);
            }
            $this->getServicePieceJointe()->devaliderFichier($fichier, $pj);
        }
        else {
            if (!$this->isAllowed($pj, PieceJointeAssertion::PRIVILEGE_DEVALIDER)) {
                throw new UnAuthorizedException('Dévalidation de la pièce jointe suivante interdite : ' . $pj);
            }
            $this->getServicePieceJointe()->devalider($pj);
            
            return $this->redirect()->toRoute('piece-jointe/intervenant', [], [], true);
        }
        
        return $this->redirect()->toRoute('piece-jointe/intervenant/lister', ['typePieceJointe' => $tpj->getId()], [], true);
    }
    
    /**
     * 
     * @return ViewModel
     */
    public function voirAction()
    {
        $pj  = $this->getPieceJointe();
        $vue = urldecode($this->params()->fromRoute('vue', $defaut= 'voir')); // vue à rendre
        
        if (!in_array($vue, [$defaut, 'partial/validation-bar'])) {
            $vue = $defaut;
        }
        
        $this->view
                ->setTemplate('application/piece-jointe/' . $vue)
                ->setVariables(array('pj' => $pj));

        return $this->view;
    }
    
    /**
     * 
     * @return ViewModel
     */
    public function voirTypeAction()
    {
        $pj  = $this->getPieceJointeProcess()->getPieceJointeFournie($this->getTypePieceJointe());
        $vue = urldecode($this->params()->fromRoute('vue', $defaut= 'voir')); // vue à rendre
        
        if (!in_array($vue, [$defaut, 'partial/validation-bar'])) {
            $vue = $defaut;
        }
        
        $this->view
                ->setTemplate('application/piece-jointe/' . $vue)
                ->setVariables(array('pj' => $pj));

        return $this->view;
    }
    
    /**
     * 
     * @return ViewModel
     */
    public function validationBarAction()
    {
        $pj = $this->getPieceJointe();
        
        $this->view->setVariables(array(
            'pj' => $pj,
        ));

        return $this->view;
    }
    
    protected $formJoindre;
    
    protected function getFormJoindre()
    {
        if (null === $this->formJoindre) {
            $this->formJoindre = new Joindre();
            $this->formJoindre->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        }
        return $this->formJoindre;
    }
    
    /**
     * @return array
     */
    private function getDestinatairesPiecesJointes()
    {
        $template      = '<a href="mailto:%s">%s</a>';
        $destinataires = [];
        
        if (($contactPj = $this->getIntervenant()->getStructure()->getContactPj())) {
            foreach (explode(',', $contactPj) as $mail) {
                $destinataires[] = sprintf($template, $mail = trim($mail), $mail);
            }
        }
        else {
            foreach ($this->getPieceJointeProcess()->getRolesDestinatairesPiecesJointes() as $r) {
                $mailto = sprintf($template, $mail = $r->getPersonnel()->getEmail(), $mail);
                $destinataires[] = sprintf("%s : %s", $r->getPersonnel(), $mailto);
            }
        }
        
        return $destinataires;
    }
    
    /**
     * @var IntervenantExterieur
     */
    private $intervenant;
    
    public function getIntervenant()
    {
        if (null == $this->intervenant) {
            $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
        }
        
        return $this->intervenant;
    }
    
    /**
     * @var TypePieceJointe
     */
    private $typePieceJointe;
    
    public function getTypePieceJointe()
    {
        if (null == $this->typePieceJointe) {
            $this->typePieceJointe = $this->context()->mandatory()->typePieceJointeFromRoute();
        }
        
        return $this->typePieceJointe;
    }
    
    /**
     * @var PieceJointe
     */
    private $pieceJointe;
    
    /**
     * @return PieceJointe
     */
    public function getPieceJointe()
    {
        if (null == $this->pieceJointe) {
            $this->pieceJointe = $this->context()->mandatory()->pieceJointeFromRoute();
        }
        
        return $this->pieceJointe;
    }
    
    /**
     * @var Fichier
     */
    private $fichier;
    
    /**
     * @return Fichier
     */
    public function getFichier($mandatory = true)
    {
        if (null == $this->fichier) {
            $this->fichier = $this->context()->mandatory($mandatory)->fichierFromRoute();
        }
        
        return $this->fichier;
    }
    
    /**
     * @var PieceJointeProcess
     */
    private $process;
    
    /**
     * @return PieceJointeProcess
     */
    private function getPieceJointeProcess()
    {
        if (null === $this->process) {
            $this->process = $this->getServiceLocator()->get('ApplicationPieceJointeProcess');
        }
        
        try {
            $this->process->setIntervenant($this->getIntervenant());
        }
        catch (AucuneAFournirException $exc) {
            throw new MessageException(
                    "L'intervenant {$this->getIntervenant()} n'est pas sensé fournir de pièce justificative.", null, $exc);
        }
        catch (PieceJointeException $exc) {
            throw new MessageException(
                    "Gestion des pièces justificatives impossible pour l'intervenant {$this->getIntervenant()}.", null, $exc);
        }
        
        return $this->process;
    }
    
    /**
     * @return PieceJointeService
     */
    private function getServicePieceJointe()
    {
        return $this->getServiceLocator()->get('ApplicationPieceJointe');
    }
    
    /**
     * @return Service
     */
    private function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }
}