<?php

namespace tests;

use PHPUnit\Framework\TestCase;

class OseTestCase extends TestCase
{

    public function assertArrayEquals(array $a1, array $a2, string $path=''): bool
    {
        $k1 = array_keys($a1);
        $k2 = array_keys($a2);

        if (!empty(array_diff($k1, $k2))){
            $this->assertNotTrue(true, 'Les tableaux n\'ont pas les mêmes clés ('.$path.')');
            return false;
        }

        if (!empty(array_diff($k2, $k1))){
            $this->assertNotTrue(true, 'Les tableaux n\'ont pas les mêmes clés ('.$path.')');
            return false;
        }

        foreach( $k1 as $k ){
            $p = $path.'/'.$k;
            $a1Type = getType($a1[$k]);
            $a2Type = getType($a2[$k]);
            if ($a1Type != $a2Type){
                $this->assertNotTrue(true, 'Des valeurs ne sont pas du même type ('.$p.') : '.$a2Type.' pour '.$a1Type);
                return false;
            }
            if (is_array($a1[$k])){
                if (!$this->assertArrayEquals($a1[$k], $a2[$k], $p)){
                    $this->assertNotTrue(true, 'Des sous-tableaux sont différentes ('.$p.')');
                    return false;
                }
            }else{
                if ($a1[$k] !== $a2[$k]){
                    $this->assertNotTrue(true, 'Des valeurs sont différentes ('.$p.') : '.$a2[$k].' pour '.$a1[$k]);
                    return false;
                }
            }
        }

        $this->assertEquals(true, true);
        return true;
    }

}