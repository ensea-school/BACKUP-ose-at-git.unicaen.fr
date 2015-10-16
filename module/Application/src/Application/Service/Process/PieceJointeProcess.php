<?php

namespace Application\Service\Process;

use Application\Entity\Db\Dossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Service\AbstractService;
use Common\Exception\LogicException;
use Common\Exception\PieceJointe\AucuneAFournirException;
use Application\Service\Traits\TypePieceJointeStatutAwareTrait;
use Application\Service\Traits\PieceJointeAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;

/**
 * Processus de gestion de la liste de pièces à fournir pour un dossier vacataire.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PieceJointeProcess extends AbstractService
{
    use TypePieceJointeStatutAwareTrait;
    use PieceJointeAwareTrait;
    use ServiceAwareTrait;
    
    protected $typesPieceJointeStatut;

    /**
     *
     * @return array type_id => TypePieceJointeStatut
     */
    public function getTypesPieceJointeStatut()
    {
        if (null === $this->typesPieceJointeStatut) {
            $qb = $this->getServiceTypePieceJointeStatut()->finderByStatutIntervenant($this->getStatut());
            $qb = $this->getServiceTypePieceJointeStatut()->finderByPremierRecrutement($this->getDossier()->getPremierRecrutement(), $qb);
            $this->typesPieceJointeStatut = [];
            foreach ($this->getServiceTypePieceJointeStatut()->getList($qb) as $tpjs) {
                $typeId = $tpjs->getType()->getId();
                if (isset($this->typesPieceJointeStatut[$typeId])) {
                    throw new LogicException("Anomalie: plusieurs TypePieceJointeStatut trouvés pour un même TypePieceJointe.");
                }
                $this->typesPieceJointeStatut[$typeId] = $tpjs;
            }
        }

        return $this->typesPieceJointeStatut;
    }

    protected $typesPieceJointeAttendus;

    /**
     *
     * @return array id => TypePieceJointe
     */
    public function getTypesPieceJointeAttendus()
    {
        if (null === $this->typesPieceJointeAttendus) {
            $this->typesPieceJointeAttendus = [];
            foreach ($this->getTypesPieceJointeStatut() as $typePieceJointeStatut) { /* @var $typePieceJointeStatut TypePieceJointeStatut */
                $type = $typePieceJointeStatut->getType();
                $this->typesPieceJointeAttendus[$type->getId()] = $type;
            }
        }

        return $this->typesPieceJointeAttendus;
    }

    protected $piecesJointesFournies;

    /**
     *
     * @return array type_id => PieceJointe
     */
    public function getPiecesJointesFournies()
    {
        if (null === $this->piecesJointesFournies) {
            $qb = $this->getServicePieceJointe()->finderByDossier($this->getIntervenant()->getDossier());
            $this->getServicePieceJointe()->finderByExistsFichier(true, $qb);
//            $this->getServicePieceJointe()->finderByExistsValidation(true, $qb);
            $piecesJointes = $qb->getQuery()->getResult();

            $this->piecesJointesFournies = [];
            foreach ($piecesJointes as $pj) { /* @var $pj PieceJointe */
                // NB: il ne peut y avoir qu'une seule pièce par type de pièce jointe
                $this->piecesJointesFournies[$pj->getType()->getId()] = $pj;
            }
        }

        return $this->piecesJointesFournies;
    }

    protected $piecesJointes;

    /**
     *
     * @return array type_id => PieceJointe
     */
    public function getPiecesJointes()
    {
        if (null === $this->piecesJointes) {
            $qb = $this->getServicePieceJointe()->finderByDossier($this->getIntervenant()->getDossier());
//            $this->getServicePieceJointe()->finderByExistsFichier(true, $qb);
//            $this->getServicePieceJointe()->finderByExistsValidation(true, $qb);
            $qb->orderBy("pj.type");
            $piecesJointes = $qb->getQuery()->getResult();

            $this->piecesJointes = [];
            foreach ($piecesJointes as $pj) { /* @var $pj PieceJointe */
                // NB: il ne peut y avoir qu'une seule pièce par type de pièce jointe
                $this->piecesJointes[$pj->getType()->getId()] = $pj;
            }
        }

        return $this->piecesJointes;
    }

    /**
     *
     * @param int|TypePieceJointe $type
     * @return PieceJointe|null
     */
    public function getPieceJointeFournie($type)
    {
        $type = $type instanceof TypePieceJointe ? $type->getId() : $type;

        foreach ($this->getPiecesJointesFournies() as $pj) { /* @var $pj PieceJointe */
            if ($type === $pj->getType()->getId()) {
                return $pj;
            }
        }

        return null;
    }

    /**
     * @deprecated Implémenter le vrai calcul d'HETD ?
     */
    public function getTotalHeuresReellesIntervenant()
    {
        return $this->getServicePieceJointe()->getTotalHeuresReelles($this->getIntervenant());
    }

    /**
     * @var Intervenant
     */
    private $intervenant;

    /**
     *
     * @param Intervenant $intervenant
     * @return \Application\Service\DossierProcess
     * @throws AucuneAFournirException
     */
    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;

        $this->piecesJointesFournies    = null;
        $this->typesPieceJointeFournis  = null;
        $this->typesPieceJointeAttendus = null;
        $this->typesPieceJointeStatut   = null;

//        if (!$this->getTypesPieceJointeStatut()) {
//            throw new AucuneAFournirException(
//                    "Aucun type de pièce justificative à fournir n'a été trouvé pour l'intervenant {$this->getIntervenant()} "
//                    . "(dont le statut est '{$this->getStatut()}').");
//        }

        return $this;
    }

    /**
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * @return Dossier
     */
    private function getDossier()
    {
        $dossier = $this->getIntervenant()->getDossier();
        if (!$dossier) {
            throw new LogicException("L'intervenant spécifié n'a pas de données personnelles enregistrées.");
        }
        return $dossier;
    }

    /**
     * @return StatutIntervenant
     */
    public function getStatut()
    {
        return $this->getDossier()->getStatut();
    }
}
