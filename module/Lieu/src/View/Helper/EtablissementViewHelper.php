<?php

namespace Lieu\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Lieu\Entity\Db\Etablissement;
use Lieu\Entity\Db\EtablissementAwareTrait;

/**
 * Description of EtablissementViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementViewHelper extends AbstractHelper
{
    use EtablissementAwareTrait;


    public function __invoke(?Etablissement $etablissement = null): self
    {
        if ($etablissement) $this->setEtablissement($etablissement);

        return $this;
    }



    public function __toString(): string
    {
        return $this->render();
    }



    public function render(): string
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



    public function renderLink(): string
    {
        $etablissement = $this->getEtablissement();
        if (!$etablissement) return '';

        if ($etablissement->getHistoDestruction()) {
            return '<p class="bg-danger"><abbr title="Cet établissement n\'existe plus">' . $etablissement . '</abbr></p>';
        }

        $url = $this->getView()->url('etablissement', ['action' => 'voir', 'id' => $etablissement->getId()]);
        $pourl = $this->getView()->url('etablissement', ['action' => 'apercevoir', 'id' => $etablissement->getId()]);
        $out = '<a href="' . $url . '" data-po-href="' . $pourl . '" class="ajax-modal">' . $etablissement . '</a>';

        return $out;
    }
}