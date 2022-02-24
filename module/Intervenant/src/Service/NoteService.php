<?php

namespace Intervenant\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractEntityService;
use Doctrine\Common\Collections\ArrayCollection;
use Intervenant\Entity\Db\Note;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of NoteService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Note::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'note';
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return Array
     */

    public function getByIntervenant(Intervenant $intervenant): array
    {
        $qb = $this->finderByIntervenant($intervenant);
        $this->finderByHistorique($qb);
        $notes = $this->getList();
      

        return $notes;
    }



    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     */
    public function newEntity(): Note
    {
        /** @var Note $entity */
        $note = parent::newEntity();

        return $note;
    }
}