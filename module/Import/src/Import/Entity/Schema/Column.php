<?php

namespace Import\Entity\Schema;



/**
 * 
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Column
{
        
    /**
     * Type de données
     *
     * @var string
     */
    public $dataType;

    /**
     * Longueur
     *
     * @var integer
     */
    public $length;

    /**
     * Nullable
     *
     * @var boolean
     */
    public $nullable;

    /**
     * Si la colonne possède ou non une valeur par défaut
     *
     * @var boolean
     */
    public $hasDefault;

    /**
     * Nom de la table référence (si clé étrangère)
     *
     * @var string
     */
    public $refTableName;

    /**
     * Nom du champ référence (si clé étrangère)
     *
     * @var string
     */
    public $refColumnName;

    /**
     * Si l'import par synchronisation est actif ou non
     *
     * @var boolean
     */
    public $importActif;

}