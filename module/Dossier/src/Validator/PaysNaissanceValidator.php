<?php

namespace Dossier\Validator;

use Application\Filter\DateTimeFromString;
use DateTime;
use Laminas\Validator\AbstractValidator;
use Lieu\Entity\Db\Pays as PaysEntity;
use LogicException;

class PaysNaissanceValidator extends AbstractValidator
{
    const MSG_INVALID = 'msgInvalid';

    protected $messageTemplates = [
        self::MSG_INVALID => "Le pays sélectionné n'existe pas à la date de naissance saisie",
    ];

    /**
     * @var PaysService
     */
    protected $service;



    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!isset($options['service'])) {
            throw new LogicException("Paramètre 'service' introuvable.");
        }

        $this->service = $options['service'];
    }



    public function isValid($value, $context = null)
    {
        $pays = $this->service->get($value);
        /* @var $pays PaysEntity */

        $date              = DateTimeFromString::run($context['dateNaissance']);
        $dateDebutValidite = $pays->getValiditeDebut();
        $dateFinValidite   = $pays->getValiditeFin() ?: new DateTime();

        if ($date && ($date < $dateDebutValidite || $date > $dateFinValidite)) {
            $this->error(self::MSG_INVALID);

            return false;
        }

        return true;
    }
}