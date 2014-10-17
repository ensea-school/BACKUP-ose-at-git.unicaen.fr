<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Service\PieceJointe as PieceJointeService;
use Application\Service\TypePieceJointeStatut as TypePieceJointeStatutService;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si toutes les pièces justificatives OBLIGATOIRES 
 * ont été fournies.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PiecesJointesFourniesRule extends AbstractIntervenantRule
{
    const MESSAGE_INCOMPLET = 'messageIncomplet';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_INCOMPLET => "Toutes les pièces justificatives obligatoires de l'intervenant n'ont pas été fournies.",
    );
    
    /**
     * 
     * @todo Ajouter la jointure avec la table FORMULE quand elle existera
     * @return boolean
     */
    public function execute()
    {
        $this->message(null);
                
        $em  = $this->getServiceIntervenant()->getEntityManager();
        $sql = $this->getQuerySQL();
        
        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
            $stmt = $em->getConnection()->executeQuery($sql, array('intervenant' => $this->getIntervenant()->getId()));
            
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
            if (!$result) {
                $this->message(self::MESSAGE_INCOMPLET, implode(", ", $this->getTypesPieceJointeObligatoiresNonFournis()));
            }
                
            return $this->normalizeResult($result);
        }
        else {
            $stmt = $em->getConnection()->executeQuery($sql);
        }
        
        /**
         * Recherche des intervenants répondant à la règle
         */
        
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $this->normalizeResult($result);
    }
    
    /**
     * 
     * @return boolean
     */
    public function isRelevant()
    {
        if ($this->getIntervenant()) {
            return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
        }
        
        return true;
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        throw new \BadMethodCallException("Cette méthode ne devrait pas être appelée!!");
    }
    
    /**
     * 
     * @return string
     * @throws LogicException
     */
    public function getQuerySQL()
    {
        /**
         * Liste des intervenants (extérieurs) dont le statut requiert des pièces justificatives OBLIGATOIRES,
         * nombre de pièces OBLIGATOIRES à fournir et nombre de pièces OBLIGATOIRES fournies.
         * 
         * On tient compte :
         * - du fait qu'il s'agit d'un 1er recrutement ou pas (cf. données personnelles) ; 
         * - du nombre d'heures réelles de l'intervenant lorsqu'une PJ n'est obligatoire qu'au delà d'un seuil d'heures.
         */
        $sqlTemplate = <<<EOS
    WITH 
    ATTENDU_OBLIGATOIRE AS (
        -- nombres de pj OBLIGATOIRES pour chaque intervenant
        SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
        FROM INTERVENANT_EXTERIEUR IE
        INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
        INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
        LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
        WHERE tpjs.OBLIGATOIRE = 1
        AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
        GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
    ), 
    FOURNI_OBLIGATOIRE AS (
        -- nombres de pj OBLIGATOIRES FOURNIES par chaque intervenant
        SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
        FROM INTERVENANT_EXTERIEUR IE
        INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
        INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
        INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
        INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
        LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
        WHERE tpjs.OBLIGATOIRE = 1
        AND tpjFourni.ID = tpjAttendu.ID
        AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
        %s
        %s
        GROUP BY I.ID, I.SOURCE_CODE
    ), 
    ATTENDU_FACULTATIF AS (
        -- nombres de pj FACULTATIVES pour chaque intervenant
        SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
        FROM INTERVENANT_EXTERIEUR IE
        INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
        INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
        LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
        WHERE (tpjs.OBLIGATOIRE = 0 OR tpjs.OBLIGATOIRE = 1 AND tpjs.SEUIL_HETD IS NOT NULL AND COALESCE(vheures.TOTAL_HEURES, 0) < tpjs.SEUIL_HETD)
        GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
    ), 
    FOURNI_FACULTATIF AS (
        -- nombres de pj FACULTATIVES FOURNIES par chaque intervenant
        SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
        FROM INTERVENANT_EXTERIEUR IE
        INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
        INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
        INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
        INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
        INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
        LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
        WHERE (tpjs.OBLIGATOIRE = 0 OR tpjs.OBLIGATOIRE = 1 AND tpjs.SEUIL_HETD IS NOT NULL AND COALESCE(vheures.TOTAL_HEURES, 0) < tpjs.SEUIL_HETD)
        AND tpjFourni.ID = tpjAttendu.ID
        GROUP BY I.ID, I.SOURCE_CODE
    )
    SELECT 
        COALESCE(AO.INTERVENANT_ID, AF.INTERVENANT_ID)  ID, 
        COALESCE(AO.SOURCE_CODE, AF.SOURCE_CODE)        SOURCE_CODE, 
        COALESCE(AO.TOTAL_HEURES, AF.TOTAL_HEURES)      TOTAL_HEURES, 
        COALESCE(AO.NB, 0)                              NB_PJ_OBLIG_ATTENDU, 
        COALESCE(FO.NB, 0)                              NB_PJ_OBLIG_FOURNI, 
        COALESCE(AF.NB, 0)                              NB_PJ_FACUL_ATTENDU, 
        COALESCE(FF.NB, 0)                              NB_PJ_FACUL_FOURNI 
    FROM            ATTENDU_OBLIGATOIRE AO
    FULL OUTER JOIN ATTENDU_FACULTATIF  AF ON AF.INTERVENANT_ID = AO.INTERVENANT_ID
    LEFT JOIN       FOURNI_OBLIGATOIRE  FO ON FO.INTERVENANT_ID = AO.INTERVENANT_ID
    LEFT JOIN       FOURNI_FACULTATIF   FF ON FF.INTERVENANT_ID = AF.INTERVENANT_ID
    WHERE 1=1 
    %s
EOS;
        
        $andFichier = null;
        if (true === $this->getAvecFichier()) {
            $andFichier = <<<EOS
    AND EXISTS (
        SELECT * FROM PIECE_JOINTE_FICHIER pjf 
        INNER JOIN FICHIER f ON pjf.FICHIER_ID = f.ID AND (f.HISTO_DESTRUCTION IS NULL AND f.HISTO_DESTRUCTEUR_ID IS NULL) 
        WHERE pjf.PIECE_JOINTE_ID = pj.ID
    )
EOS;
        }
        
        $andValidation = null;
        if (true === $this->getAvecValidation()) {
            $andValidation = "AND pj.VALIDATION_ID IS NOT NULL ";
        }
        
        $andIntervenant = null;
        if ($this->getIntervenant()) {
            $andIntervenant = "AND COALESCE(AO.INTERVENANT_ID, AF.INTERVENANT_ID) = :intervenant ";
        }
        
        $sql = sprintf($sqlTemplate, 
                $andFichier,
                $andValidation,
                $andIntervenant);
        
        $sql = <<<EOS
SELECT ID, SOURCE_CODE, TOTAL_HEURES, NB_PJ_OBLIG_ATTENDU, NB_PJ_OBLIG_FOURNI, NB_PJ_FACUL_ATTENDU, NB_PJ_FACUL_FOURNI
FROM (
    $sql
)
WHERE NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI
EOS;
        
        return $sql;
    }
    
    /**
     * Recherche les types de PJ obligatoires non fournis.
     * 
     * NB: les flags suivants sont pris en compte :
     * - flag indiquant s'il faut vérifier ou pas la présence/absence de fichier pour chaque pièce justificative.
     * - flag indiquant s'il faut vérifier ou pas la présence/absence de validation pour chaque pièce justificative.
     * 
     * @return TypePieceJointe[] type_id => TypePieceJointe
     */
    public function getTypesPieceJointeObligatoiresNonFournis()
    {
        if (null === $this->getIntervenant()) {
            throw new LogicException("Cette méthode n'est valable que pour un intervenant précis.");
        }
        
        // liste des PJ déjà fournies
        $pjFournies = $this->getPiecesJointesFournies();

        // liste des (types de) pièces justificatives déjà fournies
        $typesFournis = array();
        foreach ($pjFournies as $pj) {
            $typesFournis[$pj->getType()->getId()] = $pj->getType();
        }

        // liste des types de pièce justificative à fournir selon le statut d'intervenant
        $service = $this->getServiceTypePieceJointeStatut();
        $qb = $service->finderByStatutIntervenant($this->getIntervenant()->getDossier()->getStatut());
        $qb = $service->finderByPremierRecrutement($this->getIntervenant()->getDossier()->getPremierRecrutement(), $qb);
        $typesPieceJointeStatut = $service->getList($qb);

        $totalHeuresReellesIntervenant = $this->getTotalHeuresReellesIntervenant();
        
        // recherche des types de pièce justificative obligatoires non fournis
        $typesPieceJointeObligatoiresNonFournis = [];
        foreach ($typesPieceJointeStatut as $tpjs) { /* @var $tpjs TypePieceJointeStatut */
            if (array_key_exists($tpjs->getType()->getId(), $typesFournis)) {
                continue;
            }
            if (!$tpjs->isObligatoire($totalHeuresReellesIntervenant)) {
                continue;
            }
            $typesPieceJointeObligatoiresNonFournis[$tpjs->getType()->getId()] = $tpjs->getType();
        }
         
        return $typesPieceJointeObligatoiresNonFournis;
    }
    
    /**
     * Recherche les PJ fournies, obligatoires ou non.
     * 
     * NB: les flags suivants sont pris en compte :
     * - flag indiquant s'il faut vérifier ou pas la présence/absence de fichier pour chaque pièce justificative.
     * - flag indiquant s'il faut vérifier ou pas la présence/absence de validation pour chaque pièce justificative.
     * 
     * @return PieceJointe[] type_id => PieceJointe
     */
    public function getPiecesJointesFournies()
    {
        if (null === $this->getIntervenant()) {
            throw new LogicException("Cette méthode n'est valable que pour un intervenant précis.");
        }
        
        $qb = $this->getServicePieceJointe()->finderByDossier($this->getIntervenant()->getDossier());
        if (is_bool($this->getAvecFichier())) {
            $this->getServicePieceJointe()->finderByExistsFichier(true, $qb);
        }
        if (is_bool($this->getAvecValidation())) {
            $this->getServicePieceJointe()->finderByExistsValidation(true, $qb);
        }
        $piecesJointes = $qb->getQuery()->getResult();

        $piecesJointesFournies = [];
        foreach ($piecesJointes as $pj) { /* @var $pj PieceJointe */
            // NB: il ne peut y avoir qu'une seule pièce par type de pièce jointe
            $piecesJointesFournies[$pj->getType()->getId()] = $pj;
        }
        
        return $piecesJointesFournies;
    }
    
    /**
     * Spécifie l'intervenant concerné.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant = null)
    {
        if ($intervenant && !$intervenant instanceof IntervenantExterieur) {
            throw new LogicException("L'intervenant spécifié doit être un IntervenantExterieur.");
        }
        
        $this->intervenant = $intervenant;
        
        return $this;
    }

    /**
     * Retourne le total d'heures réelles de l'intervenant concerné.
     * Ce total est pris en compte pour déterminer le caractère obligatoire de certains types de PJ.
     * 
     * @return float
     */
    public function getTotalHeuresReellesIntervenant()
    {
        if (!$this->getIntervenant()) {
            throw new LogicException("Un intervenant doit être spécifié.");
        }
        
        return $this->getServicePieceJointe()->getTotalHeuresReelles($this->getIntervenant());
    }
    
    protected $avecFichier = null;
    
    /**
     * Retourne le flag indiquant s'il faut vérifier ou pas la présence/absence de fichier pour chaque pièce justificative.
     * 
     * @return boolean|null null : peu importe ; true : présence ; false : absence 
     */
    public function getAvecFichier()
    {
        return $this->avecFichier;
    }

    /**
     * Spécifie s'il faut vérifier ou pas la présence/absence de fichier pour chaque pièce justificative.
     * 
     * @param boolean|null $avecFichier null : peu importe ; true : présence ; false : absence 
     * @return self
     */
    public function setAvecFichier($avecFichier = true)
    {
        $this->avecFichier = $avecFichier;
        
        return $this;
    }
    
    protected $avecValidation = null;
    
    /**
     * Retourne le flag indiquant s'il faut vérifier ou pas la présence/absence de validation pour chaque pièce justificative.
     * 
     * @return boolean|null null : peu importe ; true : présence ; false : absence 
     */
    public function getAvecValidation()
    {
        return $this->avecValidation;
    }

    /**
     * Spécifie s'il faut vérifier ou pas la présence/absence de validation pour chaque pièce justificative.
     * 
     * @param boolean|null $avecValidation null : peu importe ; true : présence ; false : absence 
     * @return self
     */
    public function setAvecValidation($avecValidation = true)
    {
        $this->avecValidation = $avecValidation;
        
        return $this;
    }
    
    /**
     * @return PieceJointeService
     */
    private function getServicePieceJointe()
    {
        return $this->getServiceLocator()->get('applicationPieceJointe');
    }
    
    /**
     * @return TypePieceJointeStatutService
     */
    private function getServiceTypePieceJointeStatut()
    {
        return $this->getServiceLocator()->get('applicationTypePieceJointeStatut');
    }
}
