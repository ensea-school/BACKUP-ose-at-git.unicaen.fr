<?php

namespace Intervenant\Service;

use Application\Service\AbstractEntityService;
use Intervenant\Entity\Db\TypeNote;


/**
 * Description of TypeNoteService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class TypeNoteService extends AbstractEntityService
{

    public function getEntityClass()
    {
        return TypeNote::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'typeNote';
    }



    public function newEntity(): TypeNote
    {
        /** @var TypeNote $entity */
        $typeNote = parent::newEntity();

        return $typeNote;
    }
}