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
    public function getTypesInterventions()
    {
        $sti = $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
        /* @var $sti \Application\Service\TypeIntervention */
        return $sti->getTypesIntervention();
    }

    /**
     *
     * @param SMFieldset $fieldset
     * @return SaisieMultipleFieldset|string
     */
    public function __invoke(SMFieldset $fieldset = null)
    {
        if (null === $fieldset) {
            return $this;
        }

        return $this->render($fieldset);
    }

    /**
     * Rendu du Fieldset
     *
     * @param SMFieldset $fieldset
     * @return string
     */
    public function render(SMFieldset $fieldset)
    {
        $res = $this->getView()->formHidden($fieldset->get('service'));
        $res .= $this->getView()->formHidden($fieldset->get('periode'));

        $res .= '<h3>'.$fieldset->getObject()->getPeriode().'</h3>';
        $res .= '<div class="row">';
        foreach( $this->getTypesInterventions() as $typeIntervention ){
            $element = $fieldset->get($typeIntervention->getCode());
            $element->setAttribute('class', 'form-control')
                    ->setAttribute('style', 'width:5em;display:inline')
                    ->setLabelAttributes(array('class' => 'control-label'));
            $res .= '<div class="col-md-2">';
            $res .= $this->getView()->formLabel( $element );
            $res .= ' ';
            $res .= $this->getView()->formNumber( $element);
            //$res .= $this->getView()->formControlGroup( $fieldset->get($typeIntervention->getCode()) );
            $res .= '</div>';
        }
        $res .= '</div>';

        return $res;
    }
}