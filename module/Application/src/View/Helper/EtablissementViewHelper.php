<?php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Traits\EtablissementAwareTrait;

/**
 * Description of Etablissement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementViewHelper extends AbstractHelper
{
    use EtablissementAwareTrait;


    /**
     *
     * @param Etablissement $etablissement
     *
     * @return self
     */
    public function __invoke(Etablissement $etablissement = null)
    {
        if ($etablissement) $this->setEtablissement($etablissement);

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
        $entity = $this->getEtablissement();

        if (!$entity) {
            return '';
        }

        $vars = [
            "Libellé"                                   => $entity->getLibelle(),
            "Département :"                             => $entity->getDepartement(),
            "Localisation :"                            => $entity->getLocalisation() . " (" . $entity->getDepartement() . ")",
            "N° {$entity->getSource()->getLibelle()} :" => $entity->getSourceCode(),
        ];

        $html = "<dl class=\"etablissement dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        $html .= $this->getView()->historique($entity);

        return $html;
    }



    public function renderLink()
    {
        $etablissement = $this->getEtablissement();
        if (!$etablissement) return '';

        if ($etablissement->getHistoDestruction()) {
            return '<p class="bg-danger"><abbr title="Cet établissement n\'existe plus">' . $etablissement . '</abbr></p>';
        }

        $url   = $this->getView()->url('etablissement', ['action' => 'voir', 'id' => $etablissement->getId()]);
        $pourl = $this->getView()->url('etablissement', ['action' => 'apercevoir', 'id' => $etablissement->getId()]);
        $out   = '<a href="' . $url . '" data-po-href="' . $pourl . '" class="ajax-modal">' . $etablissement . '</a>';

        return $out;
    }
}