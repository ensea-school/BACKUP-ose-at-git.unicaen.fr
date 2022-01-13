<?php

namespace Plafond\Form;

use Application\Form\AbstractForm;
use Laminas\Form\Element;
use Plafond\Entity\Db\Plafond;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Service\PlafondServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of PlafondConfigForm
 *
 * @author UnicaenCode
 */
class PlafondConfigForm extends AbstractForm
{
    use PlafondServiceAwareTrait;

    /**
     * @var array Plafond[]
     */
    protected array $plafonds = [];



    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'etat',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServicePlafond()->getEtats()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'heures',
            'type'       => 'Text',
            'attributes' => [
                'title' => "Nombre d'heures",
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    public function getElement(Plafond $plafond, string $name): Element
    {
        $pa = $plafond->getPlafondApplication();
        switch ($name) {
            case 'plafondEtatPrevu':
                $e    = $this->get('etat');
                $etat = $pa->getEtatPrevu();
                if (!empty($etat)) {
                    $e->setValue($etat->getId());
                } else {
                    $e->setValue($this->getServicePlafond()->getEtat(PlafondEtat::DESACTIVE)->getId());
                }
            break;
            case 'plafondEtatRealise':
                $e    = $this->get('etat');
                $etat = $pa->getEtatRealise();
                if (!empty($etat)) {
                    $e->setValue($etat->getId());
                } else {
                    $e->setValue($this->getServicePlafond()->getEtat(PlafondEtat::DESACTIVE)->getId());
                }
            break;
            case 'heures':
                $e = $this->get('heures');
                $e->setValue($pa->getHeures());
            break;
            default:
                throw new \Exception('L\'élément "' . $name . '" n\'existe pas');
        }
        $e->setName($name . '[' . $plafond->getId() . ']');
        $e->setAttribute('data-name', $name);
        $e->setAttribute('data-plafond-id', $plafond->getId());

        return $e;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            /* Filtres et validateurs */
        ];
    }



    /**
     * @return Plafond[]
     */
    public function getPlafonds(): array
    {
        return $this->plafonds;
    }



    /**
     * @param array $plafonds
     *
     * @return PlafondConfigForm
     */
    public function setPlafonds(array $plafonds): PlafondConfigForm
    {
        $this->plafonds = $plafonds;

        return $this;
    }

}