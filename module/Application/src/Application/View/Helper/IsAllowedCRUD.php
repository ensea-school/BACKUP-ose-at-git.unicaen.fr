<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * IsAllowed View helper. Allows checking access to a resource/privilege in views.
 *
 * @author 
 */
class IsAllowedCRUD extends AbstractHelper
{
    /**
     * @param mixed $resource
     *
     * @return bool
     */
    public function __invoke($resource)
    {
        if ($this->getView()->isAllowed($resource, 'create')) {
            echo 'create';
        }
        elseif ($this->getView()->isAllowed($resource, 'read')) {
            echo 'read';
        }
        elseif ($this->getView()->isAllowed($resource, 'update')) {
            echo 'update';
        }
        elseif ($this->getView()->isAllowed($resource, 'delete')) {
            echo 'delete';
        }
    }
}