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