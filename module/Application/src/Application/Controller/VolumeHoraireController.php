<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Entity\Db\VolumeHoraire;

/**
 * Description of VolumeHoraireController
 *
 * @method \Doctrine\ORM\EntityManager em() Description
 * @method \Application\Controller\Plugin\Context context() Description
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

    /**
     *
     * @param integer $serviceId
     * @return array
     */
    protected function getContext( $service )
    {
        $this->getServiceLocator()->get('ApplicationService')->getRepo()->find($service); /** @todo à modifier! ! ! */
        return array(
        //    'service'       => $service,
        );
    }

    public function indexAction()
    {
        $context = $this->getContext( 1 );
        $volumeHoraires = $this->getServiceVolumeHoraire()->finderByContext($context)->getQuery()->execute();
        return compact('volumeHoraires', 'context');
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

    public function ajouterAction()
    {
        
    }

    public function modifierAction()
    {
        $vh   = $this->context()->mandatory()->volumeHoraireFromQuery(); /* @var $vh \Application\Entity\Db\VolumeHoraire */
        $form = new \Application\Form\VolumeHoraire\Saisie('vh'); 
        var_dump('avant', $vh->getId(), $vh->getHeures());
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($form->isValid()) {
                var_dump($data);
                $vh->setHeures(floatval($data['heures']));
                $this->em()->flush($vh);
                var_dump('apres', $vh->getId(), $vh->getHeures());
            }
        }//<a onclick="$('#bsm5345063f12817').popover('hide');" class="close">×</a>
        die;
    }
}