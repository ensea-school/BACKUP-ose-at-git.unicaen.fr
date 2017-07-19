<?php

namespace Application\Exception;

use RuntimeException;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DbException extends RuntimeException {

    /**
     * Errors
     *
     * @var array
     */
    protected static $messages = [
        'unique constraint (OSE.SERVICE__UN) violated' =>
            'Vous ne pouvez pas enregistrer cet enseignement car il en existe déjà un de similaire.',
        'ORA-01722' =>
            'Nombre invalide',
        'integrity constraint (OSE.MEP_FR_SERVICE_FK) violated' =>
            'Vous ne pouvez pas dévalider ces heures d\'enseignement, car des demandes de mise en paiement ou des mises en paiement ont été faites.',
        'integrity constraint (OSE.MEP_FR_SERVICE_REF_FK) violated' =>
            'Vous ne pouvez pas dévalider ces heures de référentiel, car des demandes de mise en paiement ou des mises en paiement ont été faites.',
        'unique constraint (OSE.EP_CODE__UN) violated' =>
            'Un enseignement existe déjà avec le même code. Merci de choisir un autre code d\'enseignement.',
        'unique constraint (OSE.ETAPE_CODE__UN) violated' =>
            'Une formation existe déjà avec le même code. Merci de choisir un autre code de formation.',
    ];



    /**
     * Se charge de traduire les exceptions en provanance de la base de données
     *
     * @param \Exception $exception
     * @param string     $tableName
     *
     * @return self
     */
    public static function translate(\Exception $exception)
    {
        if (! $exception->getPrevious() instanceof \Doctrine\DBAL\Driver\OCI8\OCI8Exception){
            // Non gérée donc on retourne l'original'
            return $exception;
        }

        $msg = $exception->getPrevious()->getMessage();

        foreach( self::$messages as $key => $newMsg ){
            if (false !== strpos( $msg, $key)){
                $msg = $newMsg;
                break;
            }
        }

        if (false !== strpos($msg, '20101')){ // erreur décrite manuellement dans Oracle (depuis un trigger par exemple)
            $msg = substr( $msg, 0, strpos($msg, "\n")); // Chaque erreur comporte 3 lignes. On ne récupère que la première
            $msg = str_replace( 'ORA-20101: ', '', $msg ); // On retire le code erreur (20101 par convention pour les erreurs perso OSE)
        }

        return new self($msg, 0, $exception);
    }

}