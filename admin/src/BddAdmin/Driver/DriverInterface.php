<?php

namespace BddAdmin\Driver;

interface DriverInterface
{
    public function connect(): DriverInterface;



    public function disconnect(): DriverInterface;



    public function beginTransaction(): DriverInterface;



    public function commitTransaction(): DriverInterface;



    public function rollbackTransaction(): DriverInterface;



    public function exec(string $sql, array $params = [], array $types = []): bool;



    public function select(string $sql, array $params = [], array $options = []);



    public function fetch($statement);



    public function getDdlClass(string $name): string;

}