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
        $id      = (int)$this->params()->fromRoute('id',0);
        $svh     = $this->getServiceVolumeHoraire();
        $serviceId = (int)$this->params()->fromQuery('service');
        $periodeId = (int)$this->params()->fromQuery('periode');
        $motifNonPaiementId = (int)$this->params()->fromQuery('motifNonPaiement',0);
        $typeInterventionId = (int)$this->params()->fromQuery('typeIntervention',0);

        $form = new Saisie( $this->getServiceLocator() );
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if (0 != $id){
            /* Initialisation des valeurs */
            $entity = $svh->getRepo()->find($id);
            /* @var $entity \Application\Entity\Db\VolumeHoraire */
            $form->get('heures')->setValue($entity->getHeures());
            $form->get('motifNonPaiement')->setValue( $entity->getMotifNonPaiement() ? $entity->getMotifNonPaiement()->getId() : 0 );
        }else{
            $form->get('motifNonPaiement')->setValue( $motifNonPaiementId );
        }
        $form->get('id')->setValue( $id === 0 ? null : $id );
        $form->get('service')->setValue( $serviceId );
        $form->get('periode')->setValue( $periodeId );
        $form->get('typeIntervention')->setValue( $typeInterventionId );

        $request = $this->getRequest();
        if ($request->isPost()){
            $post = $request->getPost();
            if (0 == $post['motifNonPaiement']) $post['motifNonPaiement'] = null;
            $form->setData($post);
            if ($form->isValid()){
                if (! isset($entity)){
                    $entity = new VolumeHoraire;
                    $entity->setService( $this->em()->find('Application\Entity\Db\Service', $serviceId));
                    $entity->setPeriode( $this->em()->find('Application\Entity\Db\Periode', $periodeId));
                    $entity->setTypeIntervention( $this->em()->find('Application\Entity\Db\TypeIntervention', $typeInterventionId));
                }
                $entity->setHeures($post['heures']);
                if (null !== $post['motifNonPaiement']) $entity->setMotifNonPaiement( $this->em()->find('Application\Entity\Db\MotifNonPaiement', $post['motifNonPaiement']) );
                else $entity->setMotifNonPaiement(null);

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
        $errors = array();
        return compact('form', 'errors');
    }
}