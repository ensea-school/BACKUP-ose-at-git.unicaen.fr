<?php

namespace Intervenant\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Note;
use Laminas\Mail\Message;

/**
 * Description of NoteService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteService extends AbstractEntityService
{

    use ContextServiceAwareTrait;
    use TypeNoteServiceAwareTrait;

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
        $notes = $this->getList($qb);


        return $notes;
    }

    public function getHistoriqueIntervenant(Intervenant $intervenant)
    {
        $historique = [];
        $sql = 'SELECT * FROM v_intervenant_historique where intervenant_id =  ' . $intervenant->getId() . ' ORDER BY histo_date DESC';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        while ($r = $stmt->fetch()) {

            $historique[] = [
                'id'             => $r['ID'],
                'intervenant_id' => $r['INTERVENANT_ID'],
                'label'          => $r['LABEL'],
                'histo_date'     => new \DateTime($r['HISTO_DATE']),
                'histo_user'     => $r['HISTO_USER'],

            ];
        }

        return $historique;

    }

    public function createNoteFromEmail(Intervenant $intervenant, $sujet, $message): Note
    {
        $note = $this->newEntity();
        $note->setIntervenant($intervenant);
        $note->setLibelle($sujet);
        $note->setContenu($message);
        $note->setType($this->getServiceTypeNote()->getByCode('email'));
        $this->save($note);

        return $note;

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