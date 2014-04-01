<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Entity\Db\VolumeHoraire;

/**
 * Description of VolumeHoraireController
 *
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

}