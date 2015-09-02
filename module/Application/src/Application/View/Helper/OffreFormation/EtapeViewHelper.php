<?php

namespace Application\View\Helper\OffreFormation;

use Application\Entity\Db\Etape as Entity;
use Application\Traits\EtapeAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeViewHelper extends AbstractHtmlElement
{
    use EtapeAwareTrait;



    /**
     *
     * @param Entity $etape
     *
     * @return self
     */
    public function __invoke(Entity $etape = null)
    {
        if ($etape) $this->setEtape($etape);

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
        $entity = $this->getEtape();

        if (!$entity) {
            return '';
        }

        $vars = [
            "Code {$entity->getSource()->getLibelle()}" => $entity->getSourceCode(),
            "Libellé"                                   => $entity->getLibelle(),
            "Structure"                                 => $entity->getStructure(),
            "Type de formation"                         => $entity->getTypeFormation(),
            "Niveau"                                    => $entity->getNiveau(),
            "Spécif. échanges"                          => $entity->getSpecifiqueEchanges() ? 'Oui' : 'Non',
            "Domaine fonctionnel"                       => $entity->getDomaineFonctionnel() ?: "Aucun",
        ];

        $html = "<dl class=\"etape dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        $html .= $this->getView()->historique($entity);

        return $html;
    }



    public function renderLink()
    {
        $etape = $this->getEtape();
        if (!$etape) return '';

        if ($etape->getHistoDestruction() && 0 == $etape->getCheminPedagogique()->count()) {
            return '<span class="bg-danger"><abbr title="Cette formation n\'existe plus">' . $etape . '</abbr></span>';
        }

        $url   = $this->getView()->url('of/etape/voir', ['etape' => $etape->getId()]);
        $pourl = $this->getView()->url('of/etape/voir', ['etape' => $etape->getId()]);
        $out   = '<a href="' . $url . '" data-po-href="' . $pourl . '" class="ajax-modal">' . $etape . '</a>';

        return $out;
    }
}