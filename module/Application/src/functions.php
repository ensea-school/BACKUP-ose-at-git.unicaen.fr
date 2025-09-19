<?php

use Application\Entity\Db\Annee;

/**
 * @param string $value
 *
 * @return int|null
 */
function stringToInt($value)
{
    if (null === $value || '' === $value) return null;

    return (int)$value;
}

/**
 * @param integer $value
 *
 * @return string
 */
function intToString($value)
{
    return (string)$value;
}

function topChrono()
{
    UnicaenApp\Util::topChrono();
}


/**
 * @param string $value
 *
 * @return float|null
 */
function stringToFloat($value)
{
    if (null === $value || '' === $value) return null;

    $value = str_replace([',', ' '], ['.', ''], $value);

    return (float)$value;
}


/**
 * @param float $value
 *
 * @return string
 */
function floatToString($value)
{
    return (string)$value;
}


/**
 * @param string $value
 *
 * @return bool|null
 */
function stringToBoolean($value)
{
    if (null === $value || '' === $value) return null;

    if ('true' === $value || '1' === $value) return true;

    return (boolean)$value;
}


/**
 * @param boolean $value
 * @param string  $trueVal
 * @param string  $falseVal
 *
 * @return string
 */
function booleanToString($value, $trueVal = '1', $falseVal = '0')
{
    if ($value) return $trueVal; else return $falseVal;
}


/**
 * @param Annee $anneeDebut
 * @param Annee $anneeFin
 *
 * @return string
 */
function validiteIntervalle($anneeDebut, $anneeFin): string
{
    if ($anneeDebut && $anneeFin) {
        return "À partir de $anneeDebut jusqu'en $anneeFin";
    } elseif (!$anneeDebut && !$anneeFin) {
        return "Permanente";
    } elseif (!$anneeDebut && $anneeFin) {
        return "Jusqu'en $anneeFin";
    } elseif ($anneeDebut && !$anneeFin) {
        return "À partir de $anneeDebut";
    }
}


/**
 * Pour intégrer dans une requête DQL, permet de créer des conditions AND dans une clause WHERE, en fonction des paramètres donnés
 * Filters permet de préciser, en fonction du nom du paramètre, quelle propriété de la requête tester.
 * exemple :
 * $filters = ['intervenant' => 'm.intervenant'];
 * Ce qui donnera : AND m.intervenant = :intervenant si et seulement si intervenant est transmis comme paramètre
 *
 * $parameters est le tableau qui contient les paramètres à transmettre au QueryBuilder.
 *
 * @param array $filters
 * @param array $parameters
 *
 * @return string
 */
function dqlAndWhere(array $filters, array $parameters): string
{
    $dqlFilters = '';
    foreach ($parameters as $name => $value) {
        if (array_key_exists($name, $filters)) {
            if ($value === null) {
                $dqlFilters .= "\nAND " . $filters[$name] . ' IS NULL';
            } elseif ($value == 'IS NOT NULL') {
                $dqlFilters .= "\nAND " . $filters[$name] . ' IS NOT NULL';
            } else {
                $op         = str_contains($value, '%') ? 'LIKE' : '=';
                $dqlFilters .= "\nAND " . $filters[$name] . ' ' . $op . ' :' . $name;
            }
        }
    }


    return $dqlFilters;
}


function vhlDump(\Enseignement\Entity\VolumeHoraireListe $volumeHoraireListe): \OSETest\VolumeHoraireListeTest
{
    include_once getcwd() . '/tests/OSETest/VolumeHoraireListeTest.php';
    $dumper                      = new \OSETest\VolumeHoraireListeTest();
    $volumeHoraireListe->__debug = $dumper;
    $dumper->dumpBegin($volumeHoraireListe);

    return $dumper;
}


function mpg_oracle(): bool
{
    return \Framework\Application\Application::getInstance()->container()->get('config')['unicaen-bddadmin']['connection']['default']['driver'] == 'Oracle';
}


function mpg_lower(mixed &$data): void
{
    if (!mpg_oracle()) {
        return;
    }
    if (is_string($data)) {
        $data = strtolower($data);
        return;
    }
    if (is_array($data)) {
        $data = array_change_key_case($data, CASE_LOWER);
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                mpg_lower($data[$k]);
            }
        }
    }
}


function mpg_upper(mixed &$data): void
{
    if (!mpg_oracle()) {
        return;
    }
    if (is_string($data)) {
        $data = strtoupper($data);
        return;
    }
    if (is_array($data)) {
        $data = array_change_key_case($data, CASE_UPPER);
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                mpg_upper($data[$k]);
            }
        }
    }
}