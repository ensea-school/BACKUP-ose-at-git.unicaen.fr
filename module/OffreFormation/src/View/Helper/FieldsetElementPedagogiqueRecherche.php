<?php

namespace OffreFormation\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Form\ElementPedagogiqueRechercheFieldset;

/**
 * Description of FieldsetElementPedagogiqueRecherche
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FieldsetElementPedagogiqueRecherche extends AbstractHtmlElement
{
    /**
     * ID
     *
     * @var string
     */
    protected $id;

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
    public function __invoke(?ElementPedagogiqueRechercheFieldset $fieldset = null)
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
     * @return string
     */
    public function getId()
    {
        if (null === $this->id){
            $this->id = uniqid();
        }
        return $this->id;
    }

    /**
     * 
     */
    public function render()
    {
        $this->fieldset->populateOptions();
        $this->structureElement = $this->fieldset->get('structure');
        $this->niveauElement    = $this->fieldset->get('niveau');
        $this->etapeElement     = $this->fieldset->get('etape');
        $this->elementElement   = $this->fieldset->get('element');

        $helper = $this->getView()->formControlGroup();

        $rowTemplate = $rowArgs = [];
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

        $attribs = [
            'class'             => 'element-pedagogique-recherche',
            'id'                => $this->getId(),
            'data-relations'    => json_encode($this->fieldset->getRelations()),
            'data-default-url'  => $this->fieldset->get('element')->getautoCompleteSource(),
        ];
        $html = '<div '.$this->htmlAttribs($attribs).'>';
        if ($rowTemplate) {
            $html .= vsprintf('<div class="row">' . implode(PHP_EOL, $rowTemplate) . '</div>', $rowArgs);
        }

        $helper = $this->getView()->plugin('formSearchAndSelect')->setAutocompleteMinLength(2);
        $html .= '<div class="row"><div class="col-md-12">';
        $html .= '<label class=" control-label" for="structure">'.$this->elementElement->getLabel().'</label>';
        $html .= '<div id="ep-search">'.$helper($this->elementElement).'</div>';
        $html .= '<div id="ep-liste">'.$this->getView()->formSelect( $this->fieldset->get('element-liste') ).'</div>';
        $html .= '<div id="ep-wait" class="loading">&nbsp;</div>';
        $html .= '</div></div>';

        $html .= '</div>';

        return $html;
    }
}