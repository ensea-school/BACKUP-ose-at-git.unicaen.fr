<?php

namespace Plafond\Form;

use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Intervenant\Entity\Db\Statut;
use Laminas\Form\Element;
use Laminas\Http\Request;
use Plafond\Entity\Db\Plafond;
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



    public function requestSaveConfig(PlafondConfigInterface $plafondConfig, Request $request)
    {
        /** @var Plafond $plafond */
        $plafondId = $request->getPost('plafond');
        $name      = $request->getPost('name');
        $value     = $request->getPost('value');

        switch ($name) {
            case 'plafondEtatPrevu':
                $plafondConfig->setEtatPrevu($this->getEntityManager()->find(PlafondEtat::class, $value));
            break;
            case 'plafondEtatRealise':
                $plafondConfig->setEtatRealise($this->getEntityManager()->find(PlafondEtat::class, $value));
            break;
            case 'heures':
                $plafondConfig->setHeures(stringToFloat($value));
            break;
        }
        $this->getServicePlafond()->saveConfig($plafondConfig);
    }



    /**
     * @param PlafondConfigInterface[] $plafondConfigs
     * @param Request                  $request
     *
     * @return void
     */
    public function requestSaveConfigs(Statut|Structure|FonctionReferentiel $entity, Request $request)
    {
        $heures      = $request->getPost('heures', []);
        $etatPrevu   = $request->getPost('plafondEtatPrevu', []);
        $etatRealise = $request->getPost('plafondEtatRealise', []);

        $plafondConfigs = $this->getServicePlafond()->getPlafondsConfig($entity);

        foreach ($plafondConfigs as $plafondConfig) {
            if (isset($heures[$plafondConfig->getPlafond()->getId()])) {
                $v = stringToFloat($heures[$plafondConfig->getPlafond()->getId()]);
                $plafondConfig->setHeures($v);
            }

            if (isset($etatPrevu[$plafondConfig->getPlafond()->getId()])) {
                $v = (int)$etatPrevu[$plafondConfig->getPlafond()->getId()];
                if ($v != $plafondConfig->getEtatPrevu()?->getId()) {
                    $v = $this->getEntityManager()->find(PlafondEtat::class, $v);
                    $plafondConfig->setEtatPrevu($v);
                }
            }

            if (isset($etatRealise[$plafondConfig->getPlafond()->getId()])) {
                $v = (int)$etatRealise[$plafondConfig->getPlafond()->getId()];
                if ($v != $plafondConfig->getEtatRealise()?->getId()) {
                    $v = $this->getEntityManager()->find(PlafondEtat::class, $v);
                    $plafondConfig->setEtatRealise($v);
                }
            }

            $this->getServicePlafond()->saveConfig($plafondConfig);
        }
    }

}