<?php

namespace Application\Controller;

use Application\Acl\ComposanteDbRole;
use Application\Controller\Plugin\Context;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\PeutCreerContratRule;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Validation;
use Common\Constants;
use Common\Exception\LogicException;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use DateTime;
use UnicaenApp\Exporter\Pdf;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of ContratController
 *
 * @method Context context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;
    
    /**
     * 
     * @return array
     */
    public function indexAction()
    {
        return array();
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteDbRole) {
            return $this->creerAction();
        }
        else {
            return $this->voirAction();
        }
    }
    
    /**
     * 
     * @return array
     */
    public function voirAction()
    {
        $view = new \Zend\View\Model\ViewModel(array(
            'role'        => $this->getContextProvider()->getSelectedIdentityRole(),
            'contrat'     => $this->getContrat(),
            'avenants'    => $this->getAvenants(),
            'intervenant' => $this->getIntervenant(),
        ));
        $view->setTemplate('application/contrat/voir');
        
        return $view;
    }
    
    /**
     * Création du contrat ou d'un avenant, selon la situation.
     * 
     * @return ViewModel
     * @throws RuntimeException
     */
    public function creerAction()
    {
        // si un contrat existe déjà, il s'agit de créer un avenant
        if (!$this->getContrat()) {
            return $this->creerContratAction();
        } 
        else {
            return $this->creerAvenantAction();
        }
    }
    
    /**
     * Création du contrat.
     * 
     * @return ViewModel
     * @throws RuntimeException
     */
    public function creerContratAction()
    {
        $peutCreerContratRule = new PeutCreerContratRule($this->getIntervenant());
        if (!$peutCreerContratRule->execute()) {
            throw new \Common\Exception\MessageException(
                    sprintf("Impossible de créer le contrat de %s.", $this->getIntervenant()),
                    null,
                    new \Exception($peutCreerContratRule->getMessage()));
        }
        
        if ($this->getRequest()->isPost()) {
            $this->creerContrat();
            $this->flashMessenger()->addSuccessMessage(sprintf("Contrat de %s enregistré avec succès.", $this->getIntervenant()));
            
            return $this->redirect()->toRoute('intervenant/contrat', array('intervenant' => $this->getIntervenant()->getSourceCode()));
        }
        
        $view = new \Zend\View\Model\ViewModel(array(
            'intervenant' => $this->getIntervenant(),
        ));
        $view->setTemplate('application/contrat/creer');
        
        return $view;
    }
    
    private function creerContrat()
    {
        // recherche des volumes horaires validés, qui seront rattachés au contrat
        $serviceValidation = $this->getServiceValidation();
        $qb = $serviceValidation->finderByType($code = TypeValidation::CODE_SERVICES_PAR_COMP);
        $qb = $serviceValidation->finderByIntervenant($this->getIntervenant(), $qb);
        $validation = $serviceValidation->finderByStructureIntervention($this->getStructure(), $qb)->getQuery()->getOneOrNullResult();
        $volumesHoraires = $validation->getVolumeHoraire();
        if (!count($volumesHoraires)) {
            throw new RuntimeException(
                    "Anomalie : aucun volume horaire rattachés à la validation de services {$validation->getId()} n'a été trouvé.");
        }

        $this->contrat = $this->getServiceContrat()->newEntity(TypeContrat::CODE_CONTRAT)->setIntervenant($this->getIntervenant());
        foreach ($volumesHoraires as $volumeHoraire) {
            $this->contrat->addVolumeHoraire($volumeHoraire);
            $volumeHoraire->setContrat($this->contrat);
            $this->em()->persist($volumeHoraire);
        }
        $this->em()->persist($this->contrat);
        $this->em()->flush();
        
        return $this;
    }
    
    /**
     * Création d'avenant.
     * 
     * @return ViewModel
     * @throws RuntimeException
     */
    public function creerAvenantAction()
    {
        $peutCreerAvenantRule = new \Application\Rule\Intervenant\PeutCreerAvenantRule($this->getIntervenant());
        $peutCreerAvenantRule
                ->setStructure($this->getStructure())
                ->setTypeContrat($this->em()->getRepository('Application\Entity\Db\TypeContrat')->findOneByCode(TypeContrat::CODE_CONTRAT))
                ->setTypeValidation($this->em()->getRepository('Application\Entity\Db\TypeValidation')->findOneByCode(TypeValidation::CODE_SERVICES_PAR_COMP))
                ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        if (!$peutCreerAvenantRule->execute()) {
            throw new \Common\Exception\MessageException(
                    sprintf("Impossible de créer un avenant pour %s.", $this->getIntervenant()),
                    null,
                    new \Exception($peutCreerAvenantRule->getMessage()));
        }
        
        $volumesHorairesNonValides = $peutCreerAvenantRule->getVolumesHorairesNonValides();
        
        if ($this->getRequest()->isPost()) {
            $this->creerAvenant($volumesHorairesNonValides);
            $this->flashMessenger()->addSuccessMessage(sprintf("Avenant de %s enregistré avec succès.", $this->getIntervenant()));
            
            return $this->redirect()->toRoute('intervenant/contrat', array('intervenant' => $this->getIntervenant()->getSourceCode()));
        }
        
        $view = new \Zend\View\Model\ViewModel(array(
            'intervenant' => $this->getIntervenant(),
        ));
        $view->setTemplate('application/contrat/creer');
        
        return $view;
    }
    
    private function creerAvenant($volumesHorairesNonValides)
    {
        // recherche des volumes horaires validés, qui seront rattachés au contrat
        $serviceValidation = $this->getServiceValidation();
        $qb = $serviceValidation->finderByType($code = TypeValidation::CODE_SERVICES_PAR_COMP);
        $qb = $serviceValidation->finderByIntervenant($this->getIntervenant(), $qb);
        $validation = $serviceValidation->finderByStructureIntervention($this->getStructure(), $qb)->getQuery()->getOneOrNullResult();
        $volumesHoraires = $validation->getVolumeHoraire();
        if (!count($volumesHoraires)) {
            throw new RuntimeException(
                    "Anomalie : aucun volume horaire rattachés à la validation des enseignements {$validation->getId()} n'a été trouvé.");
        }

        $this->contrat = $this->getServiceContrat()->newEntity(TypeContrat::CODE_CONTRAT)->setIntervenant($this->getIntervenant());
        foreach ($volumesHoraires as $volumeHoraire) {
            $this->contrat->addVolumeHoraire($volumeHoraire);
            $volumeHoraire->setContrat($this->contrat);
            $this->em()->persist($volumeHoraire);
        }
        $this->em()->persist($this->contrat);
        $this->em()->flush();
        
        return $this;
    }
    
    /**
     * 
     */
    public function exporterAction()
    {       
        if (!$this->getContrat()) {
            throw new RuntimeException("Aucun contrat existant.");
        }
        
        $fileName = sprintf("contrat_%s_%s.pdf", $this->getIntervenant()->getNomUsuel(), $this->getIntervenant()->getSourceCode());
        
        $variables = array(
            'today'                => (new DateTime())->format(Constants::DATE_FORMAT),
            'dateConseilRestreint' => null,
        );
        
        // Création du pdf, complétion et envoye au navigateur
        $exp = new Pdf($this->getServiceLocator()->get('view_manager')->getRenderer());
//        $exp->setHeaderSubtitle("Contrat");
//        $exp->addBodyHtml("<p style='text-align: center'>Carte n°" . $numeroCarte . "</p>", false);
        $exp->addBodyScript('application/contrat/contrat-pdf.phtml', false, $variables);
        $exp->export($fileName, Pdf::DESTINATION_BROWSER_FORCE_DL);
    }
    
    private $intervenant;
    
    /**
     * @return IntervenantExterieur
     */
    private function getIntervenant()
    {
        if (null === $this->intervenant) {
            $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }
        return $this->intervenant;
    }
    
    private $contrat;
    
    private function getContrat()
    {
        if (null === $this->contrat) {
//            $qb = $this->getServiceContrat()->finderByType(TypeContrat::CODE_CONTRAT);
//            $qb = $this->getServiceContrat()->finderByIntervenant($this->intervenant, $qb);
//            $this->contrat = $qb->getQuery()->getOneOrNullResult();
            $contrats = $this->getIntervenant()->getContrat(TypeContrat::CODE_CONTRAT);
            $this->contrat = count($contrats) ? $contrats[0] : null;
        }

        return $this->contrat;
    }
    
    private $avenants;
    
    private function getAvenants()
    {
        if (null === $this->avenants) {
//            $qb = $this->getServiceAvenants()->finderByType(TypeAvenants::CODE_AVENANT);
//            $qb = $this->getServiceAvenants()->finderByIntervenant($this->intervenant, $qb);
//            $this->avenants = $qb->getQuery()->getOneOrNullResult();
            $this->avenants = $this->getIntervenant()->getContrat(TypeContrat::CODE_AVENANT);
        }

        return $this->avenants;
    }
    
    private $structure;
    
    private function getStructure()
    {
        if (null === $this->structure) {
            $role = $this->getContextProvider()->getSelectedIdentityRole();
            if (!$role instanceof ComposanteDbRole) {
                throw new LogicException("Rôle courant inattendu.");
            }
            $this->structure = $role->getStructure();
        }

        return $this->structure;
    }
    
    private function getValidationService()
    {
        $serviceValidation = $this->getServiceValidation();
        $qb = $serviceValidation->finderByType($code = TypeValidation::CODE_SERVICES_PAR_COMP);
        $qb = $serviceValidation->finderByIntervenant($this->intervenant, $qb);
        $validation = $serviceValidation->finderByStructureIntervention($structure, $qb)->getQuery()->getOneOrNullResult();
        if ($validation) {
            $this->validation[$key]['validation'] = $validation;
        }
    }
    
    /**
     * @return \Application\Service\Validation
     */
    private function getServiceValidation()
    {
        return $this->getServiceLocator()->get('ApplicationValidation');
    }
    
    /**
     * @return \Application\Service\Contrat
     */
    private function getServiceContrat()
    {
        return $this->getServiceLocator()->get('ApplicationContrat');
    }
    
    /**
     * @return \Application\Service\VolumeHoraire
     */
    private function getServiceVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraire');
    }
}
