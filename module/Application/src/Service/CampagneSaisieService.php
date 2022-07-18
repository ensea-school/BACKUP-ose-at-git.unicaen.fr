<?php

namespace Application\Service;

use Service\Entity\Db\CampagneSaisie;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Service\Traits\ContextServiceAwareTrait;

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
    use ContextServiceAwareTrait;


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