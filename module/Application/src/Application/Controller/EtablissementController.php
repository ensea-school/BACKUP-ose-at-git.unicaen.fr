<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Zend\View\Model\JsonModel;

/**
 * Description of EtablissementController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementController extends AbstractActionController
{
    /**
     * @return \Application\Service\Etablissement
     */
    public function getServiceEtablissement()
    {
        return $this->getServiceLocator()->get('ApplicationEtablissement');
    }

    public function indexAction()
    {
        $url = $this->url()->fromRoute('etablissement/default', array('action' => 'choisir'));
        return $this->redirect()->toUrl($url);
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     * @todo placer le formulaire danx une classe à part
     */
    public function choisirAction()
    {
        $url    = $this->url()->fromRoute('etablissement/recherche');
        $etablissement = new \UnicaenApp\Form\Element\SearchAndSelect('etablissement');
        $etablissement->setAutocompleteSource($url)
                ->setRequired(true)
                ->setSelectionRequired(true)
                ->setLabel("Recherchez l'établissement concerné :")
                ->setAttributes(array('title' => "Saisissez le nom de l'établissement"));
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'etablissement-rech'));
        $form->add($etablissement);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $url = $this->url()->fromRoute('etablissement/default', array('action' => 'voir', 'id' => $form->get('etablissement')->getValueId() ) );
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

        $entities  = $this->getServiceEtablissement()->findByLibelle($term)->getQuery()->execute();
        $result = array();
        
        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\Etablissement */
            $result[] = array(
                'id'    => $item->getId(),  // identifiant unique de l'item
                'label' => (string)$item,   // libellé de l'item
                'extra' => '( département '.$item->getDepartement().')', // infos complémentaires (facultatives) sur l'item
            );
        };

        return new JsonModel($result);
    }

    public function voirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de l'établissement spécifié.");
        }
        if (!($etablissement = $this->getServiceEtablissement()->getRepo()->find($id))) {
            throw new RuntimeException("Etablissement '$id' spécifié introuvable.");
        }

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->etablissementGetDifferentiel($etablissement);
        $short = $this->params()->fromQuery('short', false);
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('application/etablissement/voir')
                  ->setVariables(compact('etablissement', 'changements', 'short'));
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->modalInnerViewModel($viewModel, "Détails de l'établissement", false);
        }
        
        return $viewModel;
    }

    public function apercevoirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de l'établissement spécifié.");
        }
        if (!($etablissement = $this->getServiceEtablissement()->getRepo()->find($id))) {
            throw new RuntimeException("Etablissement '$id' spécifié introuvable.");
        }

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->etablissementGetDifferentiel($etablissement);
        $short = $this->params()->fromQuery('short', false);
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('application/etablissement/voir')
                  ->setVariables(compact('etablissement', 'changements', 'short'));
        
        return $viewModel;
    }
}