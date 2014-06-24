<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Application\Entity\Db\TypeValidation;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\ServiceValidation;

/**
 * Description of ContratController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function exporterAction()
    {            
        $intervenant = $this->context()->mandatory()->intervenantFromRoute('id');

        $fileName = sprintf("contrat_%s_%s.pdf", $intervenant->getNomUsuel(), $intervenant->getSourceCode());
        
        // Création du pdf, complétion et envoye au navigateur
        $exp = new \UnicaenApp\Exporter\Pdf($this->getServiceLocator()->get('view_manager')->getRenderer());
        $exp->setHeaderSubtitle("Contrat");
//        $exp->addBodyHtml("<p style='text-align: center'>Carte n°" . $numeroCarte . "</p>", false);
        $exp->addBodyScript('/contrat/contrat.phtml', false, array(/*'carte' => $carte*/));
        $exp->export($fileName, \UnicaenApp\Exporter\Pdf::DESTINATION_BROWSER_FORCE_DL);
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function voirAction()
    {
        return array();
    }
}
