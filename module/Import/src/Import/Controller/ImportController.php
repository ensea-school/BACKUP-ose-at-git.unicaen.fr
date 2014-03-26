<?php
namespace Import\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Import\Entity\Differentiel\Query;
use Common\Exception\LogicException;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ImportController extends AbstractActionController
{
    protected function makeQueries( $tableName )
    {
        /* Liste des tables pour lesquelles les insertions ne doivent pas être scrutées */
        $noInsertTables = array(
            'INTERVENANT_PERMANENT',
            'INTERVENANT_EXTERIEUR',
            'INTERVENANT',
            'AFFECTATION_RECHERCHE',
            'ADRESSE_INTERVENANT',
        );

        $sc = $this->getServiceLocator()->get('ImportServiceSchema');
        /* @var $sc \Import\Service\Schema */

        $tables = $sc->getImportTables();
        sort($tables);

        if (! empty($tableName)){
            $tableName = str_replace( ' ', '_', strtoupper($tableName) );
            if (! in_array($tableName, $tables)){
                throw new LogicException('La table "'.$tableName.'" n\'est pas correste ou n\'est pas importable.');
            }
            // Réduction à la seule table voulue
            $tables = array( $tableName );
        }

        $queries = array();
        foreach( $tables as $table ){
            $q = new Query($table);
            if (in_array($table,$noInsertTables)){
                $q->setAction(Query::ACTION_DELETE, Query::ACTION_UPDATE, Query::ACTION_UNDELETE);
            }else{
                $q->setAction(null);
            }

            $queries[$table] = $q;
        }
        if (isset($queries['CHEMIN_PEDAGOGIQUE'])){
            $queries['CHEMIN_PEDAGOGIQUE']->setColChanged(array('ELEMENT_PEDAGOGIQUE_ID','ETAPE_ID','VALIDITE_DEBUT','VALIDITE_FIN'));
        }
        return $queries;
    }


    public function indexAction()
    {
        return array('test' => 'import');
    }

    public function updateViewsAndPackagesAction()
    {
        try{
            $processusImport = $this->getServiceLocator()->get('ImportProcessusImport');
            /* @var $processusImport \Import\Processus\Import */
            $processusImport->updateViewsAndPackages();
            $message = 'Mise à jour des vues différentielles et du paquetage d\'import terminés';
        }catch(\Exception $e){
            $message = 'Une erreur a été rencontrée.';
            throw new \UnicaenApp\Exception\LogicException("import impossible", null, $e);
        }

        $terminal = $this->getRequest()->isXmlHttpRequest();

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('import/import/update-tables') // spécification du template obligatoire
                ->setTerminal($terminal) // Turn off the layout for AJAX requests
                ->setVariables(compact('message'));

        if ($terminal) {
            return $this->modalInnerViewModel($viewModel, "Résultat", false);
        }

        return $viewModel;
    }

    public function showImportTblAction()
    {
        $schema = $this->getServiceLocator()->get('ImportServiceSchema');
        /* @var $schema \Import\Service\Schema */

        $data = $schema->getSchema();
        return compact('data');
    }

    public function showDiffAction()
    {
        $tableName = $this->params()->fromRoute('table');

        $queries = $this->makeQueries($tableName);

        $sd = $this->getServiceLocator()->get('ImportServiceDifferentiel');
        /* @var $sd \Import\Service\Differentiel */

        $data = array();
        foreach( $queries as $table => $query ){
            $query->setLimit(101);
            $table = ucwords(str_replace( '_', ' ', strtolower($table)));
            $data[$table] = $sd->make($query)->fetchAll();
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(compact('data'));
        if ($this->getRequest()->isXmlHttpRequest()){
            $viewModel->setTemplate('import/import/show-diff-ajax');
        }else{
            $viewModel->setTemplate('import/import/show-diff');
        }
        return $viewModel;
    }

    public function updateTablesAction()
    {
        $tableName = $this->params()->fromRoute('table');

        $queries = $this->makeQueries($tableName);

        if (! empty($tableName)){
            $tableName = str_replace( ' ', '_', strtoupper($tableName) );
            if (! isset($queries[$tableName])){
                throw new LogicException('La table "'.$tableName.'" n\'est pas correste ou n\'est pas importable.');
            }
            // Réduction à la seule table voulue
            $queries = array( $tableName => $queries[$tableName]);
        }

        $sq = $this->getServiceLocator()->get('ImportServiceQueryGenerator');
        /* @var $sq \Import\Service\QueryGenerator */

        $message = '';
        try{

            foreach( $queries as $table => $query ){
                $message .= '<div>Table "'.$table.'" Mise à jour.</div>';
                $sq->execMaj($query);
            }

            $message .= 'Mise à jour des données OSE terminée';
        }catch(\Exception $e){
            $message = 'Une erreur a été rencontrée.';
            throw new \UnicaenApp\Exception\LogicException("mise à jour des données OSE impossible", null, $e);
        }

        $terminal = $this->getRequest()->isXmlHttpRequest();

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('import/import/update-tables') // spécification du template obligatoire
                ->setTerminal($terminal) // Turn off the layout for AJAX requests
                ->setVariables(compact('message'));

        if ($terminal) {
            return $this->modalInnerViewModel($viewModel, "Résultat", false);
        }

        return $viewModel;
    }
}