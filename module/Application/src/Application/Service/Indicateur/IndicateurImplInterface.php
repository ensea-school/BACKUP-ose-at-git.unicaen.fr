<?php

namespace Application\Service\Indicateur;

use Traversable;
use Zend\Filter\FilterInterface;

/**
 * Interface définissant l'implémentation d'un indcateur.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface IndicateurImplInterface
{
    /**
     * Retourne la liste de résultats renvoyée par l'indicateur.
     * 
     * @return Traversable
     */
    public function getResult();
    
    /**
     * Retourne le nombre de ligne dans la liste de résultats renvoyée par l'indicateur.
     * 
     * @return int
     */
    public function getResultCount();

    /**
     * Retourne l'intitulé de l'indicateur.
     * 
     * @param bool $appendStructure Faut-il inclure la structure concernée
     * @return string
     */
    public function getTitle($appendStructure = true);
    
    /**
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultItemFormatter();
    
    /**
     * Retourne l'URL associée à un item de la liste de résultat renvoyée par l'indicateur.
     * 
     * @param mixed $resultItem Un item de la liste de résultat
     * @return string
     */
    public function getResultItemUrl($resultItem);
}