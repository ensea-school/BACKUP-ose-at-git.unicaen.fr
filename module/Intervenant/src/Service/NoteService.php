<?php

namespace Intervenant\Service;

use Application\Service\AbstractEntityService;
use Doctrine\DBAL\Query\QueryBuilder;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Note;

/**
 * Description of NoteService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteService extends AbstractEntityService
{

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
     * @param Intervenant $intervenant  Intervenant concerné
     * @param string|null $typeNoteCode Code du type de note souhaité, si null renvoi toutes les notes
     *
     * @return array
     */

    public function getByIntervenant(Intervenant $intervenant, ?string $typeNoteCode = null): array
    {
        /**
         * @var $qb QueryBuilder
         */
        $qb = $this->finderByIntervenant($intervenant);
        $this->finderByHistorique($qb);

        if (!empty($typeNoteCode)) {
            $type = $this->getServiceTypeNote()->getByCode($typeNoteCode);
            $qb->andWhere($this->getAlias() . '.type = :type');
            $qb->setParameter('type', $type);
        }
        $qb->orderBy($this->getAlias() . '.histoCreation', 'DESC');

        $notes = $this->getList($qb);


        return $notes;
    }



    public function getHistoriqueIntervenant(Intervenant $intervenant)
    {
        $historique = [];
        $sql        = 'SELECT * FROM v_intervenant_historique where intervenant_id =  ' . $intervenant->getId() . ' ORDER BY ordre ASC, histo_date ASC';
        $stmt       = $this->getEntityManager()->getConnection()->executeQuery($sql);
        while ($r = $stmt->fetch()) {
            $historique[$r['CATEGORIE']][] = [
                'id'             => $r['ID'],
                'intervenant_id' => $r['INTERVENANT_ID'],
                'label'          => $r['LABEL'],
                'histo_date'     => new \DateTime($r['HISTO_DATE']),
                'histo_user'     => $r['HISTO_USER'],
                'ordre'          => $r['ORDRE'],

            ];
        }

        return $historique;
    }



    /**
     * @param Intervenant $intervenant
     * @param             $sujet   string Sujet du mail
     * @param             $message string Contenu du mail
     *
     * @return Note
     */

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
     * @param Intervenant    $intervenant
     * @param \DateTime|null $since Date à partir de laquelle on calcule le nombre de note, si NULL on compte toutes les notes
     *
     * @return int
     */

    public function countNote(Intervenant $intervenant, ?\DateTime $since = null): int
    {


        $sql = "SELECT count(*) AS nb 
                FROM note n 
                JOIN type_note tn ON tn.id = n.type_note_id AND tn.code =  'note'
                WHERE intervenant_id =  " . $intervenant->getId() . " AND n.histo_destruction IS NULL";
        if (!empty($since)) {
            $sql .= " AND n.histo_creation > to_date('" . $since->format('d/m/Y') . "', 'dd/mm/yyyy')";
        }


        $count = $this->getEntityManager()->getConnection()->fetchOne($sql);

        return $count;
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