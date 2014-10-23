<?php

namespace Application\Rule\Intervenant;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si un intervenant est concerné par un type d'agrément donné.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteAgrementRule extends AgrementAbstractRule implements ServiceLocatorAwareInterface
{
    const MESSAGE_AUCUN     = 'messageAucun';
    const MESSAGE_INATTENDU = 'messageInattendu';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_AUCUN     => "Le statut de l'intervenant ne nécessite aucun d'agrément particulier.",
        self::MESSAGE_INATTENDU => "Le statut de l'intervenant ne nécessite pas le type d'agrément '%value%'.",
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
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
//            $result = $qb->getQuery()->getScalarResult();
//            
//            if (!$result) {
//                $this->message(self::MESSAGE_AUCUN);
//            }
            $result = $this->executeForIntervenant();
                
            return $this->normalizeResult($result);
        }
        
        /**
         * Recherche des intervenants répondant à la règle
         */
                
        $em  = $this->getServiceIntervenant()->getEntityManager();
        $sql = $this->getQuerySQL();
        
        $stmt = $em->getConnection()->executeQuery($sql);
        
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $this->normalizeResult($result);
    }
    
    /**
     * Retourne la requête SQL de cette règle.
     * NB: les paramètres éventuels ne sont pas valués et restent sous la forme ":param".
     * 
     * @return string
     * @deprecated 
     * @todo Utiliser un query builder puisque la requête a été simplifiée suite à la création de la colonne INTERVENANT.PREMIER_RECRUTEMENT
     */
    public function getQuerySQL()
    {
        $andTypeAgrement = null;
        if ($this->getTypeAgrement()) {
            $andTypeAgrement = "AND tas.TYPE_AGREMENT_ID = " . $this->getTypeAgrement()->getId();
        }

        $sql = <<<EOS
-- intervenants concernés par le type d'agrément
SELECT DISTINCT i.ID
FROM INTERVENANT i
INNER JOIN STATUT_INTERVENANT si ON i.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
INNER JOIN TYPE_AGREMENT_STATUT tas ON si.ID = tas.STATUT_INTERVENANT_ID AND (tas.HISTO_DESTRUCTEUR_ID IS NULL)
    AND (i.PREMIER_RECRUTEMENT IS NULL OR tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT)
INNER JOIN TYPE_AGREMENT ta ON tas.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
WHERE (i.HISTO_DESTRUCTEUR_ID IS NULL)
$andTypeAgrement
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
     * @return boolean
     */
    public function executeForIntervenant()
    {
        // si aucun critère type d'agrément n'a été spécifié
        if (!$this->getTypeAgrement()) {
            if (!$this->getTypesAgrementAttendus()) {
                $this->message(self::MESSAGE_AUCUN, $this->getTypeAgrement());
                return [];
            }
        }
        // si type d'agrément spécifié ne fait pas partie des attendus
        elseif (!in_array($this->getTypeAgrement(), $this->getTypesAgrementAttendus())) {
            $this->message(self::MESSAGE_INATTENDU, $this->getTypeAgrement());
            return [];
        }
        
        return [0 => ['id' => $this->getIntervenant()->getId()]];
    }
    
    public function isRelevant()
    {
        return true;
    }
}
