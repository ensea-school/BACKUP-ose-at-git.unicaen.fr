<?php

namespace Application\Controller;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Zend\View\Model\JsonModel;

/**
 * Description of StructureController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Structure   structure()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureController extends AbstractActionController
{
    public function indexAction()
    {
        $url = $this->url()->fromRoute('structure/default', array('action' => 'choisir'));
        return $this->redirect()->toUrl($url);
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function choisirAction()
    {
        $url    = $this->url()->fromRoute('structure/recherche');
        $structure = new \UnicaenApp\Form\Element\SearchAndSelect('structure');
        $structure->setAutocompleteSource($url)
                ->setRequired(true)
                ->setSelectionRequired(true)
                ->setLabel("Recherchez la structure concernée :")
                ->setAttributes(array('title' => "Saisissez le nom de la structure"));
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'structure-rech'));
        $form->add($structure);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $url = $this->url()->fromRoute('structure/default', array('action' => 'voir', 'id' => $form->get('structure')->getValueId() ) );
                return $this->redirect()->toUrl($url);
            }
        }

        return compact('form');
    }

    public function rechercheAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel(array());
        }

        $repo      = $this->structure()->getRepo();
        $entities  = $repo->findByNom($term);
        $template  = "{label} <small>{extra}</small>";
        $result = array();

        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\Structure */
            $result[] = array(
                'id'    => $item->getId(),          // identifiant unique de l'item
                'label' => $item->getLibelleLong(), // libellé de l'item
                'extra' => $item->getLibelleCourt(),     // infos complémentaires (facultatives) sur l'item
            );
        };

        return new JsonModel($result);
    }

    public function voirAction()
    {
        $this->em()->getFilters()->enable('historique');

        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de structure spécifié.");
        }
        if (!($structure = $this->structure()->getRepo()->find($id))) {
            throw new RuntimeException("Structure '$id' spécifiée introuvable.");
        }

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->structureGetDifferentiel($structure);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(compact('structure', 'changements'));
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());
        return $view;
    }

}