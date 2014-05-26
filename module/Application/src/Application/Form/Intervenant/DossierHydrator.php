<?php
namespace Application\Form\Intervenant;

/**
 *
 * 
 */
class DossierHydrator extends \Zend\Stdlib\Hydrator\ClassMethods
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\Dossier $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $data['rib'] = implode('-', $data['rib']); 
        
        return parent::hydrate($data, $object);
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\Dossier $object
     * @return array
     */
    public function extract($object)
    {
        $data = parent::extract($object);
        
        if ($object->getRib()) {
            $data['rib'] = array_combine(array('banque', 'guichet', 'compte', 'cle'), explode('-', $object->getRib()));
        }
        
        return $data;
    }

}