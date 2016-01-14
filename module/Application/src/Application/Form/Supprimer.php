<?php

namespace Application\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;

/**
 * Description of Supprimer
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Supprimer extends AbstractForm
{

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     *
     * @return void
     */
    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());

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



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [];
    }

}