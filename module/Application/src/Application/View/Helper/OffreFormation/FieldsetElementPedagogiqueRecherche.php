<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset;
use Zend\View\Helper\AbstractHelper;

/**
 * Description of FieldsetElementPedagogiqueRecherche
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class FieldsetElementPedagogiqueRecherche extends AbstractHelper
{
    /**
     * @var ElementPedagogiqueRechercheFieldset 
     */
    protected $fieldset;
    
    protected $structureElement;
    protected $niveauElement;
    protected $etapeElement;
    protected $elementElement;
    
    /**
     * 
     * @param ElementPedagogiqueRechercheFieldset $fieldset
     * @return FieldsetElementPedagogiqueRecherche|string
     */
    public function __invoke(ElementPedagogiqueRechercheFieldset $fieldset = null)
    {
        $this->fieldset = $fieldset;
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * 
     */
    public function render()
    {
        $id = uniqid();

        $this->fieldset->populateOptions();
        $this->structureElement = $this->fieldset->get('structure');
        $this->niveauElement    = $this->fieldset->get('niveau');
        $this->etapeElement     = $this->fieldset->get('etape');
        $this->elementElement   = $this->fieldset->get('element');
        
        
        $this->structureElement ->setAttribute('id', 'structure-'.$id)
                                ->setAttribute('data-id', $id)
                                ->setAttribute('onchange', 'elementPedagogiqueRecherche.updateValues("'.$id.'",this)');

        $this->niveauElement    ->setAttribute('id', 'niveau-'.$id)
                                ->setAttribute('data-id', $id)
                                ->setAttribute('onchange', 'elementPedagogiqueRecherche.updateValues("'.$id.'",this)');

        $this->etapeElement     ->setAttribute('id', 'etape-'.$id)
                                ->setAttribute('data-id', $id)
                                ->setAttribute('onchange', 'elementPedagogiqueRecherche.updateValues("'.$id.'",this)');

        $this->elementElement   ->setAttribute('id', 'element-'.$id);

        $this->structureElement ->setAttribute('data-relations', json_encode($this->fieldset->getRelations()) );
        $this->structureElement ->setAttribute('data-default-url', $this->fieldset->get('element')->getautoCompleteSource() );
        //NB: $this->elementElement possède déjà un id DOM

        $helper = $this->getView()->formControlGroup();

        $rowTemplate = $rowArgs = array();
        if ($this->fieldset->getStructureEnabled()) {
            $rowTemplate[] = '<div class="col-md-3">%s</div>';
            $rowArgs[]     = $helper($this->structureElement);
        }
        if ($this->fieldset->getNiveauEnabled()) {
            $rowTemplate[] = '<div class="col-md-3">%s</div>';
            $rowArgs[]     = $helper($this->niveauElement);
        }
        if ($this->fieldset->getEtapeEnabled()) {
            $rowTemplate[] = '<div class="col-md-6">%s</div>';
            $rowArgs[]     = $helper($this->etapeElement);
        }

        $html = '';
        if ($rowTemplate) {
            $html .= vsprintf('<div class="row">' . implode(PHP_EOL, $rowTemplate) . '</div>', $rowArgs);
        }
        
        $rowTemplate = $rowArgs = array();
        $rowTemplate[] = '<div class="col-md-12">%s</div>';
        $rowArgs[]     = $helper($this->elementElement);
        
        $html .= vsprintf('<div class="row">' . implode(PHP_EOL, $rowTemplate) . '</div>', $rowArgs);
        
        $html .= '<script> elementPedagogiqueRecherche.updateValues("'.$id.'"); </script>';
        
        return $html;
    }
}