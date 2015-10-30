<?php

namespace Application\View\Helper;

use Application\Entity\Db\Validation;
use Application\Entity\Db\Traits\ValidationAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;

/**
 * Description of ValidationDl
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
            "Date et auteur"     => $entity->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT)
                . ' par ' . $this->getView()->utilisateur($entity->getHistoModificateur()),
        ];

        $html = "<dl class=\"validation dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        return $html;
    }

}