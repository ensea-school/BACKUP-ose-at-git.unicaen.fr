<?php

namespace Common\View\Helper;

/**
 * Description of Exception
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Exception extends \Zend\I18n\View\Helper\AbstractTranslatorHelper
{
    public function __invoke($exception)
    {
        if ($exception instanceof \Exception) {
            $exception = $exception->getMessage();
        }
        
        return sprintf('<div class="alert alert-danger">%s</div>', $exception);
    }
}