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
    protected function makeQueries( $tableName=null, $import=false )
    {
        /* Liste des tables pour lesquelles les insertions ne doivent pas être scrutées */
        $noInsertTables = array(
            'INTERVENANT_PERMANENT',
            'INTERVENANT_EXTERIEUR',
            'INTERVENANT',
            'AFFECTATION_RECHERCHE',
            'ADRESSE_INTERVENANT',
        );
        /* Liste des tables pour lesquelles les insertions doivent tout de même être scrutées si un intervenant existe déjà dans OSE */
        $inTable = array(
            'INTERVENANT_PERMANENT',
            'INTERVENANT_EXTERIEUR',
        );
        $ignoreFields = array(
            'INTERVENANT' => array('STATUT_ID')
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
                $q->setAction(array(Query::ACTION_DELETE, Query::ACTION_UPDATE, Query::ACTION_UNDELETE));
                if (in_array($table, $inTable)){
                    $q->setInTable('INTERVENANT');
                }
            }else{
                $q->setAction(null);
            }

            if (isset($ignoreFields[$table])){
                $q->setIgnoreFields($ignoreFields[$table]);
            }

            $queries[$table] = $q;
        }

        if ($import){
            if (isset($queries['ELEMENT_PEDAGOGIQUE'])){
                $queries['ELEMENT_PEDAGOGIQUE']->addNotNull('ETAPE_ID');
            }
            if (isset($queries['CHEMIN_PEDAGOGIQUE'])){
                $queries['CHEMIN_PEDAGOGIQUE']->addNotNull('ELEMENT_PEDAGOGIQUE_ID');
            }
        }

        return $queries;
    }

    public function indexAction()
    {
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
        $title = "Résultat";
        return compact('message', 'title');
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

    public function updateAction()
    {
        $errors = array();
        $lignes = array();
        $tableName = $this->params()->fromRoute('table');

        $query = $this->makeQueries($tableName, true);

        if (! isset($query[$tableName])){
            $errors[] = 'La table "'.$tableName.'" n\'est pas correste ou n\'est pas importable.';
        }else{
            $query = $query[$tableName];

            $sd = $this->getServiceLocator()->get('ImportServiceDifferentiel');
            /* @var $sd \Import\Service\Differentiel */

            $sq = $this->getServiceLocator()->get('ImportServiceQueryGenerator');
            /* @var $sq \Import\Service\QueryGenerator */

            /* Mise à jour des données et récupération des éventuelles erreurs */
            try{
                $sq->execMaj($query);
            }catch(\Exception $e){
                $errors = array($e->getMessage());
            }
            $query->setNotNull(array()); // Aucune colonne ne doit être non nulle !!
            $query->setLimit(101);
            $lignes = $sd->make($query)->fetchAll();
        }

        return array(
            'lignes' => $lignes,
            'table'  => $tableName,
            'errors' => $errors
        );
    }

    public function updateTablesAction()
    {
        $queries = $this->makeQueries();

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

        $title = "Résultat";
        return compact('message', 'title');
    }
}