<?php

namespace Indicateur\Service;

use Application\Entity\Db\Affectation;
use Application\Service\AbstractEntityService;
use Indicateur\Entity\Db\Indicateur;
use Indicateur\Entity\Db\NotificationIndicateur;
use Application\Service\Traits\AffectationServiceAwareTrait;
use LogicException;
use DateTime;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of WfEtapeDepService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method NotificationIndicateur get($id)
 * @method NotificationIndicateur[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method NotificationIndicateur newEntity()
 *
 */
class NotificationIndicateurService extends AbstractEntityService
{
    use AffectationServiceAwareTrait;
    use IndicateurServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return NotificationIndicateur::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ni';
    }



    public function finderByRole($role = null, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $affectation = $this->getServiceAffectation()->getByRole($role);
        $this->finderByAffectation($affectation, $qb, $alias);

        return $qb;
    }



    /**
     * Abonne un personnel à un indicateur.
     *
     * @param Indicateur $indicateur
     * @param string     $frequence
     *
     * @return NotificationIndicateur
     */
    public function abonner(Indicateur $indicateur, $frequence = null, $inHome = false, Affectation $affectation = null)
    {
        if ($frequence && !array_key_exists($frequence, NotificationIndicateur::$frequences)) {
            throw new LogicException("Fréquence spécifiée inconnue: $frequence.");
        }

        if (!$affectation) {
            $affectation = $this->getServiceAffectation()->getByRole();
        }

        $notification = $this->getRepo()->findOneBy([
            'indicateur'  => $indicateur,
            'affectation' => $affectation,
        ]);

        if ($frequence || $inHome) {
            if (!$notification) {
                $notification = $this->newEntity();
                $notification->setAffectation($affectation);
                $notification->setIndicateur($indicateur);
            }

            $notification->setFrequence($frequence);
            $notification->setInHome($inHome);
            $notification->setDateAbonnement(new DateTime());

            $this->save($notification);
        } else {
            if ($notification) {
                $this->delete($notification);
                $notification = null;
            }
        }

        return $notification;
    }



    /**
     * Recherche des notifications à faire concernant les indicateurs.
     *
     * La notification est à faire si l'une des conditions suivantes est remplie :
     * - aucune notification n'a encore été faite (i.e. date de dernière notification = null) ;
     * - l'âge de la dernière notification est supérieur à la fréquence de notification.
     *
     * @param bool $force Si true, toutes les notifications sont considérées comme devant être faites
     *
     * @return \Indicateur\Entity\Db\NotificationIndicateur[]
     */
    public function getNotifications($force = false)
    {
        $qb = $this->getRepo()->createQueryBuilder("ni")
            ->select("ni, i, a, u, s")
            ->join('ni.indicateur', 'i')
            ->join('ni.affectation', 'a')
            ->join('a.utilisateur', 'u')
            ->leftJoin('a.structure', 's')
            ->andWhere('ni.frequence IS NOT NULL')
            ->andWhere('i.enabled = true')
            ->andWhere('a.histoDestruction IS NULL');

        if (!$force) {
            $now = new \DateTime();
            $now->setTime($now->format('H'), 0, 0); // raz minutes et secondes
            $qb->andWhere("ni.dateDernNotif IS NULL OR ni.dateDernNotif + ni.frequence/(24*60*60) <= :now")
                ->setParameter('now', $now);
        }

        $nis = $qb->getQuery()->getResult();
        /* @var $nis \Indicateur\Entity\Db\NotificationIndicateur[] */
        foreach ($nis as $ni) {
            $ni->getIndicateur()->setServiceIndicateur($this->getServiceIndicateur());
        }

        return $nis;
    }

}