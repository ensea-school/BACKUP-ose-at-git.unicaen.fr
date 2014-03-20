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
    protected function makeQueries()
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
        return compact('message');
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
        $queries = $this->makeQueries();

        $sd = $this->getServiceLocator()->get('ImportServiceDifferentiel');
        /* @var $sd \Import\Service\Differentiel */

        $data = array();
        foreach( $queries as $table => $query ){
            $table = ucwords(str_replace( '_', ' ', strtolower($table)));
            $data[$table] = $sd->make($query)->fetchAll();
        }

        return compact('data');
    }

    public function updateAllDataAction()
    {
        try{

            $queries = $this->makeQueries();

            $sq = $this->getServiceLocator()->get('ImportServiceQueryGenerator');
            /* @var $sq \Import\Service\QueryGenerator */

            foreach( $queries as $table => $query ){
                $sq->execMaj($query);
            }

            $message = 'Mise à jour des données OSE terminée';
        }catch(\Exception $e){
            $message = 'Une erreur a été rencontrée.';
            throw new \UnicaenApp\Exception\LogicException("mise à juor des données OSE impossible", null, $e);
        }
        return compact('message');
    }
}