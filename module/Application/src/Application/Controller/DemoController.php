<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\LogicException;

/**
 * 
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Intervenant intervenant()
 */
class DemoController extends AbstractActionController
{
    /**
     * @var \Zend\Session\Container
     */
    protected $sessionContainer;

    /**
     * 
     * @return type
     */
    public function indexAction()
    {
//        var_dump($this->identity());
        return array();
    }

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function intervenantAction()
    {
        $url    = $this->url()->fromRoute('recherche', array('action' => 'intervenantFind'));
        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('interv');
        $interv->setAutocompleteSource($url)
                ->setLabel("Rechercher un intervenant :")
                ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
        $form   = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'intervenant-rech'));
        $form->add($interv);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('form' => $form));

        return $view;
    }

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @see IntervenantController
     */
    public function voirIntervenantAction()
    {
        if (!($sourceCode = $this->params()->fromQuery('sourceCode', $this->params()->fromPost('sourceCode')))) {
            if ($this->getRequest()->isXmlHttpRequest()) {
                exit;
            }
            return $this->redirect()->toRoute('home');
        }

        $controller = 'Application\Controller\Intervenant';
        $params     = $this->getEvent()->getRouteMatch()->getParams();

        // import si besoin
        if (!($intervenant = $this->intervenant()->getRepo()->findOneBy(array('sourceCode' => $sourceCode)))) {
            $params['action'] = 'importer';
            $params['id']     = $sourceCode;
            $viewModel        = $this->forward()->dispatch($controller, $params); /* @var $viewModel \Zend\View\Model\ViewModel */
            $intervenant      = $viewModel->getVariable('intervenant');
        }

        $params['action'] = 'voir';
        $params['id']     = $intervenant->getId();
        $viewModel        = $this->forward()->dispatch($controller, $params);

        return $viewModel;
    }

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function saisirServiceReferentielIntervenantAction()
    {
        $controller = 'Application\Controller\Intervenant';
        $params     = $this->getEvent()->getRouteMatch()->getParams();

        $params['action'] = 'saisirServiceReferentiel';
        $viewModel        = $this->forward()->dispatch($controller, $params);

        return $viewModel;
    }

public function modalAction()
{
    $terminal = $this->getRequest()->isXmlHttpRequest();

    $viewModel = new \Zend\View\Model\ViewModel();
    $viewModel->setTemplate('application/demo/modal')
            ->setTerminal($terminal) // Turn off the layout for AJAX requests
            ->setVariables(array(
                'name'     => "Beber",
                'terminal' => $terminal,
            ));

    if ($terminal) {
        // utilisation du plugin encapsulant le résultat de la vue dans le marquage attendu
        // par Bootstrap pour dessiner le contenu de la fenêtre modale
        return $this->modalInnerViewModel($viewModel, "Fenêtre modale", false);
    }

    return $viewModel;
}
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function ofAction()
    {
        $em         = $this->intervenant()->getEntityManager(); /* @var $em \Doctrine\ORM\EntityManager */
        $repository = $em->getRepository('Application\Entity\Db\DemoOf'); /* @var $repository \Doctrine\ORM\EntityRepository */
        $qb         = $repository->createQueryBuilder('t');
        $criteria   = array_filter($this->params()->fromQuery());

        $avecUe = 0;
        if (isset($criteria['avecUe'])) {
            $avecUe = (integer) $criteria['avecUe'];
            unset($criteria['avecUe']);
        }

        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('element');
        $interv->setAutocompleteSource($this->url()->fromRoute('demo', array('action' => 'search-of'), array('query' => array('avecUe' => $avecUe))))
                ->setLabel("Recherche :")
                ->setAttributes(array('title' => "Saisissez 2 lettres au moins"));
        $form   = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'element-rech'));
        $form->add($interv);

        // structures distinctes
        $qb->select('t.codStr, t.licStr')->distinct()->orderBy('t.licStr');
        $structures = array();
        foreach ($qb->getQuery()->execute() as $s) {
            $structures[$s['codStr']] = $s['licStr'];
        }

        // filtre structure obligatoire
        if (empty($criteria['codStr'])) {
            $criteria['codStr'] = key($structures);
        }

        // niveaux distincts
        $qb->select('t.libNivVet, t.codNivVet')->distinct()->orderBy('t.codNivVet');
        $qb->where('t.codStr = :codStr')->setParameter('codStr', $criteria['codStr']);
        $niveaux = array();
        foreach ($qb->getQuery()->execute() as $s) {
            $niveaux[$s['libNivVet']] = $s['libNivVet'];
        }

        // élément
        if (($element = $this->params()->fromPost('element')) && isset($element['id'])) {
            $form->get('element')->setValue($element);
            $criteria['id'] = $element['id'];
        }

        // filtres
        $structure = $criteria['codStr'];
        $niveau    = isset($criteria['libNivVet']) ? $criteria['libNivVet'] : null;

        $selectStructure = new \Zend\Form\Element\Select('structure');
        $selectStructure
                ->setValueOptions($structures)
                ->setLabel("Test")
                ->setValue($structure)
                ->setAttributes(array('class' => 'struct'));

        $this->getSessionContainer()->criteria  = $criteria;
        $this->getSessionContainer()->structure = $structure;
        $this->getSessionContainer()->niveau    = $niveau;

        // fetch
//        $em->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        $entities = $repository->findBy($criteria, array('codNivVet' => 'asc'));

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(compact('entities', 'structures', 'niveaux', 'structure', 'niveau', 'selectStructure', 'form', 'avecUe'));

        return $viewModel;
    }

    public function voirOfAction()
    {
        if (!($id = $this->params()->fromQuery('id'))) {
            throw new LogicException("Aucun élément spécifié.");
        }

        $em      = $this->intervenant()->getEntityManager(); /* @var $em \Doctrine\ORM\EntityManager */
        $element = $em->find('Application\Entity\Db\DemoOf', $id); /* @var $element \Application\Entity\Db\DemoOf */

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest())
                ->setVariables(compact('element'));

        return $viewModel;
    }

    public function searchOfAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            exit;
        }

        $avecUe = (integer) $this->params()->fromQuery('avecUe');

        $terms = explode(' ', trim(preg_replace('`\s+`', ' ', $term)));

        $em         = $this->intervenant()->getEntityManager(); /* @var $em \Doctrine\ORM\EntityManager */
        $repository = $em->getRepository('Application\Entity\Db\DemoOf'); /* @var $repository \Doctrine\ORM\EntityRepository */
        $qb         = $repository->createQueryBuilder('t');

        $concat = $qb->expr()->concat('t.libNivVet', 't.libWebVet');
        $concat = $qb->expr()->concat($concat, 't.codPel');
        $concat = $avecUe ? $qb->expr()->concat($concat, 't.licUe') : $concat;
        $concat = $qb->expr()->concat($concat, 't.codElp');
        $concat = $qb->expr()->concat($concat, 't.libElp');

        $convertX = new \Doctrine\ORM\Query\Expr\Func('CONVERT', array($concat, ':encoding'));

        $and = $qb->expr()->andX();
        foreach ($terms as $i => $t) {
            $key      = "t" . strval($i);
            $convertY = new \Doctrine\ORM\Query\Expr\Func('CONVERT', array(":$key", ':encoding'));
            $like     = $qb->expr()->like($qb->expr()->upper($convertX), $qb->expr()->upper($convertY));
            $and->add($like);
            $qb->setParameter($key, "%$t%");
        }

        $qb->where($and)
                ->setParameter('encoding', 'US7ASCII');

        // utilisation des filtres éventuels en session
        $criteria = $this->getSessionContainer()->criteria;
        if ($criteria) {
            foreach ($criteria as $key => $value) {
                $qb->andWhere("t.$key = :$key")->setParameter($key, $value);
            }
        }

        $entities = $qb->getQuery()->execute();

//        print_r($qb->getQuery()->getSQL());
//        var_dump($qb->getQuery()->getParameters());die;

        $result = array();
        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\DemoOf */
            $extra                  = '';
            $extra .= sprintf('<span class="vet" title="%s">%s</span> > ', "Niveau", $item->getLibNivVet());
            $extra .= sprintf('<span class="vet" title="%s">%s</span> > ', "Version d'étape", $item->getLibWebVet());
            $extra .= sprintf('<span class="pel" title="%s">%s</span> ', "Période", $item->getCodPel());
            $extra .= $avecUe ? sprintf('> <span class="ue" title="%s">%s</span>', "UE", $item->getLicUe()) : null;
            $template               = sprintf('<span class="extra">{extra}</span> <span class="elt" title="%s">{label}</span>', "Élément");
            $result[$item->getId()] = array(
                'id'       => $item->getId(),
                'label'    => $item->getCodElp() . ' ' . $item->getLibElp(),
                'extra'    => $extra,
                'template' => $template,
            );
        };

        return new \Zend\View\Model\JsonModel($result);
    }

    /**
     * @return \Zend\Session\Container
     */
    protected function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            $this->sessionContainer = new \Zend\Session\Container();
        }
        return $this->sessionContainer;
    }
}