<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHtmlElement;

/**
 * Description of AbstractDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractDl extends AbstractHtmlElement
{
    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var bool
     */
    protected $short = false;

    /**
     * @var bool
     */
    protected $horizontal = false;

    /**
     *
     * @param mixed $entity
     * @param bool $horizontal
     * @param bool $short
     * @return self
     */
    public function __invoke($entity = null, $horizontal = false, $short = false)
    {
        $this->entity     = $entity;
        $this->horizontal = $horizontal;
        $this->short      = $short;

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
     * Génrère le code HTML.
     *
     * @return string Code HTML
     */
    abstract public function render();

    /**
     *
     * @param string $class
     * @return string
     */
    public function getTemplateDl($class = null)
    {
        $classes = [];
        $classes[] = $this->horizontal ? 'dl-horizontal' : null;
        $classes[] = $class;
        $classes = implode(' ', $classes);

        return '<dl class="' . $classes . '">' . PHP_EOL . '%s' . PHP_EOL . '</dl>'. PHP_EOL;
    }

    /**
     *
     * @return string
     */
    public function getTemplateDtDd()
    {
        return '<dt>' . PHP_EOL . '%s' . PHP_EOL . '</dt>'. PHP_EOL . '<dd>' . PHP_EOL . '%s' . PHP_EOL . '</dd>'. PHP_EOL;
    }
}