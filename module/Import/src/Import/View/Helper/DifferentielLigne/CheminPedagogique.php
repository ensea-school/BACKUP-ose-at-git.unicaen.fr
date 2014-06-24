<?php
namespace Import\View\Helper\DifferentielLigne;

/**
 * Aide de vue permettant d'afficher une ligne de différentiel d'import
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CheminPedagogique extends DifferentielLigne
{

    public function getColumnDetails($column, $value)
    {
        switch( $column ){
            case 'ELEMENT_PEDAGOGIQUE_ID':
                if (null === $value){
                    return '<span class="text-danger">Elément non identifié</span>';
                }else{
                    return parent::getColumnDetails($column, $value);
                }
            default:
                return parent::getColumnDetails($column, $value);
        }
    }

}