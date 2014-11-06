<?php

namespace Application\Rule\Intervenant;

use Application\Acl\Role;
use Application\Acl\AdministrateurRole;
use Application\Acl\IntervenantRole;
use Application\Entity\Db\TypeAgrement;
use Application\Traits\StructureAwareTrait;
use Common\Exception\LogicException;
use Doctrine\ORM\QueryBuilder;

/**
 * Règle métier déterminant si un intervenant a reçu un type d'agrément donné.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementFourniRule extends AgrementAbstractRule
{
    use StructureAwareTrait;
    
    const MESSAGE_AUCUN = 'messageAucun';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_AUCUN => "L'agrément %value% n'a pas encore été donné.",
    );
    
    /**
     * Exécute la règle métier.
     * 
     * @return array [ {id} => [ 'id' => {id} ] ]
     */
    public function execute()
    {
        $this->message(null);
        
        /**
         * Recherche des intervenants répondant à la règle
         */
                
        $em  = $this->getServiceIntervenant()->getEntityManager();
        $sql = $this->getQuerySQL();
        
        $stmt = $em->getConnection()->executeQuery($sql);
        
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        /**
         * Cas d'un intervenant précis
         */
        if ($this->getIntervenant()) {
            if (!count($result)) {
                $this->message(self::MESSAGE_AUCUN, sprintf("&laquo; %s &raquo;%s", 
                        $this->getTypeAgrement(),
                        $this->getStructure() ? sprintf(" de la structure &laquo; %s &raquo;", $this->getStructure()) : null));
            }
        }
        
        return $this->normalizeResult($result);
    }
    
    /**
     * Retourne la requête SQL de cette règle.
     * NB: les paramètres éventuels ne sont pas valués et restent sous la forme ":param".
     * 
     * @return string
     */
    public function getQuerySQL()
    {
        if (!$this->getTypeAgrement()) {
            throw new LogicException("Le type d'agrément est requis.");
        }
        
        $andIntervenant       = null;
        $andStructureAgrement = null;
        $andStructureService  = null;
        $andCount             = null;
        
        if ($this->getIntervenant()) {
            $andIntervenant = "AND i.ID = " . $this->getIntervenant()->getId();
        }
        
        if ($this->getStructure()) {
            $andStructureAgrement = "AND a.STRUCTURE_ID = " . $this->getStructure()->getId();
            $andStructureService  = "AND s.STRUCTURE_ENS_ID = " . $this->getStructure()->getId();
        }
        
        /**
         * Agrément CONSEIL ACADEMIQUE : un seul pour toutes les structures d'enseignement
         */
        if ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            // aucun critère de structure pour ce type d'agrément
            $andStructureAgrement = null;
            $andStructureService  = null;
        }
        
        /**
         * Agrément CONSEIL RESTREINT : un par structure d'enseignement
         */
        if ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
//            // si aucune structure d'enseignement précise n'a été fournie, 
//            // un agrément doit exister pour chaque structure d'enseignement
//            if (!$this->getStructure()) {
//                $andCount = "AND aoe.NB_AGR_OBL_EXIST >= COALESCE(c.NB_COMP_ENS, 0)";
//            }
        }

        $sql = <<<EOS
    WITH 
    COMPOSANTES_ENSEIGN AS (
        -- nombre de composantes d'enseignement par intervenant
        SELECT I.ID, I.SOURCE_CODE, COUNT(distinct s.STRUCTURE_ENS_ID) NB_COMP_ENS
        FROM SERVICE s
        INNER JOIN INTERVENANT I ON I.ID = s.INTERVENANT_ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN STRUCTURE comp ON comp.ID = s.STRUCTURE_ENS_ID AND (comp.HISTO_DESTRUCTEUR_ID IS NULL)
        WHERE (s.HISTO_DESTRUCTEUR_ID IS NULL) 
        $andStructureService
        GROUP BY I.ID, I.SOURCE_CODE
    ),
    AGREMENTS_OBLIG_EXIST AS (
        -- nombre d'agréments obligatoires obtenus par intervenant et par type d'agrément
        SELECT I.ID, I.SOURCE_CODE, a.TYPE_AGREMENT_ID, COUNT(a.ID) NB_AGR_OBL_EXIST
        FROM AGREMENT a
        INNER JOIN TYPE_AGREMENT ta ON a.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN INTERVENANT I ON a.INTERVENANT_ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN TYPE_AGREMENT_STATUT tas ON I.STATUT_ID = tas.STATUT_INTERVENANT_ID AND ta.ID = tas.TYPE_AGREMENT_ID 
            AND COALESCE(I.PREMIER_RECRUTEMENT, 0) = tas.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 AND (tas.HISTO_DESTRUCTEUR_ID IS NULL) 
        WHERE (a.HISTO_DESTRUCTEUR_ID IS NULL) 
        $andStructureAgrement
        GROUP BY I.ID, I.SOURCE_CODE, a.TYPE_AGREMENT_ID
    )
    -- intervenants concernés de manière FACULTATIVE par le type d'agrément
    SELECT DISTINCT i.ID --, I.SOURCE_CODE, null NB_AGR_OBL_EXIST, COALESCE(c.NB_COMP_ENS, 0) NB_COMP_ENS
    FROM INTERVENANT i
    INNER JOIN TYPE_AGREMENT_STATUT tas ON i.STATUT_ID = tas.STATUT_INTERVENANT_ID AND (tas.HISTO_DESTRUCTEUR_ID IS NULL) 
        AND (i.PREMIER_RECRUTEMENT IS NULL OR i.PREMIER_RECRUTEMENT = tas.PREMIER_RECRUTEMENT) 
    INNER JOIN TYPE_AGREMENT ta ON tas.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
    --LEFT JOIN COMPOSANTES_ENSEIGN c on c.ID = i.ID
    WHERE (i.HISTO_DESTRUCTEUR_ID IS NULL)
    $andIntervenant
    AND tas.OBLIGATOIRE = 0
    AND tas.TYPE_AGREMENT_ID = {$this->getTypeAgrement()->getId()}

    UNION

    -- intervenants concernés de manière OBLIGATOIRE par le type d'agrément et possédant TOUS les agréments de ce type
    SELECT DISTINCT i.ID --, I.SOURCE_CODE, aoe.NB_AGR_OBL_EXIST, COALESCE(c.NB_COMP_ENS, 0) NB_COMP_ENS
    FROM INTERVENANT i
    INNER JOIN TYPE_AGREMENT_STATUT tas ON i.STATUT_ID = tas.STATUT_INTERVENANT_ID AND COALESCE(i.PREMIER_RECRUTEMENT, 0) = tas.PREMIER_RECRUTEMENT AND (tas.HISTO_DESTRUCTEUR_ID IS NULL)                     
    INNER JOIN TYPE_AGREMENT ta ON tas.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
    INNER JOIN AGREMENTS_OBLIG_EXIST aoe on aoe.ID = i.ID AND aoe.TYPE_AGREMENT_ID = tas.TYPE_AGREMENT_ID
    LEFT JOIN COMPOSANTES_ENSEIGN c on c.ID = i.ID
    WHERE (i.HISTO_DESTRUCTEUR_ID IS NULL)
    $andIntervenant
    AND tas.OBLIGATOIRE = 1
    AND tas.TYPE_AGREMENT_ID = {$this->getTypeAgrement()->getId()}
    $andCount
EOS;
        
        $sql = <<<EOS
SELECT ID
FROM (
    $sql
)
EOS;
        
        return $sql;
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {        
        throw new LogicException("Cette méthode ne devrait pas être appelée!");
    }
    
    /**
     * 
     * @return array
     */
//    private function executeForIntervenant()
//    {
//        if (!($role = $this->getRole())) {
//            throw new LogicException("Un rôle doit être spécifié.");
//        }
//        
//        /**
//         * Conseil Academique (un seul pour toutes les structures d'enseignement)
//         */
//        if ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
//            if (!count($this->getTypesAgrementFournis())) {
//                $this->message(self::MESSAGE_AUCUN, $this->getTypeAgrement());
//                return [];
//            }
//            // aucun critère de structure pour ce type d'agrément
//            $structures = [ null ];
//        }
//        /**
//         * Conseil Restreint (un par structure d'enseignement)
//         */
//        elseif ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
//            // si une structure d'enseignement précise a été fournie, on ne considèrera qu'elle
//            if ($this->getStructure()) {
//                $structures = [ $this->getStructure()->getId() => $this->getStructure() ];
//            }
//            // sinon, pour certains rôles, peu importe la structure
//            elseif ($role instanceof IntervenantRole || $role instanceof AdministrateurRole) {
//                // du point de vue intervenant, aucun critère de structure
//                $structures = [ null ];
//            }
//            else {
//                $structures = $this->getStructuresEnseignement();
//            }
//        }
//        
//        // teste si un agrément existe pour chaque structure d'enseignement
//        foreach ($structures as $structure) {
//            if (!count($this->getAgrementsFournis($structure))) {
//                $this->message(self::MESSAGE_AUCUN, sprintf("&laquo; %s &raquo;%s", 
//                        $this->getTypeAgrement(),
//                        $structure ? sprintf(" de la structure &laquo; %s &raquo;", $structure) : null));
//                return [];
//            }
//        }
//            
//        return [0 => ['id' => $this->getIntervenant()->getId()]];
//    }
    
    public function isRelevant()
    {
        return true;
    }
    
    /**
     * @var Role
     */
    protected $role;
    
    /**
     * 
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * 
     * @param Role $role
     * @return self
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;
        
        return $this;
    }


}