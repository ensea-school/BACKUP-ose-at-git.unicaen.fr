<?php

namespace Application\Controller;

use Application\Acl\ComposanteDbRole;
use Application\Controller\Plugin\Context;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\PieceJointe;
use Application\Form\Joindre;
use Application\Rule\Intervenant\PiecesJointesFourniesRule;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\PieceJointe as PieceJointeService;
use Application\Service\Process\PieceJointeProcess;
use Application\Service\Service;
use Application\Service\TypePieceJointeStatut;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Common\Exception\MessageException;
use Common\Exception\PieceJointe\AucuneAFournirException;
use Common\Exception\PieceJointe\PieceJointeException;
use Doctrine\ORM\EntityManager;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of UploadController
 *
 * @method EntityManager                em()
 * @method Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PieceJointeController extends AbstractActionController implements ContextProviderAwareInterface, WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var Form
     */
    private $form;
    
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
     * @return ViewModel
     * @throws MessageException
     */
    public function indexAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteDbRole) {
            return $this->modifierPiecesJointesAction();
        }
        else {
            return $this->voirPiecesJointesAction();
        }
    }
    
    /**
     * 
     * @return ViewModel
     * @throws MessageException
     */
    public function voirPiecesJointesAction()
    { 
        $this->commonPiecesJointes();
        
        $this->form
                ->remove('submit')
                ->get('pj')->setAttribute('disabled', true)->setLabel("Merci d'adresser les pièces justificatives suivantes à l'adresse ci-après...");
        
        $this->view->setVariables(array('title' => $this->title));
        
        return $this->view;
    }
    
    public function listerAction()
    {
        $piecesJointes = $this->getPieceJointeProcess()->getPiecesJointesFournies($this->getTypePieceJointe());
        
//        $content = file_get_contents('/home/gauthierb/Images/Capture du 2012-10-03 15:13:12.png');
//        $pieceJointe
//                ->setFichier($content)
//                ->setNomFichier("Capture du 2012-10-03 15:13:12.png")
//                ->setTailleFichier(strlen($content));
//        $this->em()->flush($pieceJointe);
        
        $form = $this->getFormJoindre();
        $form->setAttribute('action', $this->url()->fromRoute('piece-jointe/intervenant/ajouter', [], [], true));
               
        return [
            'typePieceJointe' => $this->getTypePieceJointe(),
            'piecesJointes'   => $piecesJointes,
            'form'            => $form,
        ];
        
    }
    
    public function ajouterAction()
    {
//        $piecesJointes = $this->getPieceJointeProcess()->getPiecesJointesFournies($this->getTypePieceJointe());
        $form     = $this->getFormJoindre();

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);
//                var_dump($post, $form->isValid(), $form->getMessages());
            if ($form->isValid()) {
                $data = $form->getData();
                foreach ($data['files'] as $file) {
                    $path          = $file['tmp_name'];
                    $nomFichier    = $file['name'];
                    $typeFichier   = $file['type'];
                    $tailleFichier = $file['size'];
                    $pj = (new PieceJointe())
                            ->setType($this->getTypePieceJointe())
                            ->setDossier($this->getIntervenant()->getDossier())
                            ->setNomFichier($nomFichier)
                            ->setTailleFichier($tailleFichier)
                            ->setTypeFichier($typeFichier)
                            ->setFichier(file_get_contents($path))
                            ->setValidation(null);
                    $this->em()->persist($pj);
                    $this->em()->flush();
                    unlink($path);
                }
                
                return $this->redirect()->toRoute('piece-jointe/intervenant/lister', [], [], true);
            }
        }
        
        $viewModel = new ViewModel();
        $viewModel->setVariables(array(
            'typePieceJointe' => $this->getTypePieceJointe(),
            'form'      => $form,
            'terminal'  => $this->getRequest()->isXmlHttpRequest(),
            'uploadUrl' => $this->url()->fromRoute(null, ['action' => 'download'], [], true),
        ));

        return $viewModel;
    }
    
    public function supprimerAction()
    {
        $pj              = $this->getPieceJointe();
        $typePieceJointe = $pj->getType();
        
        $this->em()->remove($this->getPieceJointe());
        $this->em()->flush();
        
        return $this->redirect()->toRoute('piece-jointe/intervenant/lister', ['typePieceJointe' => $typePieceJointe->getId()], [], true);
    }
    
    public function telechargerAction()
    {
        $pj          = $this->getPieceJointe();
        $content     = stream_get_contents($pj->getFichier());
        $contentType = $pj->getTypeFichier() ?: 'application/octet-stream';
        
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename=' . $pj->getNomFichier());
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($content));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        
        echo $content;
        exit;
    }
    
    /**
     * 
     * @return ViewModel
     * @throws MessageException
     */
    public function modifierPiecesJointesAction()
    { 
        $this->commonPiecesJointes();

        $this->form->get('pj')->setLabel("Cochez les pièces qui ont été fournies...");
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->form->setData($data);
            if ($this->form->isValid()) {
                $this->process->updatePiecesJointes($data['pj']);
                $this->flashMessenger()->addSuccessMessage("Checklist enregistrée avec succès.");
                return $this->redirect()->toUrl($this->url()->fromRoute(null, array(), array(), true));
            }
        }
        
        $this->view->setVariables(array('title' => $this->title));

        return $this->view;
    }
    
    private function commonPiecesJointes()
    {
        $this->title        = "Pièces justificatives <small>{$this->getIntervenant()}</small>";
        $role               = $this->getContextProvider()->getSelectedIdentityRole();
        $serviceService     = $this->getServiceService();
        $servicePieceJointe = $this->getPieceJointeService();
        
        $servicePieceJointe->canAdd($this->getIntervenant(), true);
        
        $dossier = $this->getIntervenant()->getDossier();
        if (!$dossier) {
            throw new MessageException("L'intervenant {$this->getIntervenant()} n'a aucune donnée personnelle enregistrée.");
        }
        
        $this->form = $this->getPieceJointeProcess()->getFormPiecesJointes();
        $typesPieceJointeStatut = $this->getPieceJointeProcess()->getTypesPieceJointeStatut();
        
        $piecesJointesFournies = $this->getServiceLocator()->get('PiecesJointesFourniesRule')
                ->setIntervenant($this->getIntervenant());
        $complet = $piecesJointesFournies->execute();
        
        $formUpload = $this->getFormJoindre();
        
        $this->view = new ViewModel(array(
            'intervenant'              => $this->getIntervenant(),
            'totalHETD'                => $this->getPieceJointeProcess()->getTotalHETDIntervenant(),
            'annee'                    => $this->getContextProvider()->getGlobalContext()->getAnnee(),
            'typesPieceJointeStatut'   => $typesPieceJointeStatut,
            'totalHeuresReelles'       => $serviceService->getTotalHeuresReelles($this->getIntervenant()),
            'dossier'                  => $dossier,
            'complet'                  => $complet,
            'destinataires'            => $this->getDestinatairesPiecesJointes(),
            'form'                     => $this->form,
            'formUpload'               => $formUpload,
            'role'                     => $role,
            'title'                    => $this->title,
        ));
    }
    
    public function uploadAction()
    {
        error_reporting(E_ALL | E_STRICT);
        $this->uploader()
//                ->setUploadDir($this->getUploadDir($ligne))
                ->setUploadUrl($this->getUploadUrl() . '/')
                ->setDownloadUrl($this->getDownloadUrl())
                ->handle();
        exit;
    }
    
    public function downloadAction()
    {
        error_reporting(E_ALL | E_STRICT);
        $this->uploader()
//                ->setUploadDir($this->getUploadDir($ligne))
                ->setUploadUrl($this->getUploadUrl() . '/')
                ->setDownloadUrl($this->getDownloadUrl())
                ->handle();
        exit;
    }

    protected $formJoindre;
    
    protected function getFormJoindre()
    {
        if (null === $this->formJoindre) {
            $this->formJoindre = new Joindre();
            $this->formJoindre//->setHydrator(HydratorFactory::getHydrator($ligne))
                    //->bind($ligne)
                    ->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        }
        return $this->formJoindre;
    }
    
    protected function getDownloadUrl()
    {
        return $this->url()->fromRoute(null, ['action' => 'download'], [], true);
    }
    
    protected function getUploadUrl()
    {
        return $this->url()->fromRoute(null, ['action' => 'upload'], [], true);
    }
    
//    protected function getUploadDir(Ligne $ligne)
//    {
//        $options = $this->getServiceLocator()->get('closer-module_options'); /* @var $options \CloserModule\Options\ModuleOptions */
//        return sprintf($options->getUploadDir() . '/acteur-%s/ligne-%s/', 
//                $ligne->getActeur()->getIdInterne(), 
//                $ligne->getId());
//    }
    
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
     * @return TypePieceJointeStatut
     */
    private function getServiceTypePieceJointeStatut()
    {
        return $this->getServiceLocator()->get('ApplicationTypePieceJointeStatut');
    }
    
    /**
     * @return PieceJointeService
     */
    private function getPieceJointeService()
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