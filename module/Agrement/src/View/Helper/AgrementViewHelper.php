<?php

namespace Agrement\View\Helper;

use Agrement\Entity\Db\Agrement;
use Agrement\Entity\Db\TblAgrement;
use Agrement\Entity\Db\Traits\AgrementAwareTrait;
use Application\Constants;
use Agrement\Entity\Db\Traits\TblAgrementAwareTrait;
use Laminas\View\Helper\AbstractHtmlElement;

/**
 * Description of AgrementViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AgrementViewHelper extends AbstractHtmlElement
{
    use AgrementAwareTrait;
    use TblAgrementAwareTrait;

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
     * @param Agrement $agrement
     *
     * @return self
     */
    public function __invoke (?Agrement $agrement = null, ?TblAgrement $tblAgrement = null)
    {
        if ($agrement) $this->setAgrement($agrement);
        if ($tblAgrement) $this->setTblAgrement($tblAgrement);

        return $this;
    }



    /**
     * Retourne le code HTML.
     *
     * @return string Code HTML
     */
    public function __toString ()
    {
        return $this->render();
    }



    public function short ()
    {
        $this->short = true;

        return $this;
    }



    public function box ()
    {
        $this->box = true;

        return $this;
    }



    /**
     *
     *
     * @return string Code HTML
     */
    public function render ()
    {
        $entity      = $this->getAgrement();
        $tblAgrement = $this->getTblAgrement();

        if (!$entity || !$tblAgrement) {
            return '';
        }

        $vars = [
            "Type d'agrément"       => (string)$entity->getType(),
            "Date de la décision"   => $entity->getDateDecision()->format(Constants::DATE_FORMAT),
            "Année d'agrémentation" => (string)$tblAgrement->getAnneeAgrement()->getLibelle(),
            "Valable jusqu'au"      => '31 août ' . (integer)$tblAgrement->getAnneeAgrement()->getId() + (integer)$tblAgrement->getDureeVie(),
        ];

        if (!$this->short) {
            $vars["Intervenant"] = (string)$entity->getIntervenant();
            if ($structure = $entity->getStructure()) {
                $vars["Structure"] = (string)$structure;
            }
        }
        //$vars[] = $entity->getDateDecision()->format(Constants::DATE_FORMAT);


        $html = "<dl class=\"agrement dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key&nbsp;:&nbsp;</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        $html .= $this->getView()->historique($entity);

        if ($this->box) {
            $html = '<div class="agrement agrement-box alert alert-success"><div class="float-end fs-1"><i class="fas fa-circle-check"></i></div>' . $html . '</div>';
        }

        $this->short = false;
        $this->box   = false;

        return $html;
    }



    public function renderLabel ()
    {
        $entity = $this->getAgrement();

        if ($entity->getStructure()) {
            $html = $entity->getStructure()->getLibelleCourt();
        } else {
            $html = $entity->getType()->getLibelle();
        }

        if ($entity->getDateDecision()) {
            $html .= ' (décision du ' . $entity->getDateDecision()->format(Constants::DATE_FORMAT) . ')';
        }

        return $html;
    }
}