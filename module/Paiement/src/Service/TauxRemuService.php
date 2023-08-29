<?php

namespace Paiement\Service;


use Application\Controller\Plugin\Axios;
use Application\Service\AbstractEntityService;
use Doctrine\Common\Collections\ArrayCollection;
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
    public
    function formatTauxRemus(array $tauxRemus)
    {
        $result = [];
        foreach ($tauxRemus as $tr) {
            $id = $tr->getId();

            $result[$id] = (string)$tr;
        }

        ksort($result);

        return $result;
    }



    public
    function newEntityValeur(): TauxRemuValeur
    {
        return new tauxRemuValeur();
    }
}





?>