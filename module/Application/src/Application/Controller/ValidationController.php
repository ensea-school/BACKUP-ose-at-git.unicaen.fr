<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Application\Entity\Db\Validation;
use Application\Entity\Db\TypeValidation;
use Application\Acl\ComposanteDbRole;
use Application\Acl\IntervenantRole;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of ValidationController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    private $validation;
    private $form;
    
    /**
     * Ajout d'une validation.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function ajouterAction()
    {
        $service = $this->getServiceValidation();
        $form    = $this->getForm();
        $intervenant = $this->context()->mandatory()->intervenantFromRoute('id');
        
        $this->em()->getFilters()->enable('historique');
        $this->validation = $service->finderByType($code = TypeValidation::CODE_DOSSIER_PAR_COMP)->getQuery()->getOneOrNullResult();
        if (!$this->validation) {
            $this->validation = $service->newEntity($code)->setIntervenant($intervenant);
        }
        else {
            $form->get('complet')->setValue(true);
        }
        $form->bind($this->validation);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $complet = (bool) $data['complet'];
                $this->updateValidation($complet);
                $this->notify($complet);
                $this->flashMessenger()->addSuccessMessage(sprintf("Validation %s avec succès.", $complet ? "enregistrée" : "supprimée"));
                $this->flashMessenger()->addInfoMessage("Un mail doit être envoyé pour informer l'intervenant, non ?...");
                
                return $this->redirect()->toRoute(null, array(), array(), true);
            }
        }
        
        return compact('intervenant', 'form');
    }
    
    /**
     * 
     * @param bool $complet
     * @return boolean
     */
    protected function updateValidation($complet)
    {
        if ($complet) {
            $this->em()->persist($this->validation);
        }
        else {
            $this->validation->setHistoDestruction(new \DateTime());
        }
        $this->em()->flush();
        
        return $this;
    }
    
    protected function notify($complet)
    {
    }
    
    /**
     * @return \Zend\Form\Form
     */
    protected function getForm()
    {
        if (null === $this->form) {
            $this->form = new \Zend\Form\Form();
            $this->form->setAttribute('method', 'POST');
            $this->form->add(array(
                'name' => 'complet',
                'type'  => 'Checkbox',
                'options' => array(
                    'label' => "Cochez pour déclarer le dossier complet :",
                ),
                'attributes' => array(
                ),
            ));
            $this->form->add(array(
                'name' => 'dateCommission',
                'type'  => 'UnicaenApp\Form\Element\DateInfSup',
                'options' => array(
                    'date_inf_label' => "Date de passage en commision (facultative) :",
                    'date_sup_activated' => false,
                ),
                'attributes' => array(
                ),
            ));
            $this->form->add(new \Zend\Form\Element\Csrf('security'));
            $this->form->add(array(
                'name' => 'submit',
                'type'  => 'Submit',
                'attributes' => array(
                    'value' => "Enregistrer",
                ),
            ));
            
            $if = new \Zend\InputFilter\InputFilter();
            $if->add(array(
                'name' => 'complet',
                'required' => false,
            ));
            $if->add(array(
                'name' => 'dateCommission',
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\Callback(function($value) {
                        return current($this->form->get('dateCommission')->getValue());
                    }),
                ),
            ));
            $this->form->setInputFilter($if);
            
            $h = new \Zend\Stdlib\Hydrator\ClassMethods(false);
            $h->addStrategy('dateCommission', new \Application\Entity\Db\Hydrator\DateInfSupStrategy());
            $this->form->setHydrator($h);
        }
        
        return $this->form;
    }
    
    /**
     * @return \Application\Service\PieceJointe
     */
    protected function getServicePieceJointe()
    {
        return $this->getServiceLocator()->get('ApplicationPieceJointe');
    }
    
    /**
     * @return \Application\Service\Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
    
    /**
     * @return \Application\Service\TypeValidation
     */
    protected function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
    
    /**
     * @return \Application\Service\Validation
     */
    protected function getServiceValidation()
    {
        return $this->getServiceLocator()->get('ApplicationValidation');
    }
    
    /**
     * @return \Application\Service\Service
     */
    protected function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }
}