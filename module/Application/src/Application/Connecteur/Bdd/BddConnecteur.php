<?php

namespace Application\Connecteur\Bdd;

use UnicaenApp\Service\EntityManagerAwareTrait;

class BddConnecteur{
    use EntityManagerAwareTrait;

    /**
     * @param string $sql
     * @param array  $params
     *
     * @return array
     */
    public function fetch($sql, $params = [], $key=null)
    {
        $result = $this->getEntityManager()->getConnection()->fetchAll($sql, $this->prepareParams($params));
        if (null === $key){
            return $result;
        }else{
            $res = [];
            foreach( $result as $d ){
                $res[$d[$key]] = $d;
            }
            return $res;
        }
    }



    /**
     * @param string $sql
     * @param array  $params
     *
     * @return array|mixed
     */
    public function fetchOne($sql, $params = [], $field=null, $type=null)
    {
        $res = $this->fetch($sql, $params);
        if ($res) {
            if ($field){
                if (isset($res[0][$field])){
                    $res = $res[0][$field];
                    if ($res && $type){
                        settype($res,$type);
                    }
                    return $res;
                }else{
                    return null;
                }
            }else{
                return $res[0];
            }
        } else {
            if ($field){
                return null;
            }else{
                return [];
            }
        }
    }



    /**
     * @param       $sql
     * @param array $params
     *
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function exec( $sql, $params=[] )
    {
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $this->prepareParams($params));
    }



    /**
     * @param       $plsql
     * @param array $params
     *
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function execPlsql($plsql, $params = [])
    {
        return $this->exec( 'BEGIN '.$plsql.' END;', $params );
    }



    /**
     * @param $seqName
     *
     * @return int
     */
    public function sequenceNextVal($seqName)
    {
        return (int)$this->fetchOne('SELECT '.$seqName.'.NEXTVAL val FROM DUAL', [], 'VAL');
    }



    /**
     * @param array $params
     *
     * @return array
     */
    private function prepareParams($params = [])
    {
        if (null == $params) $params = [];
        foreach( $params as $n => $v){
            if (is_object($v) && method_exists($v, 'getId')){
                $params[$n] = $v->getId();
            }
        }

        return $params;
    }
}