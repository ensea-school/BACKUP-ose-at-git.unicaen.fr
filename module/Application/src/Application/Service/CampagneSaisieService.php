<?php

namespace Application\Service;

use Application\Entity\Db\CampagneSaisie;
use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Service\Traits\ContextAwareTrait;

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
    use ContextAwareTrait;



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
     * @param TypeIntervenantEntity         $typeIntervenant
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     *
     * @return CampagneSaisie
     */
    public function getBy(TypeIntervenantEntity $typeIntervenant, TypeVolumeHoraireEntity $typeVolumeHoraire)
    {
        $annee = $this->getServiceContext()->getAnnee();

        $result = $this->getRepo()->findOneBy([
            'annee'             => $annee,
            'typeIntervenant'   => $typeIntervenant,
            'typeVolumeHoraire' => $typeVolumeHoraire,
        ]);
        if (!$result) {
            $result = new CampagneSaisie();
            $result->setAnnee($annee);
            $result->setTypeIntervenant($typeIntervenant);
            $result->setTypeVolumeHoraire($typeVolumeHoraire);
        }

        return $result;
    }
}