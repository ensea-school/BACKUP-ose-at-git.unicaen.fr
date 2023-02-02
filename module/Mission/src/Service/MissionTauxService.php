<?php

namespace Mission\Service;


use Application\Controller\Plugin\Axios;
use Application\Service\AbstractEntityService;
use DateTime;
use Mission\Entity\Db\MissionTauxRemu;
use Mission\Entity\Db\MissionTauxRemuValeur;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Description of MissionTauxService
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTauxService extends AbstractEntityService
{
    use SessionContainerTrait;

    /**
     * retourne la classe des entités
     *
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return MissionTauxRemu::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'MissionTaux';
    }



    /**
     * @return MissionTauxRemu[]
     */
    public function getTauxRemus(): array
    {
        $dql   = "SELECT mtr, mtrv, mtrp
                 FROM " . MissionTauxRemu::class . " mtr
                 LEFT JOIN mtr.missionTauxRemu mtrp
                 LEFT JOIN mtr.tauxRemuValeurs mtrv
                 WHERE mtr.histoDestruction IS NULL
                 ORDER BY mtr.id";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }



    public function getTauxRemusIndexable(): array
    {
        $dql   = "SELECT mtr, mtrv
                 FROM " . MissionTauxRemu::class . " mtr
                 LEFT JOIN mtr.tauxRemuValeurs mtrv
                 WHERE mtr.histoDestruction IS NULL
                 AND mtr.missionTauxRemu IS NULL
                 ORDER BY mtr.id";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }



    public function getTauxRemusValeur(mixed $tauxRemuValeurId)
    {
        $dql    = "SELECT mtr
                 FROM " . MissionTauxRemuValeur::class . " mtr
                 WHERE mtr.id =" . $tauxRemuValeurId
        ." ORDER BY mtr.id";
        $query  = $this->getEntityManager()->createQuery($dql);
        $result = $query->getResult();
        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }



    public function missionTauxWs(MissionTauxRemu $tauxRemu): ?array
    {
        $json = Axios::extract($tauxRemu, [
            'code',
            'libelle',
            'missionTauxRemu',
            ['tauxRemuValeurs', ['dateEffet', 'valeur']],
            'tauxRemuValeursIndex',
        ]);

        return $json;
    }

    public function getTauxRemusAnnee($tauxRemus)
    {

        $annee = $this->getServiceContext()->getAnnee()->getId();


        $result = [];
        /** @var MissionTauxRemu $tauxRemu */
        foreach ($tauxRemus as $tauxRemu) {
            /** @var MissionTauxRemuValeur $valeur */
            /** @var MissionTauxRemuValeur $temp */
            $valeurs = $tauxRemu->getValeurAnnee($annee);
            $tauxRemu->setValeurs($valeurs);
            $result[$tauxRemu->getId()] = $tauxRemu;
        }

        return $result;
    }



    public function newEntityValeur(): MissionTauxRemuValeur
    {
        return new missionTauxRemuValeur();
    }



    /**
     * @param MissionTauxRemu                   $tauxRemu
     * @param MissionTauxRemuValeur|string|null $temp
     * @param string                            $dateDebutAnnee
     * @param array                             $valeurs
     * @param string                            $dateFinAnnee
     *
     * @return array
     * @throws \Exception
     */

}





?>