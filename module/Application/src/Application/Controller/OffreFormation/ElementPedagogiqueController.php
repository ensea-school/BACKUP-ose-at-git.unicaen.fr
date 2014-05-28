<?php

namespace Application\Controller\OffreFormation;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Application\Exception\DbException;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of ElementPedagogiqueController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementPedagogiqueController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * @return \Application\Service\ServiceReferentiel
     */
    public function getContextProvider()
    {
        return $this->getServiceLocator()->get('ApplicationContextProvider');
    }

    public function voirAction()
    {
        $element = $this->context()->mandatory()->elementPedagogiqueFromRoute('id');
        $title   = "Détails d'un élément pédagogique";
        $short   = $this->params()->fromQuery('short', false);
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(compact('element', 'title', 'short'));
        
        return $viewModel;
    }

    public function apercevoirAction()
    {
        $element = $this->context()->mandatory()->elementPedagogiqueFromRoute('id');
        $title   = "Aperçu d'un élément pédagogique";
        $short   = $this->params()->fromQuery('short', false);
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(compact('element', 'title', 'short'));

        return $viewModel;
    }

    public function ajouterAction()
    {
        return $this->saisirAction();
    }

    public function modifierAction()
    {
        return $this->saisirAction();
    }
    
    protected function saisirAction()
    {
        $etape   = $this->context()->mandatory()->etapeFromRoute(); /* @var $etape \Application\Entity\Db\Etape */
        $id      = $this->params()->fromRoute('id');
        $service = $this->getServiceElementPedagogique();
        $title   = $id ? "Modification d'un élément pédagogique" : "Création d'un élément pédagogique";
        $form    = $this->getFormAjouterModifier();
        $errors  = array();

        $service->canAdd(true);
        
        if ($id) {
            $entity = $service->getRepo()->find($id);
            $form->bind($entity);
        }
        else {
            $entity = $service->newEntity(); /* @var $entity \Application\Entity\Db\ElementPedagogique */
            $entity->setEtape($etape)
                   ->setStructure($etape->getStructure());
            $form->setObject($entity);
        }
        
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $service->save($entity);
                    $form->get('id')->setValue($entity->getId()); // transmet le nouvel ID
                }
                catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('application/offre-formation/element-pedagogique/saisir')
                ->setVariables(compact('form', 'title', 'errors'));
        
        return $viewModel;
    }

    public function supprimerAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if (!($id = $this->params()->fromRoute('id'))){
            throw new \Common\Exception\RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }
        
        $service   = $this->getServiceElementPedagogique();
        $entity    = $service->getRepo()->find($id);
        $title     = "Suppression d'élément pédagogique";
        $form      = new \Application\Form\Supprimer('suppr');
        $errors = array();
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        $service->canAdd(true);
        
        if ($this->getRequest()->isPost()) {
            try {
                $service->delete($entity);
            }catch(\Exception $e){
                $e = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
        }
        return compact('entity', 'title', 'form', 'errors');
    }
    
    /**
     * Action pour rechercher des éléments pédagogiques.
     * 
     * Les filtres pris en compte sont :
     * - structure du contexte local,
     * - niveau du contexte local,
     * - étape du contexte local,
     * Éventuellement écrasés par ceux-là :
     * - paramètre GET 'structure' (id d'une structure),
     * - paramètre GET 'niveau' (ex: 'L-2'),
     * - paramètre GET 'etape' (id d'une étape),
     * 
     * NB: Les résultats sont renvoyés au format JSON.
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function searchAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            exit;
        }
        
        $localContext = $this->getContextProvider()->getLocalContext();
        
        $structure = $this->context()->structureFromQuery() ?: $localContext->getStructure();
        $niveau    = $this->context()->niveauFromQuery()    ?: $localContext->getNiveau();
        $etape     = $this->context()->etapeFromQuery()     ?: $localContext->getEtape();
        
        // respect des filtres éventuels spécifiés en GET ou sinon en session
        $params = array();
        $params['structure'] = $structure;
        $params['niveau']    = $niveau;
        $params['etape']     = $etape;
        $params['term']      = $term;
        $params['limit']     = $limit = 101;
        
        // fetch
        $serviceEp = $this->getServiceLocator()->get('applicationElementPedagogique'); /* @var $serviceEp ElementPedagogiqueService */
        $found     = $serviceEp->finderByTerm($params);

        $result = array();
        foreach ($found as $item) {
            $extra = '';
            if (!$niveau) {
                $extra .= sprintf('<span class="element-rech niveau" title="%s">%s</span>', "Niveau", $item['LIBELLE_GTF'] . $item['NIVEAU']);
            }
            if (!$etape) {
                $extra .= sprintf('<span class="element-rech etape" title="%s">%s</span>', "Étape", $item['LIBELLE_ETAPE']);
            }
            $extra .= "Année" !== $item['LIBELLE_PE'] ? sprintf('<span class="element-rech periode" title="%s">%s</span>', "Période", $item['LIBELLE_PE']) : null;
            $template = sprintf('<span class="element-rech extra">{extra}</span><span class="element-rech element" title="%s">{label}</span>', "Élément pédagogique");
            $result[$item['ID']] = array(
                'id'       => $item['ID'],
                'label'    => $item['SOURCE_CODE'] . ' ' . $item['LIBELLE'],
                'extra'    => $extra,
                'template' => $template,
            );
        };

        $result = \UnicaenApp\Form\Element\SearchAndSelect::truncatedResult($result, $limit - 1);
        
        return new \Zend\View\Model\JsonModel($result);
    }

    /**
     * Retourne le formulaire d'ajout/modif d'ElementPedagogique.
     * 
     * @return \Application\Form\OffreFormation\ElementPedagogiqueSaisie
     */
    protected function getFormAjouterModifier()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('ElementPedagogiqueSaisie');
    }

    /**
     * Retourne le service ElementPedagogique.
     * 
     * @return ElementPedagogiqueService
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('applicationElementPedagogique');
    }
}
