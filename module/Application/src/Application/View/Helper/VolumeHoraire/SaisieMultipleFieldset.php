<?php

namespace Application\View\Helper\VolumeHoraire;

use Application\Form\VolumeHoraire\SaisieMultipleFieldset as SMFieldset;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of SaisieMultipleFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieMultipleFieldset extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var SMFieldset
     */
    protected $fieldset;

    /**
     *
     * @return \Application\Service\TypeIntervention[]
     */
    public function getTypesIntervention()
    {
        $element = $this->fieldset->getObject()->getService()->getElementPedagogique();
        if ($element){
            return $element->getTypeIntervention();
        }else{
            return $this->getServiceTypeIntervention()->getTypesIntervention();
    }
    }

    /**
     *
     * @param SMFieldset $fieldset
     * @return SaisieMultipleFieldset|string
     */
    public function __invoke(SMFieldset $fieldset = null)
    {
        $this->fieldset = $fieldset;
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render()
    {
        $fieldset = $this->fieldset;
        $typesIntervention = $this->getTypesIntervention();

        $res = $this->getView()->formHidden($fieldset->get('service'));
        $res .= $this->getView()->formHidden($fieldset->get('periode'));
        $res .= $this->getView()->formHidden($fieldset->get('type-volume-horaire'));

        $res .= '<div class="volume-horaire-saisie-multiple">';
        foreach( $typesIntervention as $typeIntervention ){
            $element = $fieldset->get($typeIntervention->getCode());
            $element->setAttribute('class', 'form-control')
                    ->setLabelAttributes(array('class' => 'control-label'));
            $res .= '<div style="">';
            $res .= $this->getView()->formLabel( $element );
            $res .= '<br />';
            $res .= $this->getView()->formNumber( $element);
            $res .= '</div>';
        }
        $res .= '</div><div class="volume-horaire-saisie-multiple-fin"></div>';

        return $res;
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
    }
}