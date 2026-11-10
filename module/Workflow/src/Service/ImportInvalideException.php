<?php

namespace Workflow\Service;

/**
 * Fichier d'import du paramétrage du workflow refusé : rien n'a été modifié.
 */
class ImportInvalideException extends \RuntimeException
{
    /**
     * @param string[] $erreurs
     */
    public function __construct(private readonly array $erreurs)
    {
        parent::__construct(implode("\n", $erreurs));
    }



    /**
     * @return string[]
     */
    public function getErreurs(): array
    {
        return $this->erreurs;
    }
}
