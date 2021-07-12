<?php

namespace Plafond\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractEntityService;
use Plafond\Entity\Db\Plafond;
use Application\Entity\Db\TypeVolumeHoraire;
use Plafond\Entity\PlafondDepassement;

/**
 * Description of PlafondService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Plafond get($id)
 * @method Plafond[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Plafond newEntity()
 *
 */
class PlafondService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return Plafond::class;
    }



    /**
     * @param Intervenant       $intervenant
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return PlafondDepassement[]
     */
    public function controle(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire): array
    {
        $sql          = file_get_contents('data/Query/plafond.sql');
        $sql          = str_replace('/*i.id*/', 'AND intervenant_id = ' . $intervenant->getId(), $sql) . ' AND tvh.id = ' . $typeVolumeHoraire->getId();
        $sql          = preg_replace('/--(.*)\n/Uis', "\n", $sql);
        $res          = $this->getEntityManager()->getConnection()->fetchAll($sql);
        $depassements = [];
        foreach ($res as $r) {
            $depassements[] = $this->depassementFromArray($r);
        }

        return $depassements;
    }



    /**
     * @param array $a
     *
     * @return PlafondDepassement
     */
    private function depassementFromArray(array $a): PlafondDepassement
    {
        $depassement = new PlafondDepassement();
        $depassement->setPlafondLibelle($a['PLAFOND_LIBELLE']);
        if ($a['PLAFOND_ETAT_CODE'] == 'bloquant') $depassement->setBloquant(true);
        $depassement->setPlafond($a['PLAFOND']);
        $depassement->setHeures($a['HEURES']);

        return $depassement;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'plafond';
    }

}