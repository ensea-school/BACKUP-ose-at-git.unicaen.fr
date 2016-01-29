<?php
/**
 * Created by PhpStorm.
 * User: laurent
 * Date: 11/09/15
 * Time: 09:45
 */

namespace Application;


class Util
{

    /**
     * Fusionne les attributs HTML en provenance de deux tableaux, en tenant compte des classes
     *
     * @param array $attribs1
     * @param array $attribs2
     *
     * @return array
     */
    static public function mergeHtmlAttribs( array $attribs1=[], array $attribs2=[] )
    {
        $classes = array_merge(
            (isset($attribs1['class']) ? (array)$attribs1['class'] : []),
            (isset($attribs2['class']) ? (array)$attribs2['class'] : [])
        );

        $result = array_merge( $attribs1, $attribs2 );
        $result['class'] = array_unique( $classes );

        return $result;
    }



    static public function sqlAndIn( $column, array $entities )
    {
        if (!empty($entities)){
            $l = [];
            foreach( $entities as $e ){
                if (is_object($e) && method_exists($e, 'getId')){
                    $l[] = $e->getId();
                }else{
                    $l[] = (int)$e;
                }
            }
            return ' AND '.$column.' IN ('.implode(',',$l).')';
        }
        return '';
    }
}