<?php

namespace Application\View\Helper\OffreFormation;

use Application\Entity\Db\Etape as Entity;
use Application\Entity\Db\Privilege;
use Application\Traits\EtapeAwareTrait;
use Application\Service\Traits\EtapeAwareTrait as ServiceEtapeAwareTrait;
use Application\Util;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeViewHelper extends AbstractHtmlElement implements ServiceLocatorAwareInterface
{
    use EtapeAwareTrait;
    use ServiceLocatorAwareTrait;
    use ServiceEtapeAwareTrait;



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
        if ($this->getView()->isAllowed($entity,Privilege::ODF_ETAPE_EDITION)){
            $buttons .= '<a class="btn btn-default ajax-modal" href="' . $this->getView()->url('of/etape/modifier', ['etape' => $entity->getId()]) . '" data-event="etape-modifier"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
            $buttons .= '<a class="btn btn-default ajax-modal" href="' . $this->getView()->url('of/etape/supprimer', ['etape' => $entity->getId()]) . '" data-event="etape-supprimer"><span class="glyphicon glyphicon-trash"></span> Supprimer</a>';
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
            'id'    => $etape->getId(),
        ];

        if ($etape->getHistoDestruction() && 0 == $etape->getCheminPedagogique()->count()) {
            $default['title']   = 'Cette formation n\'existe plus';
            $default['class'][] = 'bg-danger';
        }

        return '<a ' . $this->htmlAttribs(Util::mergeHtmlAttribs($default, $attributes)) . '>' . $content . '</a>';
    }



    public function renderAjouterLink($content='', $attributes = [])
    {
        if (!$content) $content = '<span class="glyphicon glyphicon-plus"></span> Ajouter une formation';

        $default = [
            'href'  => $this->getView()->url('of/etape/ajouter'),
            'class' => ['etape-ajouter-link', 'ajax-modal', 'iconify', 'btn', 'btn-default'],
            'data-event' => 'etape-ajouter',
            'title' => 'Ajouter une formation',
        ];

        return '<a ' . $this->htmlAttribs(Util::mergeHtmlAttribs($default, $attributes)) . '>' . $content . '</a>';
    }
}