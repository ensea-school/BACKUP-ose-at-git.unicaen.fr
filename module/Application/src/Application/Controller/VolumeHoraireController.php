<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Form\VolumeHoraire\Saisie;
use Application\Entity\Db\VolumeHoraire;
use Application\Exception\DbException;

/**
 * Description of VolumeHoraireController
 *
 * @method \Doctrine\ORM\EntityManager em() Description
 * @method \Application\Controller\Plugin\Context context()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireController extends AbstractActionController
{
    /**
     * @return \Application\Service\VolumeHoraire
     */
    public function getServiceVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraire');
    }

    public function voirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de volume horaire spécifié.");
        }
        if (!($volumeHoraire = $this->getServiceVolumeHoraire()->getRepo()->find($id))) {
            throw new RuntimeException("Volume horaire '$id' spécifié introuvable.");
        }

        return compact('volumeHoraire');
    }

    public function listeAction()
    {
        if (!($serviceId = (int)$this->params()->fromRoute('id'))) {
            throw new LogicException("Aucun identifiant de service spécifié.");
        }
        if (!($service = $this->getServiceLocator()->get('ApplicationService')->getRepo()->find($serviceId))) {
            throw new RuntimeException("Service '$serviceId' spécifié introuvable.");
        }

        $volumeHoraires = $service->getVolumeHoraire();
        return compact('service','volumeHoraires');
    }

    public function saisieAction()
    {
        $id = (int)$this->params()->fromRoute('id');

        if ($id){
            $entity = $this->getServiceVolumeHoraire()->getRepo()->find($id);
        }else{
            $entity = new VolumeHoraire;
            $entity->setValiditeDebut(new \DateTime);
            $entity->setService( $this->context()->serviceFromQueryPost() );
            $entity->setPeriode( $this->context()->periodeFromQueryPost() );
            $entity->setMotifNonPaiement( $this->context()->motifNonPaiementFromQueryPost() );
            $entity->setTypeIntervention( $this->context()->typeInterventionFromQueryPost() );
        }

        $form = $this->getForm();
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        $request = $this->getRequest();
        if ($request->isPost()){
            $post = $request->getPost();

            $heures = (float)$post['heures'];
            if (0 == $heures){ // plus d'heures = suppression du volume horaire
                $entity->setHistoDestruction (new \DateTime);
            }else{
                $entity->setMotifNonPaiement( $this->context()->motifNonPaiementFromPost() );
                $entity->setHeures( $heures );
            }
        }
        $form->bind( $entity );
        $errors = array();
        if ($request->isPost()){
            if ($form->isValid()){
                try{
                    $this->em()->persist($entity);
                    $this->em()->flush();
                    $form->get('id')->setValue( $entity->getId() ); // transmet le nouvel ID
                }catch(\Exception $e){
                    $e = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }else{
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }

        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/volume-horaire/saisie')
                ->setVariables(compact('form', 'errors'));
        if ($terminal) {
            return $this->popoverInnerViewModel($viewModel, "Saisie d'heures de service", false);
        }
        return $viewModel;
    }

    /**
     * Retourne le formulaire de modif de Volume Horaire.
     * 
     * @return \Application\Form\VolumeHoraire\Saisie
     */
    protected function getForm()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('VolumeHoraireSaisie');
    }
}