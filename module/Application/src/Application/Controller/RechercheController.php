<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Service\Intervenant as IntervenantService;

/**
 * Description of RechercheController
 *
 * @method \Application\Controller\Plugin\Recherche intervenant() Description
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class RechercheController extends AbstractActionController
{
    public function intervenantAction()
    {
        if (($data = $this->prg()) instanceof \Zend\Http\Response) {
            return $data;
        }
        
        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('interv');
        $interv->setAutocompleteSource($this->url()->fromRoute('application/default', array('controller' => 'intervenant', 'action' => 'search')))
                ->setLabel("Recherche :")
                ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'intervenant-rech'));
        $form->add($interv);
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('form' => $form));
        $view->setTemplate('application/intervenant/rechercher');
        
        return $view;
    }
    
    public function intervenantFindAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel(array());
        }

        $typeIntervenant = $this->params()->fromQuery('typeIntervenant', false);
        $sTypeIntervenant = $this->getServiceLocator()->get('applicationTypeIntervenant');
        /* @var $sTypeIntervenant \Application\Service\TypeIntervenant */
        $typeIntervenant = $sTypeIntervenant->get( $typeIntervenant );

        $structure = $this->params()->fromQuery('structure', false);
        $sStructure = $this->getServiceLocator()->get('applicationStructure');
        /* @var $sStructure \Application\Service\Structure */
        $structure = $sStructure->get( $structure );

        $template  = "{label} <small>{extra}</small>";
        $resultOSE = array();
        $qb        = $this->getServiceIntervenant()->finderByNomPrenomId($term);
        if ($structure) $this->getServiceIntervenant()->finderByStructure( $structure, $qb );
        if ($typeIntervenant) $this->getServiceIntervenant()->finderByType( $typeIntervenant, $qb );
        $entities  = $qb->getQuery()->execute();

        $f = new \Common\Filter\IntervenantTrouveFormatter();
        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\Intervenant */
            $data = $f->filter($item);
            $data['template'] = $template;
            $resultOSE[$item->getSourceCode()] = $data;
        };
        
        // recherche dans la source de données externe (ex: Harpege)
        $service = $this->getServiceLocator()->get('importServiceIntervenant'); /* @var $service \Import\Service\Intervenant */
        $resultHarp = $service->searchIntervenant($term, $structure, $typeIntervenant);
        
        // marquage des individus existant dans OSE mais inexistant dans la source
        // + retrait des individus trouvés à la fois dans OSE et dans la source
        foreach ($resultOSE as $key => $value) {
            if (!array_key_exists($key, $resultHarp)) {
                $resultOSE[$key]['extra'] .= 
                        ' <i class="badge pull-right" title="Existe dans OSE mais pas dans la source de données externe">Créé dans OSE</i>';
            }
            else {
                unset($resultHarp[$key]);
            }
        }
        // marquage des individus inexistant dans OSE mais existant dans la source
        foreach ($resultHarp as $key => $value) {
            if (!array_key_exists($key, $resultOSE)) {
                $resultHarp[$key]['extra'] .= 
                        ' <i class="badge pull-right" title="Existe dans la source de données externe mais pas dans OSE">Non importé</i>';
            }
            $resultHarp[$key]['template'] = $template;
        }
        // union
        $result = $resultOSE + $resultHarp;
        
        uasort($result, function($v1, $v2) { return strcasecmp($v1['label'], $v2['label']); });

//        var_dump($result);
        
        return new JsonModel($result);
    }

    /**
     * Retourne le service Intervenant.
     * 
     * @return IntervenantService
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('applicationIntervenant');
    }
}