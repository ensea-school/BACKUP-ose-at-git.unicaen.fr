<?php

namespace Application\Service\Indicateur\Contrat;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\WfEtape;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteAvenantIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire est en attente de son avenant";
    protected $pluralTitlePattern   = "%s vacataires sont en attente de leur avenant";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/contrat', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $this->initFilters();
        
        // INDISPENSABLE si plusieurs requêtes successives sur Intervenant !
        $this->getEntityManager()->clear('Application\Entity\Db\IntervenantExterieur');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("int");
        $qb
                ->join("int.statut", "st", Join::WITH, "st.peutAvoirContrat = 1")
                ->join("int.service", "s")
                ->join("s.elementPedagogique", "ep")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")->setParameter('tvh', $this->getTypeVolumeHoraire())
                ->join("vh.validation", "v");
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("ep.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        /**
         * L'intervenant doit posséder un contrat initial validé.
         */
        $qb
                ->join("int.contrat", "ci", Join::WITH, "ci.validation IS NOT NULL")
                ->join("ci.typeContrat", "tc", Join::WITH, "tc.code = :codeTypeContratInitial")
                ->setParameter('codeTypeContratInitial', TypeContrat::CODE_CONTRAT);
        
        /**
         * L'étape Contrat/avenant doit être l'étape courante pour la composante d'enseignement concernée.
         */
        $qb
                ->join("int.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :code")
                ->setParameter('code', WfEtape::CODE_CONTRAT);
        if ($this->getStructure()) {
            $qb
                    ->andWhere("p.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * Activation du filtrage Doctrine sur l'historique.
     */
    protected function initFilters()
    {
        $this->getEntityManager()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Service',
                'Application\Entity\Db\VolumeHoraire',
                'Application\Entity\Db\Validation',
                'Application\Entity\Db\Contrat',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }
    
    public function getTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu();
    }
}