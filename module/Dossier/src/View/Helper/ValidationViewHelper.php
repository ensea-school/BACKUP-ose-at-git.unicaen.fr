<?php

namespace Dossier\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use Lieu\Entity\Db\Structure;
use Workflow\Entity\Db\Validation;
use Workflow\Entity\Db\ValidationAwareTrait;

/**
 * Description of ValidationViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ValidationViewHelper extends AbstractHtmlElement
{

    use ValidationAwareTrait;


    /**
     *
     * @param Structure $structure
     *
     * @return self
     */
    public function __invoke(Validation $validation = null)
    {
        $this->setValidation($validation);

        return $this;
    }



    /**
     * Retourne le code HTML.
     *
     * @return string Code HTML
     */
    public function __toString()
    {
        return $this->render();
    }



    /**
     *
     *
     * @return string Code HTML
     */
    public function render()
    {
        $entity = $this->getValidation();

        if (!$entity) {
            return '';
        }

        $vars = [
            "Type de validation" => $entity->getTypeValidation(),
            "Date et auteur"     => $entity->getHistoModification()->format(\Application\Constants::DATETIME_FORMAT)
                . ' par ' . $this->getView()->utilisateur($entity->getHistoModificateur()),
        ];

        $html = "<dl class=\"validation dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        return $html;
    }



    public function renderLabel()
    {
        $entity = $this->getValidation();

        if (!$entity) {
            return '';
        }

        $title = $entity->getTypeValidation() . ' de ' . $entity->getIntervenant();

        return $this->getView()->tag('abbr', compact('title'))->html(
            'Validation du ' . $entity->getHistoModification()->format(\Application\Constants::DATETIME_FORMAT) . '  par ' . $entity->getHistoModificateur()
        );
    }

}