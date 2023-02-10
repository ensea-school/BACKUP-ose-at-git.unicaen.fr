<?php

namespace Application\Service;

use Application\Entity\Db\Dossier;
use Application\Entity\Db\DossierAutre;
use Application\Entity\Db\Modulateur;
use Application\Entity\Db\ElementPedagogique;
use OffreFormation\Service\Traits\ElementModulateurServiceAwareTrait;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use http\Exception;


/**
 * Description of DossierAutreService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 * @method DossierAutre get($id)
 * @method DossierAutre[] getList(QueryBuilder $qb = null, $alias = null)
 */
class DossierAutreService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\DossierAutre::class;
    }



    public function getValueOptionsBySql(DossierAutre $dossierAutre)
    {
        $datas = [];
        /**
         * @var DossierAutre
         */
        $sql = $dossierAutre->getSqlValue();
        if (!empty($sql)) {

            $connection = $this->getEntityManager()->getConnection();
            $result     = $connection->fetchAllAssociative($sql);
            foreach ($result as $k => $v) {
                $datas [$v['VALUE_OPTION']] = $v['VALUE_OPTION'];
            }
        }

        return $datas;
    }



    public function getValueOptionByJson(DossierAutre $dossierAutre)
    {
        $datas = [];
        /**
         * @var DossierAutre
         */
        $json = $dossierAutre->getJsonValue();
        if (!empty($json)) {
            try {
                $jsonToArray = json_decode($json, true);
                foreach ($jsonToArray as $v) {
                    $datas[$v] = $v;
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return $datas;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'dossierAutre';
    }
}