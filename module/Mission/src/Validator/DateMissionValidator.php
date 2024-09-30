<?php

namespace Mission\Validator;

use Application\Entity\Db\Annee;
use DateTime;

use Laminas\Validator\AbstractValidator;
use Laminas\Validator\Date;

class DateMissionValidator extends AbstractValidator
{
    const MSG_INVALID   = 'msgInvalid';
    const MSG_DATE_DIFF = 'msgDiffDate';

    protected $messageTemplates = [
        self::MSG_INVALID   => "La mission doit être saisie sur l'année universitaire où elle se déroule majoritairement : les dates de début et de fin de mission renseignées ne sont pas compatibles avec l'année universitaire <strong>%value%</strong>.",
        self::MSG_DATE_DIFF => "La date de fin de mission est antérieure à la date de début de la mission",
    ];

    protected DateTime $dateDebut;

    protected DateTime $dateFin;

    protected Annee $annee;



    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->dateDebut = $options['dateDebut'];
        $this->dateFin   = $options['dateFin'];
        $this->annee     = $options['annee'];
    }



    public function isValid($value, $context = null)
    {


        //On cherche la date médiane de la mission
        $dateDiff    = $this->dateDebut->diff($this->dateFin);
        $interval    = \DateInterval::createFromDateString(floor($dateDiff->days / 2) . ' day');
        $dateMediane = clone $this->dateDebut;
        $dateMediane->add($interval);

        //On empêche de saisir une date de fin antérieure à la date de début de la mission
        if ($this->dateFin < $this->dateDebut) {
            $this->error(self::MSG_DATE_DIFF);
            return false;

        }

        //On vérifie que la date médiane de la mission est bien dans l'année universitaire en cours sinon
        //cela signifie que la mission n'est pas majoritairement réalisée sur l'année universitaire choisit
        if ($dateMediane >= $this->annee->getDateDebut() && $dateMediane <= $this->annee->getDateFin()) {
            return true;
        }
        $this->error(self::MSG_INVALID, $this->annee->getLibelle());

        return false;
    }
}