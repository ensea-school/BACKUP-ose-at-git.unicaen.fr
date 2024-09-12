<?php

namespace tests;

use PHPUnit\Framework\TestCase;

class OseTestCase extends TestCase
{
    private array $calc;

    public function assertArrayEquals(array $expected, array $actual, bool $strict = false, string $path=''): bool
    {
        if ('' === $path){
            $this->calc = $actual;
        }

        $k1 = array_keys($actual);
        $k2 = array_keys($expected);

        $diff = array_diff($k1, $k2);
        $hasInt = false;
        foreach( $diff as $d){
            if (is_int($d)){
                $hasInt = true;
            }
        }
        if (($strict || $hasInt) && !empty($diff)){
            $kl = implode( ',', $k1);
            return $this->error('Les tableaux n\'ont pas les mêmes clés ('.$path.') : '.$kl.' en trop');
        }

        if (!empty(array_diff($k2, $k1))){
            $kl = implode( ',', array_diff($k2, $k1));
            return $this->error('Les tableaux n\'ont pas les mêmes cés ('.$path.') : '.$kl.' manquants');
        }

        foreach( $k1 as $k ){
            if (!isset($expected[$k])) continue;

            $p = $path.'/'.$k;
            $a1Type = getType($actual[$k]);
            $a2Type = getType($expected[$k]);

            if ($a1Type != $a2Type){
                return $this->error('Des valeurs ne sont pas du même type ('.$p.') : '.$a2Type.' attendu pour '.$a1Type.' calculé');
            }
            if (is_array($actual[$k])){
                if (!$this->assertArrayEquals($actual[$k], $expected[$k], $strict, $p)){
                    return $this->error('Des sous-tableaux sont différentes ('.$p.')');
                }
            }else{
                if (('float' == $a1Type || 'double' == $a1Type) && ('float' == $a1Type || 'double' == $a1Type)){
                    $diff = abs($actual[$k] - $expected[$k]) > 0.000001;
                }else{
                    $diff = $actual[$k] !== $expected[$k];
                }

                if ($diff){
                    return $this->error('Des valeurs sont différentes ('.$p.') : '.$expected[$k].' attendu pour '.$actual[$k].' calculé');
                }
            }
        }

        $this->assertEquals(true, true);
        return true;
    }



    private function error(string $message): bool
    {
        echo 'Données calculées :'."\n";
        echo $this->arrayExport($this->calc);
        $this->assertNotTrue(true, $message);

        return false;
    }



    public function arrayExport($var, string $indent = ""): string
    {
        switch (gettype($var)) {
            case "array":
                $indexed   = array_keys($var) === range(0, count($var) - 1);
                $r         = [];
                $maxKeyLen = 0;
                foreach ($var as $key => $value) {
                    $key    = $this->arrayExport($key);
                    $keyLen = strlen($key);
                    if ($keyLen > $maxKeyLen) $maxKeyLen = $keyLen;
                }
                foreach ($var as $key => $value) {
                    $key = $this->arrayExport($key);
                    $r[] = "$indent    "
                        . ($indexed ? "" : str_pad($key, $maxKeyLen, ' ') . " => ")
                        . $this->arrayExport($value, "$indent    ");
                }

                return "[\n" . implode(",\n", $r) . ",\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, true);
        }
    }

}