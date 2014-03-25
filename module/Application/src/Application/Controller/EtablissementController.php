<?php

namespace Application\Controller;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Zend\View\Model\JsonModel;

/**
 * Description of EtablissementController
 *
 * @method \Doctrine\ORM\EntityManager                  em()
 * @method \Application\Controller\Plugin\Etablissement etablissement()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementController extends AbstractActionController
{
    public function indexAction()
    {
        $url = $this->url()->fromRoute('etablissement/default', array('action' => 'choisir'));
        return $this->redirect()->toUrl($url);
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
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

        $repo      = $this->etablissement()->getRepo();
        $entities  = $repo->findByLibelle($term);
        $result = array();
        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\Etablissement */
            $result[] = array(
                'id'    => $item->getId(),        // identifiant unique de l'item
                'label' => $item->getLibelle(),   // libellé de l'item
                'extra' => '( département '.$item->getDepartement().')', // infos complémentaires (facultatives) sur l'item
            );
        };

        return new JsonModel($result);
    }

    public function voirAction()
    {
        $this->em()->getFilters()->enable('historique');

        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de l'établissement spécifié.");
        }
        if (!($etablissement = $this->etablissement()->getRepo()->find($id))) {
            throw new RuntimeException("Etablissement '$id' spécifié introuvable.");
        }

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->etablissementGetDifferentiel($etablissement);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(compact('etablissement', 'changements'));
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());
        return $view;
    }

}