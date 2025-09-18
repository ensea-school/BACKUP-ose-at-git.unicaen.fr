<?php

namespace Indicateur\Service;

use Application\Entity\Db\Affectation;
use Application\Service\AbstractService;
use Indicateur\Entity\Db\Indicateur;
use Indicateur\Entity\Db\NotificationIndicateur;
use LogicException;
use DateTime;

/**
 * Description of NotificationIndicateurService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 */
class NotificationIndicateurService extends AbstractService
{
    use IndicateurServiceAwareTrait;


    /**
     * Abonne un personnel à un indicateur.
     */
    public function abonner(Indicateur $indicateur, ?string $frequence = null, bool $inHome = false, Affectation $affectation = null): ?NotificationIndicateur
    {
        $em = $this->getEntityManager();

        if ($frequence && !array_key_exists($frequence, NotificationIndicateur::$frequences)) {
            throw new LogicException("Fréquence spécifiée inconnue: $frequence.");
        }

        if (!$affectation) {
            $affectation = $this->getServiceContext()->getAffectation();
        }

        $notification = $em->getRepository(NotificationIndicateur::class)->findOneBy([
            'indicateur'  => $indicateur,
            'affectation' => $affectation,
        ]);

        if ($frequence || $inHome) {
            if (!$notification) {
                $notification = new NotificationIndicateur();
                $notification->setAffectation($affectation);
                $notification->setIndicateur($indicateur);
            }

            $notification->setFrequence($frequence);
            $notification->setInHome($inHome);
            $notification->setDateAbonnement(new DateTime());

            $em->persist($notification);
            $em->flush($notification);
        } else {
            if ($notification) {
                $em->remove($notification);
                $em->flush($notification);
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
        $repo = $this->getEntityManager()->getRepository(NotificationIndicateur::class);

        $qb = $repo->createQueryBuilder("ni")
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

        return $nis;
    }

}