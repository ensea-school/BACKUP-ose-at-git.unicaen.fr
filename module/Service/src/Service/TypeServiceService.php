<?php

namespace Service\Service;


use Application\Service\AbstractEntityService;
use Service\Entity\Db\TypeService;

/**
 * Description of TypeServiceService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeServiceService extends AbstractEntityService
{
    private array $cache = [];



    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass()
    {
        return TypeService::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tsrv';
    }



    public function getEnseignement()
    {
        return $this->getByCode(TypeService::CODE_ENSEIGNEMENT);
    }



    public function getReferentiel()
    {
        return $this->getByCode(TypeService::CODE_REFERENTIEL);
    }



    public function getMission()
    {
        return $this->getByCode(TypeService::CODE_MISSION);
    }



    /**
     *
     * @param string $code
     *
     * @return TypeService
     */
    public function getByCode($code)
    {
        if (!isset($this->cache[$code])) {
            $this->cache[$code] = $this->getRepo()->findOneBy(['code' => $code]);
        }

        return $this->cache[$code];
    }
}