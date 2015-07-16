<?php
/**
 * Created by PhpStorm.
 * User: gauthierb
 * Date: 16/07/15
 * Time: 14:04
 */

namespace Application\Service\Message;


use UnicaenApp\Traits\MessageAwareInterface;

interface MessageConstants
{
    /**
     * Messenger view helper severities.
     */
    const SEVERITY_INFO = MessageAwareInterface::INFO;
    const SEVERITY_SUCCESS = MessageAwareInterface::SUCCESS;
    const SEVERITY_WARNING = MessageAwareInterface::WARNING;
    const SEVERITY_ERROR = MessageAwareInterface::ERROR;

    /**
     * Messages ids.
     */
    const ID_DONNEES_PERSO_SAISIES = 'ID_DONNEES_PERSO_SAISIES';
    const ID_DONNEES_PERSO_PAS_SAISIES = 'ID_DONNEES_PERSO_PAS_SAISIES';

    const ID_DONNEES_PERSO_VALIDEES = 'ID_DONNEES_PERSO_VALIDEES';
    const ID_DONNEES_PERSO_PAS_VALIDEES = 'ID_DONNEES_PERSO_PAS_VALIDEES';

    const ID_DONNEES_PERSO_IMPORTANTES = 'ID_DONNEES_PERSO_IMPORTANTES';
}