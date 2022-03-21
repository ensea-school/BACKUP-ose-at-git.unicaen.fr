<?php

namespace Application\Form;

use Application\Traits\FormFieldsetTrait;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

abstract class AbstractFieldset extends Fieldset implements InputFilterProviderInterface
{
    use FormFieldsetTrait;
}