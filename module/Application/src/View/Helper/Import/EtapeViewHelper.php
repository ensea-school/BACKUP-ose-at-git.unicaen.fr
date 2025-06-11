<?php
namespace Application\View\Helper\Import;

use Lieu\Service\StructureServiceAwareTrait;
use UnicaenImport\View\Helper\DifferentielLigne\DifferentielLigne;

/**
 * Aide de vue permettant d'afficher une ligne de différentiel d'import
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeViewHelper extends DifferentielLigne
{
    use StructureServiceAwareTrait;

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