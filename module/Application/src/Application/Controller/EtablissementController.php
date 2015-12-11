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
    use \Application\Service\Traits\ContextAwareTrait;
    use \Application\Service\Traits\EtablissementAwareTrait;


    public function indexAction()
    {
        $url = $this->url()->fromRoute('etablissement/default', ['action' => 'choisir']);
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
                ->setAttributes(['title' => "Saisissez le nom de l'établissement"]);
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(['class' => 'etablissement-rech']);
        $form->add($etablissement);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $url = $this->url()->fromRoute('etablissement/default', ['action' => 'voir', 'id' => $form->get('etablissement')->getValueId() ] );
                return $this->redirect()->toUrl($url);
            }
        }

        return compact('form');
    }

    public function rechercheAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Etablissement::class,
        ]);

        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $entities  = $this->getServiceEtablissement()->finderByLibelle($term)->getQuery()->execute();
        $result = [];

        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\Etablissement */
            $result[] = [
                'id'    => $item->getId(),  // identifiant unique de l'item
                'label' => (string)$item,   // libellé de l'item
                'extra' => $item->getDepartement() ? '( département '.$item->getDepartement().')' : '', // infos complémentaires (facultatives) sur l'item
            ];
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

        $title = "Détails de l'établissement";

        return compact('etablissement', 'title');
    }

    public function apercevoirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de l'établissement spécifié.");
        }
        if (!($etablissement = $this->getServiceEtablissement()->getRepo()->find($id))) {
            throw new RuntimeException("Etablissement '$id' spécifié introuvable.");
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('application/etablissement/voir')
                  ->setVariables(compact('etablissement', 'changements'));

        return $viewModel;
    }
}