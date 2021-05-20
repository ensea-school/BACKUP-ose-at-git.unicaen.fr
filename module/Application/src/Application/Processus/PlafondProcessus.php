<?php

namespace Application\Processus;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Traits\PlafondServiceAwareTrait;
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
     * @param Intervenant       $intervenant
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return bool
     */
    public function endTransaction(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, bool $isDiminution = false): bool
    {
        $this->getEntityManager()->flush();

        if ($isDiminution) {
            $passed = true; // ça passe à tous les coups si on diminue le volume d'heures
        } else {
            $passed = $this->controle($intervenant, $typeVolumeHoraire);
        }

        if ($passed) {
            $this->getEntityManager()->commit();
        } else {
            $this->getEntityManager()->rollback();
        }

        return $passed;
    }



    /**
     * @param Intervenant       $intervenant
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return bool
     */
    public function controle(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire): bool
    {
        $blocage = false;
        $reponse = $this->getServicePlafond()->controle($intervenant, $typeVolumeHoraire);
        if (!empty($reponse)) {
            foreach ($reponse as $plafondDepassement) {
                if ($plafondDepassement->isBloquant()) {
                    $blocage = true;
                    $this->flashMessenger->addErrorMessage((string)$plafondDepassement);
                } else {
                    $this->flashMessenger->addWarningMessage((string)$plafondDepassement);
                }
            }
        }

        return !$blocage;
    }

}