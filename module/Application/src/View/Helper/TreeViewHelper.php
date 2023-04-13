<?php

namespace Application\View\Helper;


use Application\Model\TreeNode;
use Laminas\View\Helper\AbstractHtmlElement;

/**
 * Description of StructureViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TreeViewHelper extends AbstractHtmlElement
{
    /**
     * @var TreeNode
     */
    private $tree;

    /**
     * @var array
     */
    private $attributes = [];



    /**
     *
     * @param Structure $structure
     *
     * @return self
     */
    public function __invoke(TreeNode $tree, array $attributes = [])
    {
        $this->tree       = $tree;
        $this->attributes = $attributes;

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
        $attrs = $this->attributes;
        if (!isset($attrs['class'])) $attrs['class'] = '';
        $attrs['class'] = trim($attrs['class'] . ' jstree jstree-checkbox-selection');

        $attrs['data-widget'] = [
            "checkbox" => [
                "keep_selected_style" => true,
                "three_state"         => false,
                "cascade"             => 'down',
            ],
            "plugins"  => ["checkbox"],
        ];

        $html = $this->getView()->tag('div', $attrs);
        $html .= $this->getView()->tag('ul')->html($this->renderItem($this->tree, true));
        $html .= '</div>';

        return $html;
    }



    function renderItem(TreeNode $node)
    {
        $html = $node->getLabel();

        if ($node->hasChildren()) {
            $sHtml = '';
            foreach ($node as $child) {
                $sHtml .= $this->renderItem($child);
            }
            $html .= $this->getView()->tag('ul')->html($sHtml);
        }

        $attrs          = [];
        $attrs['id']    = $node->getId();
        $attrs['class'] = 'jstree-open';
        if ($node->getTitle()) {
            $attrs['title'] = $node->getTitle();
        }
        if ($node->getIcon()) {
            $attrs['data-jstree'] = [
                'icon' => $node->getIcon(),
            ];
        }

        $html = $this->getView()->tag('li', $attrs)->html($html);

        return $html;
    }

}