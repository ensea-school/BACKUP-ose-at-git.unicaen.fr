<?php

namespace BddAdmin\Ddl;

use BddAdmin\Bdd;


interface DdlInterface
{
    /**
     * DdlAbstract constructor.
     */
    public function __construct(Bdd $bdd);



    /**
     * @return string
     */
    public function getType(): string;



    /**
     * @return array
     */
    public function getOptions(): array;



    /**
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options = []): DdlInterface;



    /**
     * @param string $option
     * @param null   $params
     *
     * @return self
     */
    public function addOption(string $option, $params = null): DdlInterface;



    /**
     * @param array $options
     *
     * @return self
     */
    public function addOptions(array $options): DdlInterface;



    /**
     * @param string $option
     *
     * @return self
     */
    public function removeOption(string $option): DdlInterface;



    /**
     * @return self
     */
    public function clearOptions(): DdlInterface;



    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption(string $option): bool;



    /**
     * @return array
     */
    public function getList(): array;



    /**
     * @param string|string[]|null $includes
     * @param string|string[]|null $excludes
     *
     * @return array
     */
    public function get($includes = null, $excludes = null): array;



    /**
     * @param array $data
     */
    public function create(array $data);



    /**
     * @param string|array $name
     */
    public function drop($name);



    /**
     * @param array $old
     * @param array $new
     *
     * @return mixed
     */
    public function alter(array $old, array $new);



    /**
     * @param string $oldName
     * @param array  $new
     *
     * @return mixed
     */
    public function rename(string $oldName, array $new);



    /**
     * @param array $data
     *
     * @return array
     */
    public function prepareRenameCompare(array $data): array;
}