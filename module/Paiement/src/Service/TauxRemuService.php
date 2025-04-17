<?php

namespace Paiement\Service;


use Application\Service\AbstractEntityService;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Entity\Db\TauxRemuValeur;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Description of TauxService
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxRemuService extends AbstractEntityService
{
    use SessionContainerTrait;

    /**
     * retourne la classe des entités
     *
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return TauxRemu::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'Taux';
    }



    public function getTauxMap(): array
    {
        $session = $this->getSessionContainer();
        if (!isset($session->tauxValeur)) {
            $tauxValeur = [];

            $sql = "
            SELECT
                tr.id, 
                tr.taux_remu_id parent_id, to_char( trv.date_effet, 'YYYY-mm-dd' ) date_effet, trv.valeur
            FROM
                taux_remu tr
                JOIN taux_remu_valeur trv ON trv.taux_remu_id = tr.id
            ORDER BY
                tr.id, trv.date_effet
            ";

            $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
            while ($tv = $stmt->fetchAssociative()) {
                $id = (int)$tv['ID'];
                $parent = (int)$tv['PARENT_ID'] ?: null;
                $dateEffet = $tv['DATE_EFFET'];
                $valeur = $tv['VALEUR'];

                if (!isset($tauxValeur[$id])) {
                    $tauxValeur[$id] = [
                        'parent'  => $parent,
                        'valeurs' => [],
                    ];
                }
                $tauxValeur[$id]['valeurs'][$dateEffet] = $valeur;

            }

            $session->tauxValeur = $tauxValeur;
        }

        return $session->tauxValeur;
    }



    public function tauxValeur(TauxRemu|int $tauxRemu, \DateTime|string $date): float
    {
        $tauxValeur = $this->getTauxMap();

        if ($tauxRemu instanceof TauxRemu) {
            $tauxRemu = $tauxRemu->getId();
        }

        if (!isset($tauxValeur[$tauxRemu])){
            throw new \Exception('Taux de rémunération invalide : ID '.$tauxRemu.' inconnu');
        }


        if ($date instanceof \DateTime) {
            $date = $date->format('Y-m-d');
        }
        $valeur = 1.0; // pas de taux => coëf 1

        foreach($tauxValeur[$tauxRemu]['valeurs'] as $d => $v ){
            if ($d <= $date){
                $valeur = $v;
            }else{
                break;
            }
        }

        if (!empty($tauxValeur[$tauxRemu]['parent'])){
            $tauxParent = $this->tauxValeur($tauxValeur[$tauxRemu]['parent'], $date);
            return round( $valeur * $tauxParent, 2);
        }else{
            return $valeur;
        }
    }



    public function tauxDate(TauxRemu|int $tauxRemu, \DateTime|string $date): \DateTime
    {
        $tauxValeur = $this->getTauxMap();

        if ($tauxRemu instanceof TauxRemu) {
            $tauxRemu = $tauxRemu->getId();
        }

        if (!isset($tauxValeur[$tauxRemu])){
            throw new \Exception('Taux de rémunération invalide : ID '.$tauxRemu.' inconnu');
        }


        if ($date instanceof \DateTime) {
            $date = $date->format('Y-m-d');
        }
        $valeur = $date; // pas de taux => date de référence

        foreach($tauxValeur[$tauxRemu]['valeurs'] as $d => $v ){
            if ($d <= $date){
                $valeur = $d;
            }else{
                break;
            }
        }

        if (!empty($tauxValeur[$tauxRemu]['parent'])){
            $dateParent = $this->tauxDate($tauxValeur[$tauxRemu]['parent'], $date);
            if ($dateParent->format('Y-m-d') > $valeur){
                return $dateParent;
            }else{
                return new \DateTime($valeur);
            }
        }else{
            return new \DateTime($valeur);
        }
    }



    public function clearCache(): self
    {
        $session = $this->getSessionContainer();
        unset($session->tauxValeur);

        return $this;
    }



    /**
     * Retourne tous les taux de rémunération non historisé
     *
     * @return TauxRemu[]
     */
    public function getTauxRemus(): array
    {
        $dql   = "SELECT tr, trv, trp, str
                 FROM " . TauxRemu::class . " tr
                 LEFT JOIN tr.tauxRemu trp
                 LEFT JOIN tr.tauxRemuValeurs trv
                 LEFT JOIN tr.sousTauxRemu str WITH str.histoDestruction IS NULL
                 WHERE tr.histoDestruction IS NULL
                 ORDER BY tr.id";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }



    /**
     * Return les taux de rémunérations qui n'ont pas de taux de rémunération parents
     *
     * @return array
     */
    public function getTauxRemusIndexable(): array
    {
        $dql   = "SELECT mtr, mtrv
                 FROM " . TauxRemu::class . " mtr
                 LEFT JOIN mtr.tauxRemuValeurs mtrv
                 WHERE mtr.histoDestruction IS NULL
                 AND mtr.tauxRemu IS NULL
                 ORDER BY mtr.id";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }



    /**
     * @param int $tauxRemuValeurId
     *
     * @return TauxRemuValeur|null
     */
    public function getTauxRemuValeur(int $tauxRemuValeurId): ?TauxRemuValeur
    {
        $dql    = "SELECT mtr
                 FROM " . TauxRemuValeur::class . " mtr
                 WHERE mtr.id =" . $tauxRemuValeurId
            . " ORDER BY mtr.id";
        $query  = $this->getEntityManager()->createQuery($dql);
        $result = $query->getResult();
        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    /**
     * Retourne les taux Remu qui possèdent une valeurs sur l'année
     *
     * @return array|null
     */
    public function getTauxRemusAnneeWithValeur(): ?array
    {

        $dql   = "SELECT tr, trv, trp
                 FROM " . TauxRemu::class . " tr
                 LEFT JOIN tr.tauxRemu trp
                 LEFT JOIN tr.tauxRemuValeurs trv
                 WHERE tr.histoDestruction IS NULL
                 ORDER BY tr.id";
        $query = $this->getEntityManager()->createQuery($dql);

        $tauxRemus = $query->getResult();
        $annee     = $this->getServiceContext()->getAnnee();


        $result = [];
        /** @var TauxRemu $tauxRemu */
        foreach ($tauxRemus as $tauxRemu) {
            /** @var TauxRemuValeur[] $valeurs */
            $valeurs = $tauxRemu->getValeurAnnee($annee);
            if ($valeurs) {
                $tauxRemu->setValeurs($valeurs);
                $result[$tauxRemu->getId()] = $tauxRemu;
            }
        }

        return $result;
    }



    /**
     * Formatte une liste d'entités TauxRemus
     * en tableau attendu par l'aide de vue FormSelect.
     *
     *
     * @param TauxRemu[] $tauxRemus
     *
     * @return array
     */
    public function formatTauxRemus(array $tauxRemus)
    {
        $result = [];
        foreach ($tauxRemus as $tr) {
            $id = $tr->getId();

            $result[$id] = (string)$tr;
        }

        ksort($result);

        return $result;
    }



    public function newEntityValeur(): TauxRemuValeur
    {
        return new tauxRemuValeur();
    }
}