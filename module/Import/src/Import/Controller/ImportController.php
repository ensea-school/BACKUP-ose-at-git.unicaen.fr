<?php
namespace Import\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Import\Entity\Differentiel\Query;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ImportController extends AbstractActionController
{
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

        $sd = $this->getServiceLocator()->get('ImportServiceDifferentiel');
        /* @var $sd \Import\Service\Differentiel */

        $sc = $this->getServiceLocator()->get('ImportServiceSchema');
        /* @var $sc \Import\Service\Schema */

        $mviews = $sc->getImportMviews();

        if ($tableName){
            $tables = [$tableName];
        }else{
            $tables = $sc->getImportTables();
            sort($tables);
        }

        $data = array();
        foreach( $tables as $table ){
            $query = new Query($table);
            $query->setLimit(101);
            $data[$table] = $sd->make($query)->fetchAll();
        }

        return compact('data', 'mviews');
    }

    public function updateAction()
    {
        $errors = array();
        $tableName = $this->params()->fromRoute('table');
        $typeMaj = $this->params()->fromPost('type-maj');

        $query = new Query( $tableName );

        $sd = $this->getServiceLocator()->get('ImportServiceDifferentiel');
        /* @var $sd \Import\Service\Differentiel */

        $sq = $this->getServiceLocator()->get('ImportServiceQueryGenerator');
        /* @var $sq \Import\Service\QueryGenerator */

        /* Mise à jour des données et récupération des éventuelles erreurs */
        try{
            if ('vue-materialisee' == $typeMaj){
                $sq->execMajVM($tableName);
            }else{
                $errors = $errors + $sq->syncTable($tableName);
                //$sq->execMaj($query);
            }
        }catch(\Exception $e){
            $errors = array($e->getMessage());
        }
        $query->setNotNull(array()); // Aucune colonne ne doit être non nulle !!
        $query->setLimit(101);
        $lignes = $sd->make($query)->fetchAll();

        return array(
            'lignes' => $lignes,
            'table'  => $tableName,
            'errors' => $errors
        );
    }

    public function updateTablesAction()
    {
        $sc = $this->getServiceLocator()->get('ImportServiceSchema');
        /* @var $sc \Import\Service\Schema */

        $sq = $this->getServiceLocator()->get('ImportServiceQueryGenerator');
        /* @var $sq \Import\Service\QueryGenerator */

        $tables = $sc->getImportTables();
        sort($tables);

        $message = '';
        try{
            foreach( $tables as $table ){
                $message .= '<div>Table "'.$table.'" Mise à jour.</div>';
                $sq->execMaj( new Query($table) );
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