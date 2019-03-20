<?php
namespace Application\View\Helper\Import;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervenant;
use UnicaenImport\View\Helper\DifferentielLigne\DifferentielLigne;

/**
 * Aide de vue permettant d'afficher une ligne de différentiel d'import
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantViewHelper extends DifferentielLigne
{
    public function getSujet()
    {
        $format = '%s %s (n° %s, %s)';
        if ('insert' == $this->ligne->getAction() || 'undelete' == $this->ligne->getAction()){
            return sprintf( 
                        $format,
                        $this->ligne->get('NOM_USUEL'),
                        $this->ligne->get('PRENOM'),
                        $this->ligne->getSourceCode(),
                        $this->ligne->get('ANNEE_ID').'-'.($this->ligne->get('ANNEE_ID')+1)
                   );
        }else{
            $entity = $this->ligne->getEntity();
            /* @var $entity Intervenant */
            return sprintf( 
                        $format,
                        $entity->getNomUsuel(),
                        $entity->getPrenom(),
                        $this->ligne->getSourceCode(),
                        $entity->getAnnee()->getLibelle()
                   );
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
            case 'STATUT_ID':
                $intervenant = $this->ligne->getEntity();
                if ($intervenant){
                    $oldStatut = $intervenant->getStatut();
                }else{
                    $oldStatut = 'Aucun';
                }
                $statut = $this->ligne->getEntityManager()->find(StatutIntervenant::class, $value);
                return 'changement de statut ('.$oldStatut.' vers '.$statut.')';
            case 'TYPE_ID':
                $intervenant = $this->ligne->getEntity();
                if ($intervenant){
                    $oldType = $intervenant->getType();
                }else{
                    $oldType = 'Aucun';
                }
                $type = $this->ligne->getEntityManager()->find(TypeIntervenant::class, $value);
                return $oldType.' devient '.lcfirst( $type );
            default:
                return parent::getColumnDetails($column, $value);
        }
    }

}