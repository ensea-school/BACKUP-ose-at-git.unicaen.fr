<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;

abstract class AbstractViewHelper extends AbstractHtmlElement implements ServiceLocatorAwareInterface {
    use ServiceLocatorAwareTrait;

}