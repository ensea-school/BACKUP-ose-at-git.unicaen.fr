<?php

namespace Plafond\Form;

use Application\Form\AbstractForm;
use Laminas\Form\Element;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Service\PlafondServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of PlafondConfigForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PlafondConfigForm extends AbstractForm
{
    use PlafondServiceAwareTrait;

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

        $this->addSubmit();
    }



    public function getElement(PlafondConfigInterface $plafondConfig, string $name): Element
    {
        switch ($name) {
            case 'plafondEtatPrevu':
                $e    = $this->get('etat');
                $etat = $plafondConfig->getEtatPrevu();
                if (!empty($etat)) {
                    $e->setValue($etat->getId());
                } else {
                    $e->setValue($this->getServicePlafond()->getEtat(PlafondEtat::DESACTIVE)->getId());
                }
            break;
            case 'plafondEtatRealise':
                $e    = $this->get('etat');
                $etat = $plafondConfig->getEtatRealise();
                if (!empty($etat)) {
                    $e->setValue($etat->getId());
                } else {
                    $e->setValue($this->getServicePlafond()->getEtat(PlafondEtat::DESACTIVE)->getId());
                }
            break;
            case 'heures':
                $e = $this->get('heures');
                $e->setValue($plafondConfig->getHeures());
            break;
            default:
                throw new \Exception('L\'élément "' . $name . '" n\'existe pas');
        }
        $e->setName($name . '[' . $plafondConfig->getPlafond()->getId() . ']');
        $e->setAttribute('data-name', $name);
        $e->setAttribute('data-plafond-id', $plafondConfig->getPlafond()->getId());

        $entity = $plafondConfig->getEntity();
        if ($entity) {
            $e->setAttribute('data-entity-id', $entity->getId());
        } else {
            $e->setAttribute('data-entity-id', null);
        }

        $e->setAttribute('data-url', get_class($plafondConfig));


        return $e;
    }

}