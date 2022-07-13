<?php

namespace Application\Service;

use Application\Entity\Db\RegleStructureValidation;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Intervenant;

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
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param Intervenant       $intervenant
     *
     * @return RegleStructureValidation
     */
    public function getBy(TypeVolumeHoraire $typeVolumeHoraire, Intervenant $intervenant)
    {
        $typeIntervenant = $intervenant->getStatut()->getTypeIntervenant();

        return $this->getRepo()->findOneBy(compact('typeVolumeHoraire', 'typeIntervenant'));
    }

}