<?php

namespace Application\Service;

use Application\Entity\Db\Indicateur as IndicateurEntity;
use Application\Entity\Db\NotificationIndicateur as NotificationIndicateurEntity;
use Application\Entity\Db\Personnel as PersonnelEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Common\Exception\LogicException;
use DateTime;
use UnicaenApp\Traits\MessageAwareInterface;
use UnicaenApp\Traits\MessageAwareTrait;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NotificationIndicateur extends AbstractEntityService
{
    use MessageAwareTrait;
    
    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\NotificationIndicateur';
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
    
    /**
     * Abonne un personnel à un indicateur.
     * 
     * @param PersonnelEntity $personnel
     * @param IndicateurEntity $indicateur
     * @param string $frequence
     * @param StructureEntity $structure
     * @return NotificationIndicateurEntity
     */
    public function abonner(PersonnelEntity $personnel, IndicateurEntity $indicateur, $frequence, StructureEntity $structure = null)
    {
        // recherche d'abonnement existant
        $qb = $this->finderByPersonnel($personnel);
        $this->finderByIndicateur($indicateur, $qb);
        $this->finderByStructure($structure, $qb);
        $abonnement = $qb->getQuery()->getOneOrNullResult();
        
        $structureStr = $structure ? "pour la structure $structure" : null;
        
        if (null === $abonnement) {
            $abonnement = new NotificationIndicateurEntity();
            $abonnement
                    ->setPersonnel($personnel)
                    ->setIndicateur($indicateur)
                    ->setFrequence($frequence)
                    ->setStructure($structure)
                    ->setDateAbonnement(new DateTime());
            $this->getEntityManager()->persist($abonnement);
            $this->getEntityManager()->flush($abonnement);
            $message = "Abonnement de $personnel ({$personnel->getEmail()}) $structureStr enregistré avec succès.";
        }
        // une frequence spécifiée = abonnement
        elseif ($frequence) {
            if (!array_key_exists($frequence, NotificationIndicateurEntity::$frequences)) {
                throw new LogicException("Fréquence spécifiée inconnue: '$frequence'.");
            }
            $abonnement
                    ->setFrequence($frequence)
                    ->setDateAbonnement(new DateTime());
            $this->getEntityManager()->flush($abonnement);
            $message = "Abonnement de $personnel ({$personnel->getEmail()}) $structureStr modifié avec succès.";
        }
        // aucune frequence spécifiée = désabonnement
        else {
            $this->getEntityManager()->remove($abonnement);
            $message = "Abonnement de $personnel $structureStr supprimé avec succès.";
            $this->getEntityManager()->flush($abonnement);
            $abonnement = null;
        }
            
        $this->addMessage($message, MessageAwareInterface::SUCCESS);
        
        return $abonnement;
    }
    
    /**
     * Recherche des notifications à faire concernant les indicateurs.
     * 
     * La notification est à faire si l'une des conditions suivantes est remplie :
     * - aucune notification n'a encore été faite (i.e. date de dernière notification = null) ;
     * - l'âge de la dernière notification est supérieur à la fréquence de notification.
     * 
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findNotificationsIndicateurs()
    {
        $qb = $this->getRepo()->createQueryBuilder("ni")
                ->select("ni, p, i")
                ->join("ni.personnel", "p")
                ->join("ni.indicateur", "i")
                ->orderBy("i.ordre")
                ->andWhere("ni.dateDernNotif IS NULL OR ni.dateDernNotif + ni.frequence/(24*60*60) <= :now")
                ->setParameter('now', new DateTime());
//        print_r($qb->getQuery()->getSQL());
//        var_dump($qb->getQuery()->getParameters());
        
        return $qb->getQuery()->getResult();
    }
}