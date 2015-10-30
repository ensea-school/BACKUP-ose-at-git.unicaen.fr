<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agrement as Entity;
use Application\Entity\Db\Traits\AgrementAwareTrait;
use Common\Constants;
use Zend\View\Helper\AbstractHtmlElement;

/**
 * Description of AgrementViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AgrementViewHelper extends AbstractHtmlElement
{
    use AgrementAwareTrait;



    /**
     *
     * @param Entity $elementPedagogique
     *
     * @return self
     */
    public function __invoke(Entity $agrement = null)
    {
        if ($agrement) $this->setAgrement($agrement);

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
        $entity = $this->getAgrement();

        if (!$entity) {
            return '';
        }

        $vars = [
            "Type d'agrément"                    => $entity->getType(),
            "Date de la décision"                => $entity->getDateDecision()->format(Constants::DATE_FORMAT),
            "Date et auteur de l'enregistrement" =>
                $entity->getHistoModification()->format(Constants::DATETIME_FORMAT)
                . ' par ' . $this->getView()->utilisateur($entity->getHistoModificateur()),
        ];

        $html = "<dl class=\"agrement dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        $html .= $this->getView()->historique($entity);

        return $html;
    }

}