<?php

namespace Signature\Service;

use Application\Service\AbstractEntityService;
use UnicaenSignature\Entity\Db\Signature;

/**
 * Description of SignatureService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class SignatureService extends AbstractEntityService
{

    use \UnicaenSignature\Service\SignatureServiceAwareTrait;

    /**
     * retourne la classe des entités correcpondantes
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Signature::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'signature';
    }



    public function getListCircuitSignature()
    {
        
    }

}