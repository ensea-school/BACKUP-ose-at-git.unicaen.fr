<?php

namespace Application\Traits;

trait TranslatorTrait
{
    /**
     * Errors
     *
     * @var array
     */
    private $dbErrors = [
        '.SERVICE__UN',
        'ORA-01722',
        '.MEP_FR_SERVICE_FK',
        '.MEP_FR_SERVICE_REF_FK',
        '.EP_CODE_UN',
        '.ETAPE_SOURCE_UN',
    ];



    /**
     * Se charge de traduire les exceptions en provenance de la base de données ou d'une erreur standard
     *
     * @param \Throwable $exception
     * @param string     $textDomain
     * @param string     $locale
     *
     * @return string
     */
    private function translateException(\Throwable $exception, $textDomain = 'default', $locale = null): string
    {
        if (!$exception->getPrevious() instanceof \Doctrine\DBAL\Driver\Exception) {
            // Non gérée donc on retourne l'original'
            return $this->translate($exception->getMessage(), $textDomain, $locale);
        }

        $msg = $exception->getPrevious()->getMessage();

        foreach ($this->dbErrors as $key) {
            if (false !== strpos($msg, $key)) {
                return $this->translate('bdd ' . $key, $textDomain, $locale);
            }
        }

        if (false !== strpos($msg, '20101')) { // erreur décrite manuellement dans Oracle (depuis un trigger par exemple)
            $msg = substr($msg, 0, strpos($msg, "\n")); // Chaque erreur comporte 3 lignes. On ne récupère que la première
            $msg = str_replace('ORA-20101: ', '', $msg); // On retire le code erreur (20101 par convention pour les erreurs perso OSE)
        }

        return $this->translate($msg, $textDomain, $locale);
    }



    /**
     * @param string|\Throwable $message
     * @param string            $textDomain
     * @param string            $locale
     *
     * @return string
     */
    protected function translate($message, $textDomain = 'default', $locale = null): string
    {
        if ($message instanceof \Throwable) {
            return $this->translateException($message, $textDomain = 'default', $locale = null);
        }

        /** @var \Laminas\I18n\Translator\Translator $translator */
        $translator = \AppAdmin::container()->get('translator');

        return $translator->translate($message, $textDomain, $locale);
    }

}