<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Zend\View\Model\JsonModel;

/**
 * Description of StructureController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureController extends AbstractActionController
{
    use \Application\Service\Traits\ContextAwareTrait;
    use \Application\Service\Traits\StructureAwareTrait;

    

    public function indexAction()
    {
        $url = $this->url()->fromRoute('structure/default', ['action' => 'choisir']);
        return $this->redirect()->toUrl($url);
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     * @todo placer le formulaire danx une classe à part
     */
    public function choisirAction()
    {
        $url    = $this->url()->fromRoute('structure/recherche');
        $structure = new \UnicaenApp\Form\Element\SearchAndSelect('structure');
        $structure->setAutocompleteSource($url)
                ->setRequired(true)
                ->setSelectionRequired(true)
                ->setLabel("Recherchez la structure concernée :")
                ->setAttributes(['title' => "Saisissez le nom de la structure"]);
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(['class' => 'structure-rech']);
        $form->add($structure);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $url = $this->url()->fromRoute('structure/default', ['action' => 'voir', 'id' => $form->get('structure')->getValueId() ] );
                return $this->redirect()->toUrl($url);
            }
        }

        return compact('form');
    }

    public function rechercheAction()
    {
        $this->em()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Structure'
            ],
            $this->getServiceContext()->getDateObservation()
        );

        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }
        $entities  = $this->getServiceStructure()->finderByNom($term)->getQuery()->execute();
        $result = [];

        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\Structure */
            $result[] = [
                'id'    => $item->getId(),          // identifiant unique de l'item
                'label' => $item->getLibelleLong(), // libellé de l'item
                'extra' => $item->getLibelleCourt(),     // infos complémentaires (facultatives) sur l'item
            ];
        };

        return new JsonModel($result);
    }

    public function voirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de structure spécifié.");
        }
        if (!($structure = $this->getServiceStructure()->getRepo()->find($id))) {
            throw new RuntimeException("Structure '$id' spécifiée introuvable.");
        }

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->structureGetDifferentiel($structure);
        $title = "Détails d'une structure";
        $short = $this->params()->fromQuery('short', false);

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('application/structure/voir')
                  ->setVariables(compact('structure', 'changements', 'title', 'short'));

        return $viewModel;
    }

    public function apercevoirAction()
    {
        $structure = $this->context()->mandatory()->structureFromRoute('id');
        $short     = $this->params()->fromQuery('short', false);

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->structureGetDifferentiel($structure);
        $title = "Aperçu d'une structure";

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(compact('structure', 'changements', 'title', 'short'));
        return $viewModel;
    }
}