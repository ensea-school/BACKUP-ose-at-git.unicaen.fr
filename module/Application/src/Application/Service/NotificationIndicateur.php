<?php

namespace Application\Service;

use Application\Entity\Db\Indicateur as IndicateurEntity;
use Application\Entity\Db\NotificationIndicateur as NotificationIndicateurEntity;
use Application\Entity\Db\Personnel as PersonnelEntity;
use UnicaenApp\Traits\MessageAwareInterface;
use UnicaenApp\Traits\MessageAwareTrait;
use Common\Exception\LogicException;

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
     * @return NotificationIndicateurEntity
     */
    public function abonner(PersonnelEntity $personnel, IndicateurEntity $indicateur, $frequence)
    {
        // recherche d'abonnement existant
        $qb = $this->finderByPersonnel($personnel);
        $this->finderByIndicateur($indicateur, $qb);
        $abonnement = $qb->getQuery()->getOneOrNullResult();
        
        if (null === $abonnement) {
            $abonnement = new NotificationIndicateurEntity();
            $abonnement
                    ->setPersonnel($personnel)
                    ->setIndicateur($indicateur)
                    ->setFrequence($frequence)
                    ->setDateAbonnement(new \DateTime());
            $this->getEntityManager()->persist($abonnement);
            $this->getEntityManager()->flush($abonnement);
            $message = "Abonnement de $personnel ({$personnel->getEmail()}) enregistré avec succès.";
        }
        // une frequence spécifiée = abonnement
        elseif ($frequence) {
            if (!array_key_exists($frequence, NotificationIndicateurEntity::$frequences)) {
                throw new LogicException("Fréquence spécifiée inconnue: '$frequence'.");
            }
            $abonnement
                    ->setFrequence($frequence)
                    ->setDateAbonnement(new \DateTime());
            $this->getEntityManager()->flush($abonnement);
            $message = "Abonnement de $personnel ({$personnel->getEmail()}) modifié avec succès.";
        }
        // aucune frequence spécifiée = désabonnement
        else {
            $this->getEntityManager()->remove($abonnement);
            $message = "Abonnement de $personnel supprimé avec succès.";
            $this->getEntityManager()->flush($abonnement);
            $abonnement = null;
        }
            
        $this->addMessage($message, MessageAwareInterface::SUCCESS);
        
        return $abonnement;
    }
}