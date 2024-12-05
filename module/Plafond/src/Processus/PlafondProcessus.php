<?php

namespace Plafond\Processus;

use Application\Processus\AbstractProcessus;
use Doctrine\DBAL\ConnectionException;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Service\PlafondServiceAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Unicaen\BddAdmin\BddAwareTrait;


/**
 * Description of PlafondProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondProcessus extends AbstractProcessus
{
    use PlafondServiceAwareTrait;
    use BddAwareTrait;

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
        $this->getBdd()->beginTransaction(); // plus nécessaire sous Postgresql

        return $this;
    }



    /**
     * @param PlafondDataInterface $entity
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return bool
     */
    public function endTransaction(PlafondDataInterface $entity, TypeVolumeHoraire $typeVolumeHoraire, bool $isDiminution = false): bool
    {
        //$this->getEntityManager()->flush();

        if ($isDiminution) {
            $passed = true; // ça passe à tous les coups si on diminue le volume d'heures
        } else {
            $passed = $this->controle($entity, $typeVolumeHoraire, true);
        }

        if ($passed) {
            try {
                $this->getEntityManager()->commit();
                $this->getBdd()->commitTransaction(); // plus nécessaire sous Postgresql
                $this->getServicePlafond()->calculerDepuisEntite($entity); // on met à jour les TBLs
            }catch(ConnectionException $e){
                $this->getEntityManager()->rollback();
                $this->getBdd()->rollbackTransaction(); // plus nécessaire sous Postgresql
            }
        } else {
            $this->getEntityManager()->rollback();
            $this->getBdd()->rollbackTransaction(); // plus nécessaire sous Postgresql
        }

        return $passed;
    }



    /**
     * @param PlafondDataInterface $entity
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return bool
     */
    public function controle(PlafondDataInterface $entity, TypeVolumeHoraire $typeVolumeHoraire, bool $pourBlocage = false): bool
    {
        $blocage = false;
        $reponse = $this->getServicePlafond()->controle($entity, $typeVolumeHoraire, $pourBlocage);
        if (!empty($reponse)) {
            foreach ($reponse as $controle) {
                if ($controle->isBloquant() && $controle->isDepassement()) {
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