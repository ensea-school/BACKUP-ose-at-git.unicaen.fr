<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Indicateur as IndicateurService;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Acl\ComposanteRole;

/**
 * Opérations autour des notifications.
 *
 * @method \Doctrine\ORM\EntityManager em()
 * @method Plugin\Context              context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IndicateurController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * Liste des indicateurs.
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $indicateurs     = $this->getServiceIndicateur()->getList();
        $indicateursImpl = $this->getServiceIndicateur()->getIndicateursImpl($indicateurs, $this->getStructure());
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            'indicateurs'     => $indicateurs,
            'indicateursImpl' => $indicateursImpl,
        ]);
        
        return $viewModel;
    }
    
    /**
     * Détails d'un indicateur.
     * 
     * @return ViewModel
     */
    public function voirAction()
    {
        $indicateur     = $this->context()->mandatory()->indicateurFromRoute();
        $indicateurImpl = $this->getServiceIndicateur()->getIndicateurImpl($indicateur, $this->getStructure());
//        var_dump(get_class($impl));
        
        $viewModel = new ViewModel();
        $viewModel->setVariables([
            'indicateur'     => $indicateur,
            'indicateurImpl' => $indicateurImpl,
        ]);
        
        return $viewModel;
    }    
    
    /**
     * @return StructureEntity
     */
    private function getStructure()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteRole) {
            return $role->getStructure();
        }
        
        return null;
    }
    
    /**
     * @return IndicateurService
     */
    private function getServiceIndicateur()
    {
        return $this->getServiceLocator()->get('IndicateurService');
    }
}