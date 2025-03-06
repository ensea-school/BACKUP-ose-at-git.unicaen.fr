<?php

namespace tests\Mocks;

use Application\Service\ParametresService;
use PHPUnit\Framework\TestCase;

class ParametreMock
{

    public static function create(TestCase $test, array $parametres = []): mixed
    {
        $parametreMock = $test->getMockBuilder(ParametresService::class)->getMock();

        if (!empty($parametres)) {
            $parametreMock->expects($test->any())
                ->method('get')
                ->willReturnMap(self::parametresFormat($parametres));
        }

        return $parametreMock;
    }



    public static function parametresFormat(array $parametres): array
    {
        $ps = [];
        foreach ($parametres as $key => $value) {
            $ps[] = [$key, $value];
        }
        return $ps;
    }

}