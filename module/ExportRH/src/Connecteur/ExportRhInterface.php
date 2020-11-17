<?php

namespace ExportRH\Connecteur;

interface ExportRhInterface
{
    /**
     * @return ExportRhInterface
     */
    public function connect(): ExportRhInterface;



    /**
     * @return ExportRhInterface
     */
    public function disconnect(): ExportRhInterface;
}