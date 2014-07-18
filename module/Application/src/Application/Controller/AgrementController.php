<?php

namespace Application\Controller;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\Agrement;
use Application\Service\ContextProviderAwareInterface;
use Application\Rule\Intervenant\AgrementFourniRule;

/**
 * Description of AgrementController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementController extends AbstractActionController implements ContextProviderAwareInterface
{
    use \Application\Service\ContextProviderAwareTrait;
    
    /**
     * @var TypeAgrement
     */
    private $typeAgrement;
    
    /**
     * @var Agrement
     */
    private $agrement;
    
    /**
     * @var \Zend\View\Model\ViewModel
     */
    private $view;
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function voirAction()
    {
        $role               = $this->getContextProvider()->getSelectedIdentityRole();
        $this->typeAgrement = $this->context()->mandatory()->typeAgrementFromRoute();
        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
        $this->title        = sprintf("Agrément &laquo; %s &raquo; <small>%s</small>", $this->typeAgrement, $this->intervenant);
        $this->readonly     = true;
        $messages           = [];
        
        $this->em()->getFilters()->enable('historique');
        
        $agrementFourniRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $agrementFourniRule AgrementFourniRule */
        $agrementFourniRule
            ->setMemePartiellement()
            ->setIntervenant($this->intervenant)
            ->setTypeAgrement($this->typeAgrement)
            ->execute();
        
        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            $structures = $agrementFourniRule->getStructuresEnseignement();
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            $structure = $this->intervenant->getStructure()->getParenteNiv2();
            $structures = [ $structure->getId() => $structure ];
        }
        else {
            throw new \Common\Exception\LogicException("Type d'agrément inattendu!");
        }

        /**
         * Collecte des agréments pour chaque structure
         */
        $data = array();
        foreach ($structures as $structure) {
            $data[$structure->getId()]['structure'] = $structure;
            $data[$structure->getId()]['agrements'] = $agrementFourniRule->getAgrementsFournis($structure);
        }
        
        if (!$data) {
            $messages = ["Aucun agrément n'a été trouvé."];
        }

        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant'       => $this->intervenant,
            'data'              => $data,
            'role'              => $role,
            'title'             => $this->title,
            'readonly'          => $this->readonly,
            'messages'          => $messages,
        ));
        $this->view->setTemplate('application/agrement/intervenant');
        
        return $this->view;
    }
    
    /**
     * Saisie d'agrément par chaque composante d'affectation ou par la composante d'enseignement.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function modifierAction()
    {
        $this->readonly = false;
        
        $role               = $this->getContextProvider()->getSelectedIdentityRole();
        $this->structure    = $role->getStructure();
        $this->typeAgrement = $this->context()->mandatory()->typeAgrementFromRoute();
        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
        $this->formValider  = $this->getFormValidationService()->setIntervenant($this->intervenant)->init();
        $this->title        = "Validation des enseignements <small>$this->intervenant</small>";
        $typeVolumeHoraire  = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $typeValidation     = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_SERVICES_PAR_COMP)->getQuery()->getOneOrNullResult();
        $messages           = [];

        // NB : les agréments par la seule composante d'affectation ;
        //    : les enseignements d'un vacataire sont validés par chaque composante d'intervenation.
        $structure = $this->intervenant->estPermanent() ? null : $this->structure;
        
        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            $structure = $this->structure;
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            $structure = $this->intervenant->getStructure()->getParenteNiv2();
            $structures = [ $structure->getId() => $structure ];
        }
        else {
            throw new \Common\Exception\LogicException("Type d'agrément inattendu!");
        }
        
//        $serviceValidation->canAdd($this->intervenant, $typeValidation, true);

        $this->em()->getFilters()->enable('historique');
        
        $this->collectValidationsServices($typeValidation, $structure);
        
        $this->em()->clear('Application\Entity\Db\Service'); // INDISPENSABLE entre les 2 requêtes sur Service !
        
        $agrementFourniRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $agrementFourniRule AgrementFourniRule */
        $agrementFourniRule
            ->setMemePartiellement()
            ->setIntervenant($this->intervenant)
            ->setTypeAgrement($this->typeAgrement)
            ->execute();
        
        // agréments concernant la structure
        $agrements = $agrementFourniRule->getAgrementsFournis($structure);
        
        if (!count($servicesNonValides)) {
            $this->validation = current($this->validations);
            $message = sprintf("Aucun enseignement à valider%s n'a été trouvé.", 
                    $structure ? " concernant la composante d'intervention &laquo; $structure &raquo;" : null);
            $messages = [$message];
        }
        
        if ($servicesNonValides && !$this->validation) {
            $this->validation = $serviceValidation->newEntity($typeValidation)
                    ->setIntervenant($this->intervenant)
                    ->setStructure($this->structure);
            
            $this->validations = [$this->validation->getId() => $this->validation] + $this->validations;
            $this->services    = [$this->validation->getId() => $servicesNonValides] + $this->services;
            
            $this->formValider->bind($this->validation);
            
            $messages = ['info' => "Des enseignements à valider ont été trouvés..."];
        }
        
//        var_dump("---------------------------");
//        var_dump(count($validations) . " validations collectées :");
//        foreach ($validations as $id => $validation) {
//            $ss = $services[$id];
//            var_dump("Validation $id : " . count($ss) . " services associés.");
//            foreach ($ss as $s) {
//                var_dump(count($s->getVolumeHoraire()) . " volumes associés :", \UnicaenApp\Util::collectionAsOptions($s->getVolumeHoraire()));
//            }
//        }

        $this->view = new \Zend\View\Model\ViewModel(array(
            'role'               => $role,
            'typeVolumeHoraire'  => $typeVolumeHoraire,
            'intervenant'        => $this->intervenant,
            'validations'        => $this->validations,
            'services'           => $this->services,
            'formValider'        => $this->formValider,
            'title'              => $this->title,
            'readonly'           => $this->readonly,
            'messages'           => $messages,
        ));
        $this->view->setTemplate('application/validation/service');
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->formValider->setData($data);
            if ($this->formValider->isValid()) {
                // peuplement de la nouvelle validation avec les volumes horaires non validés
                foreach ($servicesNonValides as $s) { /* @var $s \Application\Entity\Db\Service */
                    foreach ($s->getVolumeHoraire() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
                        $this->validation->addVolumeHoraire($vh);
                    }
                }
                $serviceValidation->save($this->validation);
                $this->flashMessenger()->addSuccessMessage("Validation enregistrée avec succès.");
                
                return $this->redirect()->toRoute(null, array(), array(), true);
            }
        }
        
        return $this->view;
    }
    
    /**
     * @return \Application\Service\Agrement
     */
    protected function getAgrementService()
    {
        return $this->getServiceLocator()->get('ApplicationAgrement');
    }
}
