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
                ->willReturnMap($parametres);
        }

        return $parametreMock;
    }

}