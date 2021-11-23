<?php

namespace Application\Form;

use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

abstract class AbstractFieldset extends Fieldset implements InputFilterProviderInterface
{
    /**
     * Generates a url given the name of a route.
     *
     * @param string            $name               Name of the route
     * @param array             $params             Parameters for the link
     * @param array|Traversable $options            Options for the route
     * @param bool              $reuseMatchedParams Whether to reuse matched parameters
     *
     * @return string Url                         For the link href attribute
     * @see    \Laminas\Mvc\Router\RouteInterface::assemble()
     *
     */
    protected function getUrl($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        $url = \Application::$container->get('ViewHelperManager')->get('url');

        /* @var $url \Laminas\View\Helper\Url */
        return $url->__invoke($name, $params, $options, $reuseMatchedParams);
    }

}