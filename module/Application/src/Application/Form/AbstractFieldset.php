<?php

namespace Application\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

abstract class AbstractFieldset extends Fieldset implements InputFilterProviderInterface
{
    /**
     * Generates a url given the name of a route.
     *
     * @see    \Zend\Mvc\Router\RouteInterface::assemble()
     *
     * @param  string            $name               Name of the route
     * @param  array             $params             Parameters for the link
     * @param  array|Traversable $options            Options for the route
     * @param  bool              $reuseMatchedParams Whether to reuse matched parameters
     *
     * @return string Url                         For the link href attribute
     */
    protected function getUrl($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        $url = \Application::$container->get('viewhelpermanager')->get('url');

        /* @var $url \Zend\View\Helper\Url */
        return $url->__invoke($name, $params, $options, $reuseMatchedParams);
    }

}