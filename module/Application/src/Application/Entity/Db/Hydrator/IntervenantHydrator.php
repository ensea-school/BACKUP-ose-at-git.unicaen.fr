<?php

namespace Application\Entity\Db\Hydrator;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Civilite;
use LogicException;

/**
 * Description of Intervenant
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantHydrator extends \Zend\Stdlib\Hydrator\AbstractHydrator
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        throw new LogicException("Pas implémenté.");
    }

    /**
     * Hydrate Intervenant $object with the provided $data.
     *
     * @param  array $data
     * @param  Intervenant $object
     * @return Intervenant
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Intervenant) {
            throw new LogicException("Intervenant spécifié non valide.");
        }
        
        if (!isset($data['CIVILITE'])) {
            throw new LogicException("Aucun civilité présente dans les données d'hydratation.");
        }
        
        // normalize
        if (!$data['CIVILITE'] instanceof Civilite) {
            $civilite = new Civilite();
            $civilite->setLibelleCourt($data['CIVILITE']);
            $civilite->setLibelleLong($data['CIVILITE']);
            $civilite->setSexe($data['CIVILITE']);
            $data['CIVILITE'] = $civilite;
        }
        
        $object->setNomUsuel($data['NOM_USUEL']);
        $object->setNomPatronymique($data['NOM_PATRONYMIQUE']);
        $object->setCivilite($data['CIVILITE']);
        
        return $object;
    }
}