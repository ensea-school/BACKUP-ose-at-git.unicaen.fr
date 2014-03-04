<?php

namespace Import\Entity\Differentiel;

use Doctrine\ORM\EntityManager;
use Application\Entity\Db\Source;

/**
 * Classe permettant de récupérer une ligne de différentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Ligne
{

    /**
     * ID
     *
     * @var integer
     */
    protected $id;

    /**
     * Action
     *
     * @var string
     */
    protected $action;

    /**
     * ID de la source
     *
     * @var Source
     */
    protected $source;

    /**
     * Code source
     *
     * @var string
     */
    protected $sourceCode;

    /**
     * Données des colonnes
     *
     * @var array
     */
    protected $values;

    /**
     * Liste des colonnes ayant changé
     *
     * @var boolean[]
     */
    protected $changed;




    /**
     * 
     * @param Statement $stmt
     */
    public function __construct(EntityManager $entityManager, $tableName, array $data)
    {
        $this->id = (integer)$data['ID'];
        unset($data['ID']);

        $this->action = $data['IMPORT_ACTION'];
        unset($data['IMPORT_ACTION']);

        $this->source = $entityManager->find('Application\Entity\Db\Source', (integer)$data['SOURCE_ID']);
        unset($data['SOURCE_ID']);

        $this->sourceCode = $data['SOURCE_CODE'];
        unset($data['SOURCE_CODE']);

        $keys = array_keys( $data );
        foreach( $keys as $key ){
            if (in_array('U_'.$key, $keys)){
                $this->values[$key] = $data[$key];
                $this->changed[$key] = $data['U_'.$key] === '1';
            }
        }
    }

    /**
     * Retourne l'ID OSE de l'enregistrement
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le type d'action prévue pour l'import
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Retourne lasource de données
     *
     * @return Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Retourne le code de la donnée source
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    /**
     * Retourne, sous forme de chaîne de caractères, la valeur de la colonne donnée
     *
     * @param string $colName
     * @return string
     */
    public function get( $colName )
    {
        return $this->values[$colName];
    }

    /**
     * Retourne true si la colonne $colName a changé, false sinon
     *
     * @param string $colName
     * @return boolean
     */
    public function hasChanged( $colName )
    {
        return $this->changed[$colName];
    }

    /**
     * Retourne un tableau des colonnes ayant changé
     *
     * @return array
     */
    public function getChanges()
    {
        $changes = array();
        foreach( $this->changed as $colName => $changed ){
            if ($changed) $changes[$colName] = $this->values[$colName];
        }
        return $changes;
    }
}