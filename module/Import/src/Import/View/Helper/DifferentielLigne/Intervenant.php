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
        if ('insert' == $this->ligne->getAction() || 'undelete' == $this->ligne->getAction()){
            return sprintf( $format, $this->ligne->get('NOM_USUEL'), $this->ligne->get('PRENOM'), $this->ligne->getSourceCode() );
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
            case 'STATUT_ID':
                $intervenant = $this->ligne->getEntity();
                if ($intervenant){
                    $oldStatut = $intervenant->getStatut();
                }else{
                    $oldStatut = 'Aucun';
                }
                $statut = $this->ligne->getEntityManager()->find('Application\Entity\Db\StatutIntervenant', $value);
                return 'changement de statut ('.$oldStatut.' vers '.$statut.')';
            case 'TYPE_ID':
                $intervenant = $this->ligne->getEntity();
                if ($intervenant){
                    $oldType = $intervenant->getType();
                }else{
                    $oldType = 'Aucun';
                }
                $type = $this->ligne->getEntityManager()->find('Application\Entity\Db\TypeIntervenant', $value);
                return $oldType.' devient '.lcfirst( $type );
            default:
                return parent::getColumnDetails($column, $value);
        }
    }

}