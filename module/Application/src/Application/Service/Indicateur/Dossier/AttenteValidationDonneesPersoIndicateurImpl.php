<?php

namespace Application\Service\Indicateur\Dossier;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\WfEtape;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationDonneesPersoIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire est en attente de validation de ses données personnelles";
    protected $pluralTitlePattern   = "%s vacataires sont en attente de validation de leurs données personnelles";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultItemUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/validation-dossier', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        // INDISPENSABLE si plusieurs requêtes successives sur IntervenantExterieur !
        $this->getEntityManager()->clear('Application\Entity\Db\IntervenantExterieur');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("int");
        $qb
            ->join("int.statut", "st", Join::WITH, "st.peutSaisirDossier = 1")
            ->andWhere("int.annee = :annee")
            ->setParameter("annee", $this->getServiceContext()->getAnnee());
        
        /**
         * Dans la progression de l'intervenant dans le WF, toutes les étapes précédant l'étape 
         * "Validation des données perso" doivent avoir été franchies
         */
        $qb
                ->join("int.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :codeEtape")
                ->setParameter('codeEtape', WfEtape::CODE_DONNEES_PERSO_VALIDATION);
        
        /**
         * L'intervenant doit intervenir dans la structure spécifiée éventuelle.
         */
        if ($this->getStructure()) {
            $qb
                ->join("int.service", "s")
                ->join("s.elementPedagogique", "ep", Join::WITH, "ep.structure = :structure")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                ->setParameter('tvh', $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu())
                ->setParameter('structure', $this->getStructure())
                ->andWhere("1 = pasHistorise(s)")
                ->andWhere("1 = pasHistorise(ep)")
                ->andWhere("1 = pasHistorise(vh)");
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
         
        return $qb;
    }
}