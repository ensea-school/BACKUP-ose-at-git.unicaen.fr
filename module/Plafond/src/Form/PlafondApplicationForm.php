<?php

namespace Plafond\Form;

use Plafond\Entity\Db\PlafondApplication;
use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Service\PlafondApplicationServiceAwareTrait;
use Plafond\Service\PlafondServiceAwareTrait;
use UnicaenApp\Util;
use Laminas\Hydrator\HydratorInterface;


/**
 * Description of PlafondApplicationForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondApplicationForm extends AbstractForm
{
    use AnneeServiceAwareTrait;
    use PlafondServiceAwareTrait;
    use ContextServiceAwareTrait;
    use PlafondApplicationServiceAwareTrait;


    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());

        $hydrator = new PlafondApplicationFormHydrator;
        $hydrator->setServiceAnnee($this->getServiceAnnee());
        $hydrator->setServicePlafond($this->getServicePlafond());
        $this->setHydrator($hydrator);

        $this->add([
            'type' => 'Hidden',
            'name' => 'plafond',
        ]);

        $this->add([
            'type' => 'Hidden',
            'name' => 'typeVolumeHoraire',
        ]);

        $this->add([
            'name'       => 'plafondEtat',
            'options'    => [
                'label'         => 'État',
                'value_options' => Util::collectionAsOptions($this->getServicePlafond()->getEtats()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'anneeDebut',
            'options'    => [
                'label' => 'Année de début',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'anneeFin',
            'options'    => [
                'label' => 'Année de fin',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
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



    /**
     * @param PlafondApplication $papp
     *
     * @return $this
     */
    public function buildAnnees(PlafondApplication $papp)
    {
        /* Limitations des années de début */
        $derniereAnneeDebut = $this->getServicePlafondApplication()->derniereAnneeDebut($papp);
        if ($derniereAnneeDebut) {
            $this->get('anneeDebut')->setValueOptions($this->getAnnees($derniereAnneeDebut, null));
            if (!$papp->getId()) {
                $papp->setAnneeDebut($this->getServiceAnnee()->getSuivante($derniereAnneeDebut));
            }
        } else {
            $this->get('anneeDebut')->setValueOptions($this->getAnnees(null, null));
            $this->get('anneeDebut')->setEmptyOption('Pas de limite');
        }


        /* Limitations des années de fin */
        $premiereAnneeFin = $this->getServicePlafondApplication()->premiereAnneeFin($papp);
        if ($premiereAnneeFin) {
            $this->get('anneeFin')->setValueOptions($this->getAnnees(null, $premiereAnneeFin));
            if (!$papp->getId()) {
                $papp->setAnneeFin($this->getServiceAnnee()->getPrecedente($premiereAnneeFin));
            }
        } else {
            $this->get('anneeFin')->setValueOptions($this->getAnnees(null, null));
            $this->get('anneeFin')->setEmptyOption('Pas de limite');
        }

        return $this;
    }



    private function getAnnees($min, $max)
    {
        $annee = $this->getServiceContext()->getAnnee()->getId();
        $as    = $this->getServiceAnnee()->getList();

        $annees = [];
        foreach ($as as $ak => $av) {
            if ($ak >= $annee - 10 && $ak <= $annee + 10) {
                if ((!$min || $ak > $min->getId()) && (!$max || $ak < $max->getId())) {
                    $annees[$ak] = $av->getLibelle();
                }
            }
        }

        return $annees;
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
            'plafondEtat'       => ['required' => true],
            'anneeDebut'        => ['required' => false],
            'anneeFin'          => ['required' => false],
            'plafond'           => ['required' => true],
            'typeVolumeHoraire' => ['required' => true],
        ];
    }

}





class PlafondApplicationFormHydrator implements HydratorInterface
{
    use AnneeServiceAwareTrait;
    use PlafondServiceAwareTrait;


    /**
     * @param array              $data
     * @param PlafondApplication $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $plafondEtat = $this->getServicePlafond()->getEntityManager()->find(PlafondEtat::class, $data['plafondEtat']);
        $anneeDebut  = isset($data['anneeDebut']) && $data['anneeDebut'] ? $this->getServiceAnnee()->get($data['anneeDebut']) : null;
        $anneeFin    = isset($data['anneeFin']) && $data['anneeFin'] ? $this->getServiceAnnee()->get($data['anneeFin']) : null;

        $object->setPlafondEtat($plafondEtat);
        $object->setAnneeDebut($anneeDebut);
        $object->setAnneeFin($anneeFin);

        return $object;
    }



    /**
     * @param PlafondApplication $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'plafondEtat'       => $object->getPlafondEtat() ? $object->getPlafondEtat()->getId() : null,
            'anneeDebut'        => $object->getAnneeDebut() ? $object->getAnneeDebut()->getId() : null,
            'anneeFin'          => $object->getAnneeFin() ? $object->getAnneeFin()->getId() : null,
            'plafond'           => $object->getPlafond()->getId(),
            'typeVolumeHoraire' => $object->getTypeVolumeHoraire()->getId(),
        ];

        return $data;
    }
}