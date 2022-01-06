<?php

namespace Application\Form\View\Helper;

use UnicaenApp\Form\Element\SearchAndSelect;
use Laminas\Form\Element\Text;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\InvalidElementException;
use Laminas\Form\View\Helper\FormText;

/**
 * Aide de vue générant le code HTML de l'élément de formulaire du même nom.
 *
 * @author <bertrand.gauthier@unicaen.fr>
 * @see    \UnicaenApp\Form\Element\SearchAndSelect
 */
class FormSearchAndSelect extends \UnicaenApp\Form\View\Helper\FormSearchAndSelect
{

    /**
     * {@inheritdoc}
     */
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof SearchAndSelect) {
            throw new InvalidElementException("L'élément spécifié n'est pas du type attendu.");
        }

        $this->element = $element;
        $name          = $this->element->getName();
        $id            = SearchAndSelect::ID_ELEMENT_NAME;
        $label         = SearchAndSelect::LABEL_ELEMENT_NAME;

        if (!$this->element->getAttribute('id')) {
            $this->element->setAttribute('id', uniqid('sas-'));
        }

        $this->element->setAttribute('class', 'sas');

        // L'élément est multivalué :
        //   'id'    => identifiant unique (ex: login de la personne),
        //   'label' => libellé affiché (ex: nom complet de la personne)
        $this->element->setName($name . "[$id]");

        // render parent
        $markup = FormText::render($this->element);

        $elementDomId      = $this->element->getAttribute('id');
        $autocompleteDomId = $elementDomId . '-autocomplete';

        $autocomplete = new Text();
        $autocomplete->setAttributes($this->element->getAttributes())
            ->setName($name . "[$label]")
            ->setAttribute('id', $autocompleteDomId)
            ->setAttribute('class', 'form-control input-sm')
            ->setValue($this->element->getValueLabel() ?: $this->element->getValue());

        $markup .= $this->getView()->formText($autocomplete);

        $markup .= PHP_EOL . '<script>' . $this->getJavascript() . '</script>' . PHP_EOL;

        return $markup;
    }

}