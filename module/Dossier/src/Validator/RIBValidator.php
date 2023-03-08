<?php

namespace Dossier\Validator;

use Laminas\Validator\Iban;

class RIBValidator extends Iban
{
    /**
     * Sets validator options
     *
     * @param array|Traversable $options OPTIONAL
     */
    public function __construct($options = [])
    {
        $options['messages'] = [
            \Laminas\Validator\Iban::FALSEFORMAT  => $message = "L'IBAN saisi n'est pas valide",
            \Laminas\Validator\Iban::CHECKFAILED  => $message,
            \Laminas\Validator\Iban::NOTSUPPORTED => $message,
        ];

        parent::__construct($options);
    }



    public function isValid($value, $context = null)
    {
        $horsSepa = isset($context['ribHorsSepa']) ? (bool)$context['ribHorsSepa'] : false;

        if ($horsSepa) return true; // pas de contrôle si hors SEPA!!

        return parent::isValid($value, $context);
    }
}