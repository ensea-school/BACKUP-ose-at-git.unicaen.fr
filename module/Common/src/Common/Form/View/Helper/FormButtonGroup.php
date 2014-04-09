<?php
namespace Common\Form\View\Helper;

use Zend\Form\View\Helper\FormRadio;
use Zend\Form\Element\Radio as RadioElement;
use Zend\Form\LabelAwareInterface;


/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormButtonGroup extends FormRadio
{

    /**
     * Render options
     *
     * @param  RadioElement $element
     * @param  array        $options
     * @param  array        $selectedOptions
     * @param  array        $attributes
     * @return string
     */
    protected function renderOptions(RadioElement $element, array $options, array $selectedOptions,
        array $attributes)
    {
        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $labelHelper      = $this->getLabelHelper();

        if ($element instanceof LabelAwareInterface) {
            $globalLabelAttributes = $element->getLabelAttributes();
        }

        if (empty($globalLabelAttributes)) {
            $globalLabelAttributes = $this->labelAttributes;
        }

        $out = '<div class="btn-group" data-toggle="buttons">';
        foreach ($options as $key => $optionSpec) {
            $value           = $key;
            $label           = $optionSpec;

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }
            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }
            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            }else{
                $selected = in_array($value, $selectedOptions);
            }

            $inputAttributes = $attributes;
            $inputAttributes['type'] = 'radio';
            $inputAttributes['value'] = $value;

            $labelAttributes = array(
                'class' => 'btn btn-default'
            );
            if ($selected) $labelAttributes['class'] .= ' active';

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }

            if (! $element instanceof LabelAwareInterface || ! $element->getLabelOption('disable_html_escape')) {
                $label = $escapeHtmlHelper($label);
            }

            $out .= $labelHelper->openTag($labelAttributes);
            $out .= '<input '.$this->createAttributesString($inputAttributes).$this->getInlineClosingBracket().' '.$label;
            $out .= $labelHelper->closeTag();

        }
        $out .= '</div>';

        return $out;
    }
}