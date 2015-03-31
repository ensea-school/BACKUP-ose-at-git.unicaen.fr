<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DonneesPersoDiffImportIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire a saisi des données personnelles qui diffèrent de celles importées";
    protected $pluralTitlePattern   = "%s vacataires ont saisi des données personnelles qui diffèrent de celles importées";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'indicateur/result-item', 
                ['action' => 'result-item-donnees-perso-diff-import', 'intervenant' => $result->getSourceCode()], 
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
                ->join("int.vIndicDiffDossier", "vidd")
                ->andWhere(
                        "vidd.adresseDossier IS NOT NULL OR " . 
                        "vidd.ribDossier IS NOT NULL OR " . 
                        "vidd.nomUsuelDossier IS NOT NULL OR " . 
                        "vidd.prenomDossier IS NOT NULL");
        
        /**
         * L'intervenant doit intervenir dans la structure spécifiée éventuelle.
         */
        if ($this->getStructure()) {
            $qb
                    ->join("int.service", "s")
                    ->join("s.elementPedagogique", "ep", Join::WITH, "ep.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
}