<?php
namespace Application\Form\Intervenant;

/**
 *
 * 
 */
class DossierFieldsetHydrator extends \Zend\Stdlib\Hydrator\ClassMethods
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\Dossier $dossier
     * @return object
     */
    public function hydrate(array $data, $dossier)
    {
        $data['rib'] = implode('-', $data['rib']);
        
        if (!array_key_exists('perteEmploi', $data)) {
            $data['perteEmploi'] = null;
        }
        if (!array_key_exists('statut', $data)) {
            $data['statut'] = null;
        }
        
        return parent::hydrate($data, $dossier);
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\Dossier $dossier
     * @return array
     */
    public function extract($dossier)
    {
        $data = parent::extract($dossier);
        
        if ($dossier->getRib()) {
            $data['rib'] = array_combine(array('bic', 'iban'), explode('-', $dossier->getRib()));
        }
        
        return $data;
    }
}