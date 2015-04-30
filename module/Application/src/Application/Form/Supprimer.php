<?php

namespace Application\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Form;

/**
 * Description of Supprimer
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Supprimer extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        /**
         * Csrf
         */
        $this->add(new Hidden('id'));

        /**
         * Csrf
         */
        $this->add(new Csrf('security'));

        /**
         * Submit
         */
        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Je confirme la suppression',
            ],
        ]);
    }
}