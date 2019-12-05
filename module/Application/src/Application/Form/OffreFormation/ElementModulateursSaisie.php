<?php

namespace Application\Form\OffreFormation;

use Application\Entity\Db\ElementPedagogique;
use Application\Form\AbstractForm;
use Application\Form\OffreFormation\Traits\ElementModulateursFieldsetAwareTrait;
use Application\Service\Traits\TypeModulateurServiceAwareTrait;
use Application\Entity\Db\Etape;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Zend\Form\Element\Select;

/**
 * Description of ElementModulateursSaisie
 *
 * @author Antony Le Courtes <antony.lecourtes@unicaen.fr>
 */
class ElementModulateursSaisie extends AbstractForm
{
    use TypeModulateurServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use ElementModulateursFieldsetAwareTrait;

    /**
     * Etape
     *
     * @var Etape
     */
    protected $element;



    public function __construct($name = null, $options = [])
    {
        if (!$name) $name = "element-modulateurs-saisie";
        parent::__construct($name, $options);
    }



    public function init()
    {

        $this->setAttribute('class', 'element-modulateurs');
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    public function build()
    {
        $element = $this->getElement();
        if (!$element) {
            throw new \RuntimeException('Element non spécifiée : construction du formulaire impossible');
        }


        $elementsModulateurs = $element->getElementModulateur();

        foreach ($elementsModulateurs as $elementModulateur) {
            $typeModulateur = $elementModulateur->getModulateur()->getTypeModulateur();
            $modulateurs    = $typeModulateur->getModulateur();
            foreach ($modulateurs as $m) {
                $modulateursValues[$typeModulateur->getCode()][] = $m->getLibelle();
            }
        }

        $select = new Select('modulateur');
        $select->setValueOptions($modulateursValues[$typeModulateur->getCode()]);
        $select->setValue(3);
        $this->add($select);


        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);
    }



    /**
     * Retourne la liste des types de modulateurs de l'element
     *
     * @return \Application\Entity\Db\Modulateur[]
     */
    public function getTypesModulateurs()
    {

        if (!$this->element) {
            throw new \RuntimeException('Element non spécifié');
        }

        return $this->getServiceTypeModulateur()->getList($this->getServiceTypeModulateur()->finderByElementPedagogique($this->element));
    }



    /**
     * Retourne le nombre total de modulateurs que l'on peut renseigner
     *
     * @param string $typeCode
     *
     * @return integer
     */
    public function countModulateurs($typeCode = null)
    {
        $count = 0;
        foreach ($this->getFieldsets() as $fieldset) {
            if ($fieldset instanceof ElementModulateursFieldset) {
                $count += $fieldset->countModulateurs($typeCode);
            }
        }

        return $count;
    }



    public function getElement()
    {
        return $this->element;
    }



    public function setElement(ElementPedagogique $element)
    {
        $this->element = $element;

        return $this;
    }

}