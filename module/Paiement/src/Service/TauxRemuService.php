<?php

namespace Paiement\Service;


use Application\Controller\Plugin\Axios;
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



    /**
     * @return TauxRemu[]
     */
    public function getTauxRemus(): array
    {
        $dql   = "SELECT tr, trv, trp
                 FROM " . TauxRemu::class . " tr
                 LEFT JOIN tr.tauxRemu trp
                 LEFT JOIN tr.tauxRemuValeurs trv
                 WHERE tr.histoDestruction IS NULL
                 ORDER BY tr.id";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }



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



    public function getTauxRemuValeur(mixed $tauxRemuValeurId)
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



    public function tauxWs(TauxRemu $tauxRemu): ?array
    {
        $json = Axios::extract($tauxRemu, [
            'code',
            'libelle',
            'tauxRemu',
            ['tauxRemuValeurs', ['dateEffet', 'valeur']],
            'tauxRemuValeursIndex',
        ]);

        return $json;
    }



    public function getTauxRemusAnnee($tauxRemus)
    {

        $annee = $this->getServiceContext()->getAnnee()->getId();


        $result = [];
        /** @var TauxRemu $tauxRemu */
        foreach ($tauxRemus as $tauxRemu) {
            /** @var TauxRemuValeur $valeur */
            /** @var TauxRemuValeur $temp */
            $valeurs = $tauxRemu->getValeurAnnee($annee);
            $tauxRemu->setValeurs($valeurs);
            $result[$tauxRemu->getId()] = $tauxRemu;
        }

        return $result;
    }



    /**
     * Formatte une liste d'entités CentreCout (centres de coûts et éventuels EOTP fils)
     * en tableau attendu par l'aide de vue FormSelect.
     *
     * NB: la liste en entrée doit être triées par code parent (éventuel) PUIS par code.
     *
     * @param TauxRemu[] $centresCouts
     */
    public function formatTauxRemus($tauxRemus)
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

    /**
     * @param TauxRemu                   $tauxRemu
     * @param TauxRemuValeur|string|null $temp
     * @param string                     $dateDebutAnnee
     * @param array                      $valeurs
     * @param string                     $dateFinAnnee
     *
     * @return array
     * @throws \Exception
     */

}





?>