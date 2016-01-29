<?php

namespace Application\Form\OffreFormation;

use Application\Form\AbstractForm;
use Application\Service\Traits\TypeModulateurAwareTrait;
use Application\Entity\Db\Etape;

/**
 * Description of ElementModulateursSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeModulateursSaisie extends AbstractForm
{
    use TypeModulateurAwareTrait;

    /**
     * Etape
     *
     * @var Etape
     */
    protected $etape;

    public function __construct($name = null, $options = [])
    {
        if (! $name) $name = "modulateurs-saisie";
        parent::__construct($name, $options);
    }

    public function init()
    {
        $hydrator = $this->getServiceLocator()->getServiceLocator()->get('EtapeModulateursFormHydrator');
        $this->setHydrator($hydrator);

        $this->setAttribute('class', 'etape-modulateurs');
        $this->setAttribute('action', $this->getCurrentUrl());
     }

    /**
     * Retourne la liste des types de modulateurs
     *
     * @return \Application\Entity\Db\Modulateur[]
     */
    public function getTypesModulateurs()
    {
        $etape = $this->getEtape();
        if (! $etape){
            throw new \RuntimeException('Etape non spécifiée');
        }
        return $this->getServiceTypeModulateur()->getList( $this->getServiceTypeModulateur()->finderByEtape($etape) );
    }

    /**
     * Retourne le nombre total de modulateurs que l'on peut renseigner
     *
     * @param string $typeCode
     * @return integer
     */
    public function countModulateurs( $typeCode=null )
    {
        $count = 0;
        foreach( $this->getFieldsets() as $fieldset ){
            if ($fieldset instanceof ElementModulateursFieldset){
                $count += $fieldset->countModulateurs( $typeCode );
            }
        }
        return $count;
    }

    public function getEtape()
    {
        return $this->etape;
    }

    public function setEtape(Etape $etape)
    {
        $this->etape = $etape;
        return $this;
    }

    protected function build()
    {
        $etape = $this->getEtape();
        if (! $etape){
            throw new \RuntimeException('Etape non spécifiée : construction du formulaire impossible');
        }

        $elements = $etape->getElementPedagogique();
        foreach( $elements as $element ){
            $mf = $this->getServiceLocator()->get('ElementModulateursFieldset');
            $mf->setName('EL'.$element->getId());
            $this->add($mf);
        }

        $this->add( [
            'name' => 'id',
            'type' => 'Hidden'
        ] );

        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    /**
     *
     * @param Etape $object
     * @return self
     */
    public function setObject($object)
    {
        if ($object instanceof Etape){
            $this->setEtape ($object);
            $this->build();
        }
        return parent::setObject($object);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $etape = $this->getEtape();
        if (! $etape){
            throw new \RuntimeException('Etape non spécifiée : construction des filtres du formulaire impossible');
        }

        $elements = $etape->getElementPedagogique();
        $filters = [];
        foreach( $elements as $element ){
            $filters['EL'.$element->getId()] = [
                'required' => false
            ];
        }
        return $filters;
    }

}