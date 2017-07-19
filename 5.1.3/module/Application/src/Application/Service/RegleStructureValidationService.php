<?php

namespace Application\Service;

use Application\Entity\Db\RegleStructureValidation;
use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;

/**
 * Description of RegleStructureValidationService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method RegleStructureValidation get($id)
 * @method RegleStructureValidation[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method RegleStructureValidation newEntity()
 *
 */
class RegleStructureValidationService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return RegleStructureValidation::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'rsv';
    }



    /**
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param IntervenantEntity       $intervenant
     *
     * @return RegleStructureValidation
     */
    public function getBy(TypeVolumeHoraireEntity $typeVolumeHoraire, IntervenantEntity $intervenant)
    {
        $typeIntervenant = $intervenant->getStatut()->getTypeIntervenant();

        return $this->getRepo()->findOneBy(compact('typeVolumeHoraire', 'typeIntervenant'));
    }

}