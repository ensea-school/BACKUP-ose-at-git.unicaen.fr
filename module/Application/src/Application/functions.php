<?php

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



function vhlDump( \Application\Entity\VolumeHoraireListe $volumeHoraireListe): \OSETest\VolumeHoraireListeTest
{
    include_once getcwd() . '/tests/OSETest/VolumeHoraireListeTest.php';
    $dumper = new \OSETest\VolumeHoraireListeTest();
    $volumeHoraireListe->__debug = $dumper;
    $dumper->dumpBegin($volumeHoraireListe);

    return $dumper;
}