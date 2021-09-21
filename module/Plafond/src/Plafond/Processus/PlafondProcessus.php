<?php

namespace Plafond\Processus;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Plafond\Service\PlafondServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;


/**
 * Description of PlafondProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondProcessus implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use PlafondServiceAwareTrait;

    /**
     * @var FlashMessenger
     */
    private $flashMessenger;



    /**
     * PlafondProcessus constructor.
     */
    public function __construct(FlashMessenger $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }



    /**
     * @return PlafondProcessus
     */
    public function beginTransaction(): self
    {
        $this->getEntityManager()->beginTransaction();

        return $this;
    }



    /**
     * @param Intervenant       $entity
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return bool
     */
    public function endTransaction(Intervenant $entity, TypeVolumeHoraire $typeVolumeHoraire, bool $isDiminution = false): bool
    {
        $this->getEntityManager()->flush();

        if ($isDiminution) {
            $passed = true; // ça passe à tous les coups si on diminue le volume d'heures
        } else {
            $passed = $this->controle($entity, $typeVolumeHoraire, true);
        }

        if ($passed) {
            $this->getEntityManager()->commit();
            $this->getServicePlafond()->calculerDepuisEntite($entity); // on met à jour les TBLs
        } else {
            $this->getEntityManager()->rollback();
        }

        return $passed;
    }



    /**
     * @param Intervenant       $entity
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return bool
     */
    public function controle($entity, TypeVolumeHoraire $typeVolumeHoraire, bool $pourBlocage = false): bool
    {
        $blocage = false;
        $reponse = $this->getServicePlafond()->controle($typeVolumeHoraire, $entity, $pourBlocage);
        if (!empty($reponse)) {
            foreach ($reponse as $controle) {
                if ($controle->isBloquant()) {
                    $blocage = true;
                    $this->flashMessenger->addErrorMessage((string)$controle);
                } elseif ($controle->isDepassement()) {
                    $this->flashMessenger->addWarningMessage((string)$controle);
                }
            }
        }

        return !$blocage;
    }



    /**
     *
     */
    public function construire()
    {
        $this->getServicePlafond()->construire();
    }
}