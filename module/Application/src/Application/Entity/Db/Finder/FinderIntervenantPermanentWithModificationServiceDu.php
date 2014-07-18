<?php

namespace Application\Entity\Db\Finder;

/**
 * Requêteur contextualisé d'intervenants permanents avec jointure sur les
 * modifications de service dû éventuelles.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see \Application\Service\ContextProvider
 */
class FinderIntervenantPermanentWithModificationServiceDu extends AbstractIntervenantFinder
{
    /**
     * 
     * @return self
     */
    protected function createQuery()
    {
        $this
                ->select('i, msd')
                ->from('Application\Entity\Db\IntervenantPermanent', 'i')
                ->leftJoin('i.modificationServiceDu', 'msd')
                ->leftJoin('msd.motif', 'm')
                ->orderBy('m.libelle');

        return $this;
    }
}