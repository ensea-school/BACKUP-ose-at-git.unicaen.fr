<?php

namespace Application\Form\Service;

use Application\Entity\Db\Annee;
use Zend\Form\Form;
use UnicaenApp\Form\Element\SearchAndSelect;

/**
 * Description of AjouterModifier
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AjouterModifier extends Form
{
    /**
     * @var Annee
     */
    protected $annee;

    public function __construct( $name=null )
    {
        // we want to ignore the name passed
        parent::__construct('service');

        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'service')
                ->setHydrator(new ClassMethods(false))
                ->setInputFilter(new InputFilter())
         ;

        $url    = $this->url()->fromRoute('recherche', array('action' => 'intervenantFind'));
        $interv = new SearchAndSelect('intervenant');
        $interv->setAutocompleteSource($url)
                ->setRequired(true)
                ->setSelectionRequired(true)
                ->setLabel("Recherchez l'intervenant concerné :")
                ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));

        $this->add($interv);

        /**
         * Csrf
         */
        $this->add(new Csrf('security'));

        /**
         * Submit
         */
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
            ),
        ));
    }

    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     *
     * @param  object $object
     * @param  int $flags
     * @return mixed|void
     * @throws Exception\InvalidArgumentException
     */
    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED)
    {
        return parent::bind($object, $flags);
    }

    /**
     *
     * @param Annee $annee
     * @return self
     */
    public function setAnnee(Annee $annee)
    {
        $this->annee = $annee;
        return $this;
    }
}