<?php
namespace Import\View\Helper\DifferentielLigne;

use Zend\View\Helper\AbstractHelper;
use Import\Entity\Differentiel\Ligne;

/**
 * Aide de vue permettant d'afficher une ligne de différentiel d'import
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DifferentielLigne extends AbstractHelper
{

    /**
     * @var Ligne
     */
    protected $ligne;

    /**
     * Helper entry point.
     *
     * @return self
     */
    final public function __invoke( Ligne $ligne)
    {
        $filter = new \Zend\Filter\Word\UnderscoreToCamelCase;
        $helperClass = __NAMESPACE__.'\\'.$filter->filter(strtolower($ligne->getTableName()));

        if (class_exists($helperClass)){
            $helperObject = new $helperClass;
            $helperObject->setLigne($ligne);
            $helperObject->setView( $this->getView() );
            return $helperObject;
        }else{
            $this->setLigne($ligne);
            return $this;
        }
    }

    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Génère le code HTML.
     *
     * @return string
     */
    protected function render(){
        $out = $this->getType().' '.$this->getSujet().' '.$this->getAction().' depuis '.$this->getSource().'<br />';
        $details = $this->getDetails();
        if (! empty($details)) $out .= 'Détails : '.implode( ', ', $details ).'';
        return (string)$this->getView()->messenger()->setMessage($out, \UnicaenApp\View\Helper\Messenger::WARNING);
    }

    /**
     * Retourne le type de ligne (en fonction du nom de la table)
     *
     * @return string
     */
    public function getType()
    {
        $type = ucwords(str_replace( '_', ' ', strtolower($this->ligne->getTableName())));
        return $type;
    }

    /**
     * Retourne le sujet de la ligne
     *
     * @return string
     */
    public function getSujet()
    {
        return 'Code initial : '.$this->ligne->getSourceCode();
    }

    /**
     * Retourne l'action à effectuer pour que la mise à jour s'effectue
     *
     * @return string
     */
    public function getAction()
    {
        switch ($this->ligne->getAction()){
            case 'insert' : return 'à importer';
            case 'update' : return 'à mettre à jour';
            case 'delete' : return 'à supprimer';
            case 'undelete' : return 'à restaurer';
        }
        return 'Action non définie';
    }

    /**
     * Retourne les détails de l'action à effectuer
     *
     * @return string[]
     */
    public function getDetails()
    {
        $details = array();
        if ('update' == $this->ligne->getAction()){
            $changes = $this->ligne->getChanges();
            foreach( $changes as $column => $value ){
                $details[] = $this->getColumnDetails( $column, $value );
            }
        }
        return $details;
    }

    public function getColumnDetails($column, $value)
    {
        switch( $column ){
            case 'VALIDITE_DEBUT':
                $date = new \DateTime($value);
                return 'valide depuis le '.$date->format('d/m/Y');
            case 'VALIDITE_FIN':
                $date = new \DateTime($value);
                return 'valide jusqu\'au '.$date->format('d/m/Y');
            default:
                $column = str_replace( '_', ' ', strtolower($column));
                return $column.' devient '.$value;
        }
    }

    /**
     * Retourne la source de données
     *
     * @return string
     */
    public function getSource()
    {
        return $this->ligne->getSource()->getLibelle();
    }

    /**
     *
     * @return Ligne
     */
    public function getLigne()
    {
        return $this->ligne;
    }

    /**
     * 
     * @param Ligne $ligne
     * @return DifferentielLigne
     */
    public function setLigne(Ligne $ligne)
    {
        $this->ligne = $ligne;
        return $this;
    }

}