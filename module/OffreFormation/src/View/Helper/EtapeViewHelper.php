<?php

namespace OffreFormation\View\Helper;

use Application\Provider\Privileges;
use Application\Util;
use Laminas\View\Helper\AbstractHtmlElement;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\Etape as Entity;
use OffreFormation\Entity\Db\Traits\EtapeAwareTrait;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeViewHelper extends AbstractHtmlElement
{
    use EtapeAwareTrait;
    use EtapeServiceAwareTrait;


    /**
     *
     * @param Entity $etape
     *
     * @return self
     */
    public function __invoke(?Entity $etape = null)
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
    public function renderDescription()
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

        return $html;
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

        $html = $this->renderDescription();

        $buttons = '';
        if ($this->getView()->isAllowed($entity, Privileges::ODF_ETAPE_EDITION)) {
            $buttons .= '<a class="btn btn-secondary mod-ajax" data-submit-reload="true" href="' . $this->getView()->url('of/etape/modifier', ['etape' => $entity->getId()]) . '" data-event="etape-modifier"><i class="fas fa-pencil"></i> Modifier</a>';
            $buttons .= '<a class="btn btn-secondary mod-ajax" data-submit-reload="true" href="' . $this->getView()->url('of/etape/supprimer', ['etape' => $entity->getId()]) . '" data-event="etape-supprimer"><i class="fas fa-trash-can"></i> Supprimer</a>';
        }

        if ($buttons) {
            $html .= "<div class=\"actions\">$buttons</div>";
        }

        $html .= $this->getView()->historique($entity);

        return $html;
    }



    public function renderLink($content = null, $attributes = [])
    {
        $etape = $this->getEtape();
        if (!$etape) return '';

        if (!$content) $content = (string)$etape;

        $default = [
            'href'  => $this->getView()->url('of/etape/voir', ['etape' => $etape->getId()]),
            'class' => ['etape-link', 'ajax-modal'],
            'id'    => (string)$etape->getId(),
        ];


        if (!$etape->estNonHistorise()) {
            $default['title']   = 'Cette formation n\'existe plus';
            $default['class'][] = 'bg-danger';
        }

        $tag = 'a';
        if (!$this->getView()->isAllowed(Privileges::getResourceId(Privileges::ODF_ETAPE_VISUALISATION))) $tag = 'span';

        return "<$tag " . $this->htmlAttribs(Util::mergeHtmlAttribs($default, $attributes)) . '>' . $content . "</$tag>";
    }



    public function renderAjouterLink(string $content, array $attributes, Structure $structure)
    {
        if (!$content) $content = '<i class="fas fa-plus"></i> Ajouter une formation';
        $default = [
            'href'       => $this->getView()->url('of/etape/ajouter', ['structure' => $structure->getId()]),
            'class'      => ['etape-ajouter-link', 'ajax-modal', 'iconify', 'btn', 'btn-secondary'],
            'data-event' => 'etape-ajouter',
            'title'      => 'Ajouter une formation',
        ];

        return '<a ' . $this->htmlAttribs(Util::mergeHtmlAttribs($default, $attributes)) . '>' . $content . '</a>';
    }
}