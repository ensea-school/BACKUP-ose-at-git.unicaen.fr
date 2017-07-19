<?php
namespace Application\View\Helper\Import;
use Application\Entity\Db\Personnel;
use Application\Entity\Db\Structure;
use UnicaenImport\View\Helper\DifferentielLigne\DifferentielLigne;

/**
 * Aide de vue permettant d'afficher une ligne de différentiel d'import
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PersonnelViewHelper extends DifferentielLigne
{
    public function getSujet()
    {
        $format = '%s %s (numéro %s)';
        if ('insert' == $this->ligne->getAction() || 'undelete' == $this->ligne->getAction()){
            return sprintf( $format, $this->ligne->get('NOM_USUEL'), $this->ligne->get('PRENOM'), $this->ligne->getSourceCode() );
        }else{
            $entity = $this->ligne->getEntity();
            /* @var $entity Personnel */
            return sprintf( $format, $entity->getNomUsuel(), $entity->getPrenom(), $this->ligne->getSourceCode() );
        }
    }

    public function getColumnDetails($column, $value)
    {
        switch( $column ){
            case 'STRUCTURE_ID':
                if (! empty($value)){
                    $structure = $this->ligne->getEntityManager()->find(Structure::class, $value);
                }else{
                    $structure = null;
                }
                return 'change de structure pour '.($structure ? $structure->getLibelleCourt() : '<i>structure indéfinie</i>');
            case 'NOM_USUEL':
                return 'change de nom usuel pour '.$value;
            default:
                return parent::getColumnDetails($column, $value);
        }
    }

}