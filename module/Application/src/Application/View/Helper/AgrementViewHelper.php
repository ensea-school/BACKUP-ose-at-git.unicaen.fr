<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agrement as Entity;
use Application\Entity\Db\Traits\AgrementAwareTrait;
use Application\Constants;
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
     * @var boolean
     */
    private $short;

    /**
     * @var boolean
     */
    private $box;



    /**
     *
     * @param Entity $agrement
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



    public function short()
    {
        $this->short = true;

        return $this;
    }



    public function box()
    {
        $this->box = true;

        return $this;
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
            "Type d'agrément" => (string)$entity->getType()
        ];

        if (!$this->short) {
            $vars["Intervenant"] = (string)$entity->getIntervenant();
            if ($structure = $entity->getStructure()) {
                $vars["Structure"] = (string)$structure;
            }
        }
        $vars["Date de la décision"] = $entity->getDateDecision()->format(Constants::DATE_FORMAT);

        $html = "<dl class=\"agrement dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key : </dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        $html .= $this->getView()->historique($entity);

        if ($this->box){
            $html = '<div class="agrement agrement-box alert alert-success"><span class="glyphicon glyphicon-ok-sign"></span>'.$html.'</div>';
        }

        $this->short = false; // réinitialisation
        $this->box = false;
        return $html;
    }

}