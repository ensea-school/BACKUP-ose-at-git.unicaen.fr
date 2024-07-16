<?php

namespace Mission\Validator;

use Application\Entity\Db\Annee;
use DateTime;

use Laminas\Validator\AbstractValidator;
use Laminas\Validator\Date;

class DateMissionValidator extends AbstractValidator
{
    const MSG_INVALID = 'msgInvalid';

    protected          $messageTemplates = [
        self::MSG_INVALID => "La mission doit être saisie sur l'année universitaire où elle se déroule majoritairement : les dates de début et de fin de mission renseignées ne sont pas compatibles avec l'année universitaire <strong>%value%</strong>.",
    ];

    protected DateTime $dateDebut;

    protected DateTime $dateFin;

    protected DateTime $dateVerifiee;

    protected Annee    $annee;



    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->dateDebut    = $options['dateDebut'];
        $this->dateFin      = $options['dateFin'];
        $this->annee        = $options['annee'];
        $this->dateVerifiee = $options['dateVerifiee'];
    }



    public function isValid($value, $context = null)
    {


        //On cherche la date médiane de la mission
        $dateDiff    = $this->dateDebut->diff($this->dateFin);
        $interval    = \DateInterval::createFromDateString(floor($dateDiff->days / 2) . ' day');
        $dateMediane = clone $this->dateDebut;
        $dateMediane->add($interval);

        //On vérifie que la date médiane de la mission est bien dans l'année universitaire en cours
        if ($dateMediane >= $this->annee->getDateDebut() && $dateMediane <= $this->annee->getDateFin()) {
            return true;
        }
        $this->error(self::MSG_INVALID, $this->annee->getLibelle());

        return false;
    }
}