<?php
namespace Application\Form\Service;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\EtablissementAwareTrait;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldsetHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
    use ContextAwareTrait;
    use IntervenantAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use EtablissementAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\Service $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $intervenant = isset($data['intervenant']['id']) ? (int)$data['intervenant']['id'] : null;
        $object->setIntervenant( $intervenant ? $this->getServiceIntervenant()->getBySourceCode($intervenant) : null );

        if (isset($data['element-pedagogique']) && $data['element-pedagogique'] instanceof \Application\Entity\Db\ElementPedagogique){
            $object->setElementPedagogique( $data['element-pedagogique'] );
        }else{
            $elementPedagogique = isset($data['element-pedagogique']['element']['id']) ? $data['element-pedagogique']['element']['id'] : null;
            $object->setElementPedagogique( $elementPedagogique ? $this->getServiceElementPedagogique()->get($elementPedagogique) : null );
        }

        $etablissement = isset($data['etablissement']['id']) ? (int)$data['etablissement']['id'] : null;
        $object->setEtablissement( $etablissement ? $this->getServiceEtablissement()->get($etablissement) : null );

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\Service $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];

        if ($object) $data['id'] = $object->getId();
        if ($object->getIntervenant()){
            $data['intervenant'] = [
                'id' => $object->getIntervenant()->getSourceCode(),
                'label' => (string)$object->getIntervenant()
            ];
        }else{
            $data['intervenant'] = null;
        }

        if ($object->getElementPedagogique()){
            $data['element-pedagogique'] = $object->getElementPedagogique();
        }else{
            $data['element-pedagogique'] = null;
        }

        if ($object->getEtablissement()){
            $data['etablissement']         = [
                'id' => $object->getEtablissement()->getId(),
                'label' => (string)$object->getEtablissement()
            ];
        }else{
            $data['etablissement'] = null;
        }

        if ($object->getEtablissement() === $this->getServiceContext()->getEtablissement()){
            $data['interne-externe'] = 'service-interne';
        }else{
            $data['interne-externe'] = 'service-externe';
        }
        return $data;
    }
}
