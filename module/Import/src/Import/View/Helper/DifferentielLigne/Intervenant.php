<?php
namespace Import\View\Helper\DifferentielLigne;

/**
 * Aide de vue permettant d'afficher une ligne de différentiel d'import
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends DifferentielLigne
{
    public function getSujet()
    {
        $format = '%s %s (numéro %s)';
        if ('insert' == $this->ligne->getAction()){
            $data = $this->ligne->getChanges();
            return sprintf( $format, $data['NOM_USUEL'], $data['PRENOM'], $this->ligne->getSourceCode() );
        }else{
            $entity = $this->ligne->getEntity();
            /* @var $entity \Application\Entity\Db\Intervenant */
            return sprintf( $format, $entity->getNomUsuel(), $entity->getPrenom(), $this->ligne->getSourceCode() );
        }
    }

    public function getColumnDetails($column, $value)
    {
        switch( $column ){
            case 'STRUCTURE_ID':
                $structure = $this->ligne->getEntityManager()->find('Application\Entity\Db\Structure', $value);
                return 'change de structure pour '.$structure->getLibelleCourt();
            case 'NOM_USUEL':
                return 'change de nom usuel pour '.$value;
            default:
                return parent::getColumnDetails($column, $value);
        }
    }

}