<?php

namespace Application\Entity\Db;

/**
 * Interface des entités possédant une gestion d'historique.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface HistoriqueAwareInterface
{
    /**
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return IntervenantExterieur
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null);

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique();
}