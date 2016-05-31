<?php

namespace Application\Service;

use Application\Entity\Db\CampagneSaisie;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypeVolumeHoraire;

/**
 * Description of CampagneSaisieService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method CampagneSaisie get($id)
 * @method CampagneSaisie[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method CampagneSaisie newEntity()
 *
 */
class CampagneSaisieService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return CampagneSaisie::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'CampSaisie';
    }



    /**
     * @param TypeIntervenant   $typeIntervenant
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return CampagneSaisie
     */
    public function getBy(TypeIntervenant $typeIntervenant, TypeVolumeHoraire $typeVolumeHoraire)
    {
        $result = $this->getRepo()->findOneBy([
            'typeIntervenant'   => $typeIntervenant,
            'typeVolumeHoraire' => $typeVolumeHoraire,
        ]);
        if (!$result){
            $result = new CampagneSaisie();
            $result->setTypeIntervenant($typeIntervenant);
            $result->setTypeVolumeHoraire($typeVolumeHoraire);
        }
        return $result;
    }
}