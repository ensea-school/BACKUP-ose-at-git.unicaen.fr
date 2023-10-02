<?php
namespace Application\View\Helper\Import;
use OffreFormation\Entity\Db\ElementPedagogique;
use UnicaenImport\View\Helper\DifferentielLigne\DifferentielLigne;

/**
 * Aide de vue permettant d'afficher une ligne de différentiel d'import
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueViewHelper extends DifferentielLigne
{
    public function getSujet()
    {
        $format = '%s (%s, %s)';
        if ('insert' == $this->ligne->getAction() || 'undelete' == $this->ligne->getAction()){
            return sprintf( $format, $this->ligne->get('LIBELLE'), $this->ligne->getSourceCode(), $this->ligne->get('ANNEE_ID').'-'.($this->ligne->get('ANNEE_ID')+1) );
        }else{
            $entity = $this->ligne->getEntityManager()->getRepository(ElementPedagogique::class)->find($this->ligne->getId());
            /* @var $entity ElementPedagogique */
            return sprintf( $format, $entity->getLibelle(), $this->ligne->getSourceCode(), (string)$entity->getAnnee() );
        }
    }

    public function getColumnDetails($column, $value)
    {
        switch( $column ){
            case 'ETAPE_ID':
                if (null === $value){
                    return '<span class="text-danger">Etape non identifiée</span>';
                }else{
                    return parent::getColumnDetails($column, $value);
                }
            default:
                return parent::getColumnDetails($column, $value);
        }
    }

}