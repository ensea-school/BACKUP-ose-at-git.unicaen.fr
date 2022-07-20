<?php

namespace <namespace>;

use Application\Form\AbstractForm;
<if useHydrator>
use Laminas\Hydrator\HydratorInterface;
<endif useHydrator>



/**
 * Description of <classname>
 *
 * @author <author>
 */
class <classname> extends AbstractForm
{

    public function init()
    {
        <if useHydrator>
        $hydrator = new <classname>Hydrator;
        $this->setHydrator($hydrator);
        <endif useHydrator>

        $this->setAttribute('action',$this->getCurrentUrl());

        /* Ajoutez vos éléments de formulaire ici */

        $this->addSubmit();
    }
}



<if useHydrator>
class <classname>Hydrator implements HydratorInterface
{

    /**
     * @param  array    $data
     * @param           $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /* on peuple l'objet à partir du tableau de données */

        return $object;
    }



    /**
     * @param  $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            /* On peuple le tableau avec les données de l'objet */
        ];

        return $data;
    }
}
<endif useHydrator>