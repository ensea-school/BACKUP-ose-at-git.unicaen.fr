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
        $this->getBdd()->setDoctrineConnection($this->getEntityManager()->getConnection());

        return $this;
    }



    public function endTransaction(PlafondDataInterface|array $entities, TypeVolumeHoraire $typeVolumeHoraire, bool $isDiminution = false): bool
    {
        if (!is_array($entities)){
            $entities = [$entities];
        }


        // ça passe à tous les coups si on diminue le volume d'heures
        // sinon, un seul contrôle groupé pour toutes les entités
        $passed = $isDiminution || $this->controle($entities, $typeVolumeHoraire, true);

        if ($passed) {
            try {
                $this->getEntityManager()->commit();
                // on met à jour les TBLs, chaque calcul n'étant lancé qu'une seule fois
                $this->getServicePlafond()->calculerDepuisEntites($entities);
            }catch(ConnectionException $e){
                $this->getEntityManager()->rollback();
            }
        } else {
            $this->getEntityManager()->rollback();
        }

        $this->getBdd()->setDoctrineConnection(null);
        return $passed;
    }



    /**
     * @param PlafondDataInterface|PlafondDataInterface[] $entities
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return bool
     */
    public function controle(PlafondDataInterface|array $entities, TypeVolumeHoraire $typeVolumeHoraire, bool $pourBlocage = false): bool
    {
        $blocage = false;
        $reponse = $this->getServicePlafond()->controle($entities, $typeVolumeHoraire, $pourBlocage);
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