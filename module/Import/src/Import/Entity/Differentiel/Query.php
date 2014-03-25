<?php

namespace Import\Entity\Differentiel;

use Application\Entity\Db\Source;
use Import\Exception\Exception;
use Import\Service\Service;

/**
 * Classe permettant de créer une requête de récupération de différentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Query
{

    const ACTION_INSERT = 'insert';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_UNDELETE = 'undelete';

    /**
     * Nom de la table
     *
     * @var string
     */
    protected $tableName;

    /**
     * ID
     *
     * @var integer|integer[]|null
     */
    protected $id;

    /**
     * Action
     *
     * @var string|string[]|null
     */
    protected $action;

    /**
     * Source de données
     *
     * @var Source|Source[]|null
     */
    protected $source;

    /**
     * Code source
     *
     * @var string|string[]|null
     */
    protected $sourceCode;

    /**
     * Liste des colonnes ayant changé à filtrer
     *
     * @var string|string[]|null
     */
    protected $colChanged;

    /**
     * Liste des colonnes avec des valeurs spéciales à filtrer
     *
     * @var array
     */
    protected $colValues = array();

    /**
     * Limite au nombre d'enregistrements retournés
     *
     * @var integer
     */
    protected $limit;





    /**
     * Constructeur
     * 
     * @param string $tableName
     */
    function __construct( $tableName )
    {
        $this->setTableName($tableName);
    }

    /**
     * Retourne le nom de la table correspondante
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     *
     * @param string $tableName
     * @return self
     */
    public function setTableName($tableName)
    {
        $this->tableName = (string)$tableName;
        return $this;
    }

    /**
     * Retourne le ou les ID scrutés
     *
     * @return integer|integer[]|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Ajoute un ou plusieurs ID
     * 
     * @param integer|integer[]|null $id
     * @return self
     */
    public function setId($id)
    {
        if (empty($id)){
            $this->id = null;
        }elseif( is_array($id)){
            $this->id = array();
            foreach( $id as $i ) $this->id[] = (int)$i;
        }else{
            $this->id = (int)$id;
        }
        return $this;
    }

    /**
     *
     * Retourne la ou les actions scrutées
     *
     * @return string|string[]|null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Ajoute une ou plusieures actions
     *
     * @param string|string[]|null $action
     * @return self
     */
    public function setAction($action)
    {
        $goodActions = array(self::ACTION_DELETE,self::ACTION_INSERT,self::ACTION_UNDELETE,self::ACTION_UPDATE);

        if (empty($action)){
            $this->action = null;
        }elseif( is_array($action)){
            foreach( $action as $a ){
                if (! in_array($a,$goodActions)){
                    throw new Exception('Requête erronée : action "'.$a.'" invalide');
                }
            }
            $this->action = $action;
        }else{
            if (! in_array($action,$goodActions)){
                throw new Exception('Requête erronée : action "'.$action.'" invalide');
            }
            $this->action = $action;
        }
        return $this;
    }

    /**
     * Retourne la ou les sources de données
     *
     * @return Source|Source[]|null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Ajoute un ou plusieurs sources de données
     *
     * @param Source|Source[]|null $source
     * @return self
     */
    public function setSource($source)
    {
        if (empty($source)){
            $this->source = null;
        }elseif( is_array($source)){
            foreach( $source as $s ){
                if (! $s instanceof Source){
                    throw new Exception('Requête erronée : classe source "'.get_class($s).'" invalide');
                }
                if (! $s->getImportable()){
                    throw new Exception('Requête erronée : source "'.$s->getLibelle().'" non importable');
                }
            }
            $this->source = $source;
        }else{
            if (! $source instanceof Source){
                throw new Exception('Requête erronée : classe source "'.get_class($source).'" invalide');
            }
            if (! $source->getImportable()){
                throw new Exception('Requête erronée : source "'.$source->getLibelle().'" non importable');
            }
            $this->source = $source;
        }
        return $this;
    }

    /**
     * Ajoute un ou plusieurs enregistrements sources
     *
     * @return string|string[]|null
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    /**
     *
     * @param string|string[]|null $sourceCode
     * @return self
     */
    public function setSourceCode($sourceCode)
    {
        if (empty($sourceCode)){
            $this->sourceCode = null;
        }elseif( is_array($sourceCode)){
            $this->sourceCode = array();
            foreach( $sourceCode as $sc ) $this->sourceCode[] = (string)$sc;
        }else{
            $this->sourceCode = (string)$sourceCode;
        }
        return $this;
    }

    /**
     * Retourne la liste des colonnes scrutées
     *
     * @return string|string[]|null
     */
    public function getColChanged()
    {
        return $this->colChanged;
    }

    /**
     * Ajoute une ou plusieurs colonnes
     *
     * @param string|string[]|null $colChanged
     * @return self
     */
    public function setColChanged($colChanged)
    {
        if (empty($colChanged)){
            $this->colChanged = null;
        }elseif( is_array($colChanged)){
            $this->colChanged = array();
            foreach( $colChanged as $c ) $this->colChanged[] = (string)$c;
        }else{
            $this->colChanged = (string)$colChanged;
        }
        return $this;
    }

    /**
     * Retourne le liste des valeurs à filtrer, colonne par colonne
     * 
     * @return array
     */
    public function getColValues()
    {
        return $this->colValues;
    }

    /**
     * Applique une liste de colonnes à scruter en fonction des valeurs transmises
     * 
     * format du tableau : {Nom de colonne => Valeur(s) à scruter}
     * 
     * @param array $colValues
     * @return self
     */
    public function setColValues( array $colValues )
    {
        $this->colValues = $colValues;
    }

    /**
     * Détermine une valeu à scruter pour une colonne donnée
     *
     * @param string $column
     * @param mixed $value
     */
    public function addColValue( $column, $value )
    {
        $this->colValues[$column] = $value;
    }

    /**
     * 
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     *
     * @param integer $limit
     * @return self
     */
    public function setLimit($limit)
    {
        $this->limit = (int)$limit;
        return $this;
    }

    /**
     * Construit la requête SQL correspondante
     *
     * @return string
     */
    public function toSql($full=true)
    {
        $viewName = Service::escapeKW('V_DIFF_'.$this->tableName);

        if ($full){
            $sql = 'SELECT * FROM '.$viewName.' ';
        }else{
            $sql = '';
        }
        $where = array();
        if (! empty($this->id)){
            $where[] = $viewName.'.ID'.Service::equals($this->id);
        }

        if (! empty($this->action)){
            $where[] = $viewName.'.IMPORT_ACTION'.Service::equals($this->action);
        }

        if (! empty($this->source)){
            if (is_array($this->source)){
                $values = array();
                foreach( $this->source as $value ){ $values[] = $value->getId(); }
                $where[] = $viewName.'.SOURCE_ID'.Service::equals($values);
            }else{
                $where[] = $viewName.'.SOURCE_ID'.Service::equals($this->source->getId());
            }
        }

        if (! empty($this->sourceCode)){
            $where[] = $viewName.'.SOURCE_CODE'.Service::equals($this->sourceCode);
        }

        if (! empty($this->colChanged)){
            $cols = (array)$this->colChanged;
            $cond = array();
            foreach( $cols as $column ){
                $cond[] = $viewName.'.'.Service::escapeKW ('U_'.$column).' = 1';
            }
            $where[] = '('.implode( ' OR ', $cond).')';
        }

        if (! empty($this->colValues)){
            foreach( $this->colValues as $column => $value ){
                $where[] = $viewName.'.'.Service::escapeKW($column).Service::equals($value);
            }
        }

        if ($this->limit !== null){
            $where[] = 'ROWNUM <= '.$this->limit;
        }

        if (! empty($where)){
            $sql .= 'WHERE '.implode( ' AND ', $where );
        }

        return $sql;
    }

}