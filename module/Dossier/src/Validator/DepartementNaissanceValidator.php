<?php

namespace Dossier\Validator;

use Application\Entity\Db\Pays;
use Application\Service\Traits\PaysServiceAwareTrait;
use Laminas\Validator\AbstractValidator;

class DepartementNaissanceValidator extends AbstractValidator
{
    use PaysServiceAwareTrait;

    const MSG_NOT_REQUIRED = 'msgNotRequired';
    const MSG_REQUIRED     = 'msgRequired';

    protected $messageTemplates = [
        self::MSG_NOT_REQUIRED => "Aucun département ne doit être saisi pour le pays sélectionné",
        self::MSG_REQUIRED     => "Un département doit être saisi pour le pays sélectionné",
    ];

    protected $franceId;



    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->franceId = $this->getServicePays()->getIdByLibelle(Pays::FRANCE);
    }



    public function isValid($value, $context = null)
    {
        $paysId = (int)$context['paysNaissance'];

        if ($paysId !== $this->franceId && $value) {
            $this->error(self::MSG_NOT_REQUIRED);

            return false;
        }

        if ($paysId === $this->franceId && !$value) {
            $this->error(self::MSG_REQUIRED);

            return false;
        }

        return true;
    }
}