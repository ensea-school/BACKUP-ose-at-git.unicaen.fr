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
//            var_dump($sql, $result);
        
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
//        var_dump($sql, $result, $this->normalizeResult($result));
        
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
WITH ATTENDU AS (
  -- nombres de pj OBLIGATOIRES pour chaque intervenant
  SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjs.id) NB /*+ materialize */
  FROM INTERVENANT_EXTERIEUR IE
  INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
  INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
  INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
  INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT AND tpjs.OBLIGATOIRE = 1) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
  LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
  WHERE COALESCE(vheures.TOTAL_HEURES, 0) >= COALESCE(tpjs.SEUIL_HETD, 0)
  GROUP BY I.ID, I.SOURCE_CODE
), FOURNI AS (
  -- nombres de pj OBLIGATOIRES FOURNIES par chaque intervenant
  SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
  FROM INTERVENANT_EXTERIEUR IE
  INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
  INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
  INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
  INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT AND tpjs.OBLIGATOIRE = 1) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
  INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
  INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
  INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
  WHERE tpjFourni.ID = tpjAttendu.ID
  %s
  %s
  GROUP BY I.ID, I.SOURCE_CODE
)
SELECT A.INTERVENANT_ID ID --, A.SOURCE_CODE, A.NB NB_PJ_ATTENDU, COALESCE(F.NB, 0) NB_PJ_FOURNI 
FROM ATTENDU A
LEFT JOIN FOURNI F ON F.INTERVENANT_ID = A.INTERVENANT_ID
WHERE A.NB <= COALESCE(F.NB, 0) -- au moins autant de PJ fournies que de PJ attendues
%s
--ORDER BY A.INTERVENANT_ID
EOS;
        
        $andFichier = null;
        if (is_bool($this->getAvecFichier())) {
            $andFichier = "AND EXISTS (SELECT * FROM PIECE_JOINTE_FICHIER pjf INNER JOIN FICHIER f ON pjf.FICHIER_ID = f.ID AND (f.HISTO_DESTRUCTION IS NULL AND f.HISTO_DESTRUCTEUR_ID IS NULL) WHERE pjf.PIECE_JOINTE_ID = pj.ID) ";
        }
        
        $andValidation = null;
        if (is_bool($this->getAvecValidation())) {
            $andValidation = "AND pj.VALIDATION_ID IS NOT NULL ";
        }
        
        $andIntervenant = null;
        if ($this->getIntervenant()) {
            $andIntervenant = "AND A.INTERVENANT_ID = :intervenant";
        }
        
        $sql = sprintf($sqlTemplate, 
                $andFichier,
                $andValidation, 
                $andIntervenant);
        
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
        if (null === $this->getTotalHETDIntervenant()) {
            throw new LogicException("Le total HETD de l'intervenant doit être spécifié.");
        }
        
        // liste des PJ déjà fournies
        $pjFournies = $this->getPiecesJointesFournies();

        // liste des (types de) pièces justificatives déjà fournies
        $typesFournis = array();
        foreach ($pjFournies as $pj) {
            $typesFournis[$pj->getType()->getId()] = $pj->getType();
        }

        $service = $this->getServiceTypePieceJointeStatut();

        // liste des (types de) pièces justificatives à fournir selon le statut d'intervenant
        $qb = $service->finderByStatutIntervenant($this->getIntervenant()->getDossier()->getStatut());
        $qb = $service->finderByPremierRecrutement($this->getIntervenant()->getDossier()->getPremierRecrutement(), $qb);
        $typesPieceJointeStatut = $service->getList($qb);

        // recherche des (types de) pièces justificatives obligatoires non fournies
        $typesPieceJointeObligatoiresNonFournis = [];
        foreach ($typesPieceJointeStatut as $tpjs) { /* @var $tpjs TypePieceJointeStatut */
            if (array_key_exists($tpjs->getType()->getId(), $typesFournis)) {
                continue;
            }
            if (!$tpjs->isObligatoire($this->getTotalHETDIntervenant())) {
                continue;
            }
            $typesPieceJointeObligatoiresNonFournis[$tpjs->getType()->getId()] = $tpjs->getType();
        }
         
        return $typesPieceJointeObligatoiresNonFournis;
    }
    
    /**
     * Recherche les PJ fournies.
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
     * @var float
     */
    protected $totalHETDIntervenant;
    
    /**
     * Spécifie le total d'HETD de l'intervenant.
     * Ce total est pris en compte pour déterminer le caractère obligatoire de certain type de PJ.
     * 
     * @param float $totalHETDIntervenant
     * @return self
     */
    public function setTotalHETDIntervenant($totalHETDIntervenant)
    {
        $this->totalHETDIntervenant = $totalHETDIntervenant;
        
        return $this;
    }

    /**
     * Retourne le total d'HETD de l'intervenant pris en considération.
     * 
     * @return float
     */
    public function getTotalHETDIntervenant()
    {
        return $this->totalHETDIntervenant;
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