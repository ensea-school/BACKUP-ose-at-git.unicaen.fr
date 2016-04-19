<?php

namespace Application\Service;

use Application\Entity\Db\TypeContrat as TypeContratEntity;

/**
 * Description of TypeContrat
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeContrat extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeContratEntity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'typec';
    }



    public function getContrat()
    {
        return $this->getByCode(TypeContratEntity::CODE_CONTRAT);
    }



    public function getAvenant()
    {
        return $this->getByCode(TypeContratEntity::CODE_AVENANT);
    }



    /**
     *
     * @param string $code
     *
     * @return TypeContratEntity
     */
    public function getByCode($code)
    {
        if (!isset($this->cache[$code])) {
            $this->cache[$code] = $this->getRepo()->findOneBy(['code' => $code]);
        }

        return $this->cache[$code];
    }

}