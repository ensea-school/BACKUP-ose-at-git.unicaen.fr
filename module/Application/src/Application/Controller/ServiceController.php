<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;



/**
 * Description of ServiceController
 *
 * @method \Doctrine\ORM\EntityManager em()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceController extends AbstractActionController
{
    /**
     * @return \Application\Service\Service
     */
    public function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }

    public function indexAction()
    {
        $service = $this->getServiceService();
        $context = $service->getGlobalContext();
        $qb = $service->finderByContext($context);
        $annee = $context['annee'];
        $services = $qb->getQuery()->execute();
        return compact('annee', 'services', 'context');
    }

    public function voirAction()
    {
        $service = $this->getServiceService();
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de service spécifié.");
        }
        if (!($service = $service->getRepo()->find($id))) {
            throw new RuntimeException("Service '$id' spécifié introuvable.");
        }

        return compact('service');
    }

}