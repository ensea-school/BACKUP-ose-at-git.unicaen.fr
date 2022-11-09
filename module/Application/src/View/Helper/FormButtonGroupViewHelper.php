<?php

namespace Application\View\Helper;

use Laminas\Form\View\Helper\FormRadio;
use Laminas\Form\Element\MultiCheckbox as MultiCheckboxElement;
use Laminas\Form\LabelAwareInterface;


/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormButtonGroupViewHelper extends FormRadio
{

    /**
     * Render options
     *
     * @param MultiCheckboxElement $element
     * @param array                $options
     * @param array                $selectedOptions
     * @param array                $attributes
     *
     * @return string
     */
    protected function renderOptions(MultiCheckboxElement $element, array $options, array $selectedOptions, array $attributes): string
    {
        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $labelHelper      = $this->getLabelHelper();

        if ($element instanceof LabelAwareInterface) {
            $globalLabelAttributes = $element->getLabelAttributes();
        }

        if (empty($globalLabelAttributes)) {
            $globalLabelAttributes = $this->labelAttributes;
        }

        $out = '<div class="btn-group" data-bs-toggle="buttons">';
        foreach ($options as $key => $optionSpec) {
            $value = $key;
            $label = $optionSpec;

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }
            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }
            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            } else {
                $selected = in_array($value, $selectedOptions);
            }

            $inputAttributes          = $attributes;
            $inputAttributes['type']  = 'radio';
            $inputAttributes['value'] = $value;

            $labelAttributes = [
                'class' => 'btn btn-secondary',
            ];
            if ($selected) {
                $labelAttributes['class']   .= ' active';
                $inputAttributes['checked'] = 'checked';
            }

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }

            if (!$element instanceof LabelAwareInterface || !$element->getLabelOption('disable_html_escape')) {
                $label = $escapeHtmlHelper($label);
            }

            $out .= $labelHelper->openTag($labelAttributes);
            $out .= '<input ' . $this->createAttributesString($inputAttributes) . $this->getInlineClosingBracket() . ' ' . $label;
            $out .= $labelHelper->closeTag();
        }
        $out .= '</div>';

        return $out;
    }
}