<?php

namespace Application\Service;

use Application\Constants;
use Application\Entity\Db\EtatSortie;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Unicaen\OpenDocument\Document;
use UnicaenApp\View\Model\CsvModel;

/**
 * Description of EtatSortieService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method EtatSortie get($id)
 * @method EtatSortie[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method EtatSortie newEntity()
 *
 */
class EtatSortieService extends AbstractEntityService
{
    use ParametresServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return EtatSortie::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'etatsortie';
    }



    /**
     * @param string $param
     *
     * @return EtatSortie
     * @throws \Exception
     */
    public function getByParametre(string $param): EtatSortie
    {
        $etatSortieId = $this->getServiceParametres()->get($param);
        $etatSortie   = $this->get($etatSortieId);
        if (!$etatSortie) {
            throw new \Exception('Etat de sortie "' . $param . '" non configuré dans les paramètres de OSE');
        }

        return $etatSortie;
    }



    /**
     * @param EtatSortie $etatSortie
     * @param array      $filtres
     *
     * @return Document
     * @throws \Exception
     */
    public function genererPdf(EtatSortie $etatSortie, array $filtres): Document
    {
        $document = new Document();
        $document->setTmpDir(getcwd() . '/data/cache/');

        if ($etatSortie->hasFichier()) {
            $document->loadFromData(stream_get_contents($etatSortie->getFichier(), -1, 0));
        }else{
            throw new \Exception('Fichier modèle au format OpenDocument non fourni dans l\'état de sortie "'.$etatSortie->getLibelle().'"');
        }

        $document->getPublisher()->setAutoBreak($etatSortie->isAutoBreak());
        $data = $this->generateData($etatSortie, $filtres);
        $document->setPdfOutput(true);

        $document->getPublisher()->setPublished(false);

        if (trim($etatSortie->getPdfTraitement())){
            eval($etatSortie->getPdfTraitement());
        }
        if (!$document->getPublisher()->isPublished()) $document->publish($data);

        return $document;
    }



    /**
     * @param EtatSortie $etatSortie
     * @param array      $filtres
     *
     * @return CsvModel
     * @throws \Exception
     */
    public function genererCsv(EtatSortie $etatSortie, array $filtres): CsvModel
    {
        $params = $etatSortie->getCsvParamsArray();
        $data   = $this->generateData($etatSortie, $filtres);

        $blocs = $etatSortie->getBlocs();
        $bkey  = null;
        foreach ($blocs as $bloc) {
            $bkey = $bloc['nom'] . '@' . $bloc['zone'];
            break;
        }

        $csvModel = new CsvModel();

        foreach ($data as $k => $d) {

            /* On récupère les sous-données éventuelles */
            if (array_key_exists($bkey, $d)) {
                $bdata = $d[$bkey];
            } else {
                $bdata = null;
            }

            /* On supprime toutes les sous-données */
            foreach ($d as $dk => $dv) {
                if (false !== strpos($dk, '@')) {
                    unset($d[$dk]);
                }
            }

            /* Si il y a des sous-données */
            if ($bdata) {
                foreach ($bdata as $bd) {
                    $csvModel->addLine($this->filterData($d + $bd, $params));
                }
            } else {
                $csvModel->addLine($this->filterData($d, $params));
            }
        }

        $csvModel->setFilename($etatSortie->getLibelle() . '.csv');
        if (isset($csvModel->getData()[0])) {
            $head = array_keys($csvModel->getData()[0]);
            foreach ($head as $k => $v) {
                if (isset($params[$v]['libelle']) && $params[$v]['libelle']) {
                    $head[$k] = $params[$v]['libelle'];
                }
            }
            $csvModel->setHeader($head);
        }

        return $csvModel;
    }



    private function filterData(array $line, array $params): array
    {
        foreach ($line as $k => $v) {
            if (!(isset($params[$k]['visible']) ? $params[$k]['visible'] : true)) {
                unset($line[$k]);
            } else {
                $type = isset($params[$k]['type']) ? $params[$k]['type'] : 'string';
                switch (strtolower($type)) {
                    case 'float':
                        $line[$k] = (float)$v;
                    break;
                    case 'date':
                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $v);
                        if ($date instanceof \DateTime) {
                            $format   = isset($params[$k]['format']) ? $params[$k]['format'] : Constants::DATE_FORMAT;
                            $line[$k] = $date->format($format);
                        }
                    break;
                }
            }
        }

        return $line;
    }



    private function generateData(EtatSortie $etatSortie, array $filtres)
    {
        if ($etatSortie->getCle()) return $this->generateDataWithCle($etatSortie, $filtres);

        if ($etatSortie->getRequete()) {
            return $this->connFetch($etatSortie->getRequete(), $filtres);
        } else {
            $blocs = $etatSortie->getBlocs();
            foreach ($blocs as $bloc) {
                return [0 => [$bloc['nom'] . '@' . $bloc['zone'] => $this->connFetch($bloc['requete'], $filtres)]];
            }
        }

        throw new \Exception('Aucune requête n\'est associée à l\'état de sortie');
    }



    private function generateDataWithCle(EtatSortie $etatSortie, array $filtres)
    {
        $cle = $etatSortie->getCle();

        $data = [];

        if ($etatSortie->getRequete()) {
            $rdata = $this->connFetch($etatSortie->getRequete(), $filtres);
            foreach ($rdata as $d) {
                if (!array_key_exists($cle, $d)) {
                    throw new \Exception('Aucune colonne de la requête ne correspond à la clé "' . $cle . '"');
                }
                $data[$d[$cle]] = $d;
            }
        }

        $blocs = $etatSortie->getBlocs();
        foreach ($blocs as $bname => $boptions) {
            $bdata   = $this->connBlocFetch($boptions['requete'], $etatSortie->getRequete(), $cle, $filtres);
            $blocKey = $boptions['nom'] . '@' . $boptions['zone'];
            foreach ($bdata as $d) {
                if (!array_key_exists($cle, $d)) {
                    throw new \Exception('Aucune colonne de la requête de bloc "' . $bname . '" ne correspond à la clé "' . $cle . '"');
                }
                if (!isset($data[$d[$cle]][$blocKey])) {
                    $data[$d[$cle]][$blocKey] = [];
                }

                $data[$d[$cle]][$blocKey][] = $d;
            }
        }

        return $data;
    }



    private function connFetch(string $sql, array $filtres)
    {
        $connection = $this->getEntityManager()->getConnection();

        $query = "SELECT q.* FROM ($sql) q WHERE 1=1";
        $queryFilters = $filtres;
        foreach ($filtres as $filtre => $values) {
            if (is_array($values)){
                unset($queryFilters[$filtre]);
                $index = 0;
                $query .= " AND (";
                foreach( $values as $val ){
                    if ($index > 0){
                        $query .=  ' OR ';
                    }
                    $query .= "q.\"$filtre\" = :$filtre$index";
                    $queryFilters[$filtre.$index] = $val;
                    $index++;
                }
                $query .= ")";
            }else{
                $query .= " AND q.\"$filtre\" = :$filtre";
            }

        }

        return $connection->fetchAll($query, $queryFilters);
    }



    private function connBlocFetch(string $sql, string $mainSql, string $cle, array $filtres)
    {
        $connection = $this->getEntityManager()->getConnection();

        $query = "SELECT q.* FROM ($sql) q JOIN ($mainSql) mq ON mq.\"$cle\" = q.\"$cle\" WHERE 1=1";
        $queryFilters = $filtres;
        foreach ($filtres as $filtre => $values) {
            if (is_array($values)){
                unset($queryFilters[$filtre]);
                $index = 0;
                $query .= " AND (";
                foreach( $values as $val ){
                    if ($index > 0){
                        $query .=  ' OR ';
                    }
                    $query .= "mq.\"$filtre\" = :$filtre$index";
                    $queryFilters[$filtre.$index] = $val;
                    $index++;
                }
                $query .= ")";
            }else{
                $query .= " AND mq.\"$filtre\" = :$filtre";
            }
        }

        return $connection->fetchAll($query, $queryFilters);
    }
}