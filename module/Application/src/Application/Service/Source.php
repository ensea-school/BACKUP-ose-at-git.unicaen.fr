<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Source as SourceEntity;

/**
 * Description of Source
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Source extends AbstractEntityService
{
    use ContextProviderAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Source';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'src';
    }

    /**
     * Retourne l'entité source OSE
     *
     * @return SourceEntity
     */
    public function getOse()
    {
        return $this->getRepo()->findOneBy(array('code' => SourceEntity::CODE_SOURCE_OSE));
    }
}