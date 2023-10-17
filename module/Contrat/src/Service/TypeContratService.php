<?php

namespace Contrat\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Contrat\Entity\Db\TypeContrat;

/**
 * Description of TypeContrat
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeContratService extends AbstractEntityService
{

    private array $cache = [];

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeContrat::class;
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
        return $this->getByCode(TypeContrat::CODE_CONTRAT);
    }



    public function getAvenant()
    {
        return $this->getByCode(TypeContrat::CODE_AVENANT);
    }



    /**
     *
     * @param string $code
     *
     * @return TypeContrat
     */
    public function getByCode($code)
    {
        if (!isset($this->cache[$code])) {
            $this->cache[$code] = $this->getRepo()->findOneBy(['code' => $code]);
        }

        return $this->cache[$code];
    }

}