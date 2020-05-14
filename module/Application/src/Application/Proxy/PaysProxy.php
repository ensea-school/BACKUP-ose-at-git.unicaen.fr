<?php

namespace Application\Proxy;

use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use DoctrineModule\Form\Element\Proxy;


/**
 * Description of PaysProxy
 *
 * Proxy pour alimentation
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */

class PaysProxy extends Proxy
{
    use PaysServiceAwareTrait;
    use ContextServiceAwareTrait;

    public function __construct()
    {
        $this->setOptions([
            'object_manager' => $this->getServiceContext()->getEntityManager(),
            'target_class' => '\Application\Entity\Db\Pays',
            'property' => 'libelle',
            'is_method' => true,
            'find_method' => [
                'name' => 'findBy',
                'params' => [
                    'criteria' => [],
                    'orderBy' => ['libelle' => 'ASC']
                ],
            ],
        ]);
    }



    protected function loadValueOptions()
    {
        parent::loadValueOptions();

        foreach ($this->valueOptions as $key => $value) {
            $id        = $value['value'];
            $pays      = $this->objects[$id];
            $estFrance = $pays->isFrance();

            $this->valueOptions[$key]['attributes'] = [
                'class'      => "pays" . ($estFrance ? " france" : null),
                'data-debut' => $pays->getValiditeDebut()->format('d/m/Y'),
                'data-fin'   => $pays->getValiditeFin() ? $pays->getValiditeFin()->format('d/m/Y') : null,
            ];
        }
    }


    protected function loadObjects()
    {
        parent::loadObjects();

        // reformattage du tableau de données : id => Pays
        $pays = [];
        foreach ($this->objects as $p) {
            $pays[$p->getId()] = $p;
        }

        $this->objects = $pays;
    }
}
