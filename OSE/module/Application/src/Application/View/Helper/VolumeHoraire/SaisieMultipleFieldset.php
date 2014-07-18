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
     * @param \Application\Entity\Db\Periode|null $periode
     * @return SaisieMultipleFieldset|string
     */
    public function __invoke(SMFieldset $fieldset = null, $periode=null)
    {
        $this->fieldset = $fieldset;
        $this->periode = $periode;

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
        $colXsNumber = floor( 12 / count($typesIntervention) );

        $res = $this->getView()->formHidden($fieldset->get('service'));
        $res .= $this->getView()->formHidden($fieldset->get('periode'));
        $res .= $this->getView()->formHidden($fieldset->get('type-volume-horaire'));

        $visible = (($this->periode === $fieldset->getObject()->getPeriode()) || (! $this->periode));

        $res .= '<div class="periode" id="'.$fieldset->getObject()->getPeriode()->getCode().'" style="display:'.($visible ? 'block' : 'none').'">';
        $res .= '<h3>'.$fieldset->getObject()->getPeriode().'</h3>';
        $res .= '<div class="row">';
        foreach( $typesIntervention as $typeIntervention ){
            $element = $fieldset->get($typeIntervention->getCode());
            $element->setAttribute('class', 'form-control')
                    ->setAttribute('style', 'width:5em;display:inline')
                    ->setLabelAttributes(array('class' => 'control-label'));
            $res .= '<div class="col-xs-'.$colXsNumber.'" style="max-width:15em">';
            $res .= $this->getView()->formLabel( $element );
            $res .= ' ';
            $res .= $this->getView()->formNumber( $element);
            $res .= '</div>';
        }
        $res .= '</div>';
        $res .= '</div>';

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