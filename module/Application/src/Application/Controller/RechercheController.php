<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

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
        
        $intervenant = false;
        
        // post
        if (is_array($data)) {
            $form->setData($data);
//            var_dump($data);
            if ($form->isValid()) {
                $repo = $this->intervenant()->getRepo();
                $intervenant = $repo->findOneBy(array('sourceCode' => $form->get('interv')->getValueId()));
            }
        }
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('form' => $form, 'intervenant' => $intervenant));
        $view->setTemplate('application/intervenant/rechercher');
        
        return $view;
    }
    
    public function intervenantFindAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel(array());
        }
           
        $repo = $this->intervenant()->getRepo();
        $entities = $repo->findByNomPrenomId($term);
        
        $result = array();
        $f = new \Common\Filter\IntervenantTrouveFormatter();
        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\Intervenant */
            $result[$item->getSourceCode()] = $f->filter($item);
//            $result[$item->getSourceCode()] = array(
//                'id'    => $item->getSourceCode(),
//                'label' => $item->__toString(),
//                'extra' => sprintf('%s (%s)', $item->getSourceCode(), $item->getDateNaissance()->format('d/m/Y')),
//            );
        };
        
        // recherche dans la source de données externe (ex: Harpege)
        $service = $this->getServiceLocator()->get('importServiceIntervenant'); /* @var $service \Import\Service\Intervenant */
        $resultHarp = $service->searchIntervenant($term);
        
        
        // marquage des individus existant dans OSE mais inexistant dans la source
        // + retrait des individus trouvés à la fois dans OSE et dans la source
        foreach ($result as $key => $value) {
            if (!array_key_exists($key, $resultHarp)) {
                $result[$key]['extra'] .= ' <i title="Existe dans OSE mais pas dans Harpege"> Introuvable dans Harpege</i>';
            }
            else {
                unset($resultHarp[$key]);
            }
        }
        // marquage des individus inexistant dans OSE mais existant dans la source
        foreach ($resultHarp as $key => $value) {
            if (!array_key_exists($key, $result)) {
                $resultHarp[$key]['extra'] .= ' <i title="Existe dans Harpege mais pas dans OSE"> À importer</i>';
            }
        }
        // union
        $result = $result + $resultHarp;
        
        uasort($result, function($v1, $v2) { return strcasecmp($v1['label'], $v2['label']); });

//        var_dump($result);
        
        return new JsonModel($result);
    }
}