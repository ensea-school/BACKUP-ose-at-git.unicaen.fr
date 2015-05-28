<?php
namespace Import\View\Helper\DifferentielLigne;

/**
 * Aide de vue permettant d'afficher une ligne de différentiel d'import
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Etape extends DifferentielLigne
{
    use \Application\Service\Traits\StructureAwareTrait
    ;

    public function getColumnDetails($column, $value)
    {
        switch( $column ){
            case 'STRUCTURE_ID':
                if (null === $value){
                    return '<span class="text-danger">Structure non identifiée</span>';
                }else{
                    $column = 'Structure';
                    $value = $this->getServiceStructure()->get($value);
                    return parent::getColumnDetails($column, $value);
                }
            default:
                return parent::getColumnDetails($column, $value);
        }
    }

}