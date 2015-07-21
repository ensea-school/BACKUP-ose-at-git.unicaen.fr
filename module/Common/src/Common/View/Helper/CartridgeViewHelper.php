<?php
namespace Common\View\Helper;


use UnicaenApp\Exception\LogicException;
use Zend\View\Helper\AbstractHtmlElement;

/**
 * Class CartridgeViewHelper
 *
 * @author  Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 * @package Common\View\Helper
 */
class CartridgeViewHelper extends AbstractHtmlElement
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * options par défaut
     *
     * @var array
     */
    private $defaultOptions = [
        'use-div'    => true,
        'bordered'   => true,
        'theme'      => null,
        'attributes' => [],
    ];



    /**
     * @param array     $items    Liste des éléments à afficher dans la cartouche
     * @param null      $theme    Définit le thème utilisé par la cartouche (gray, purple) Si non précisé, alors le thème est celui par défaut (sans nom)
     * @param bool|true $bordered Définit su la cartouche est entourée d'une bordure ou non
     * @param bool|true $useDiv   Utilise une division (si true) ou un span (si non) pour s'afficher. La division gère mieux les retours chariot.
     *
     * @return $this
     */
    public function __invoke(array $items, array $options = [])
    {
        $this->setItems($items);
        $this->setOptions($options);

        return $this;
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
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
        $options = array_merge($this->defaultOptions, $this->getOptions());

        $mainElement = $options['use-div'] ? 'div' : 'span';
        $classes     = ['cartridge'];
        if ($options['theme']) $classes[] = $options['theme'];
        if ($options['bordered']) $classes[] = 'bordered';

        $attrs = $options['attributes'];
        if (isset($attrs['class'])) {
            $attrs['class'] = implode(' ', $classes) . ' ' . $attrs['class'];
        } else {
            $attrs['class'] = implode(' ', $classes);
        }

        $items  = $this->getItems();
        $iCount = count($items);
        if (0 == $iCount) {
            throw new LogicException('Entre 1 et 5 items doivent être transmis au composant "Cartridge"');
        } elseif (5 < $iCount) {
            throw new LogicException('Le composant "Cartridge" ne peut pas accepter plus de 5 items.');
        }

        $out = '<' . $mainElement . ' ' . $this->htmlAttribs($attrs) . '>';
        foreach ($items as $item) {
            $out .= '<span>' . $item . '</span>';
        }
        $out .= '</' . $mainElement . '>';

        return $out;
    }



    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }



    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }



    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }



    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

}