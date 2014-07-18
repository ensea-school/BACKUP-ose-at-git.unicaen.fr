<?php

namespace Application\Entity\Db\Hydrator;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

/**
 * Description of DateInfSupStrategy
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DateInfSupStrategy implements StrategyInterface
{
    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed   $value The original value.
     * @param object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        return $value;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array  $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        /**
         * On s'attend à recevoir la date au format utilisé par l'élément DateInfSup.
         */
        $e = new \UnicaenApp\Form\Element\DateInfSup();
        $e->setValue($value);
        
        return $e->getDateInf();
    }
}