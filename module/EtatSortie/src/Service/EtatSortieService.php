<?php

namespace EtatSortie\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Constants;
use Application\Service\AbstractEntityService;
use EtatSortie\Entity\Db\EtatSortie;
use Unicaen\OpenDocument\Document;
use UnicaenApp\View\Model\CsvModel;
use Framework\Application\Application;

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
     * @var array
     */
    private $config;



    public function getEntityClass(): string
    {
        return EtatSortie::class;
    }



    public function getAlias(): string
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



    /***
     * @param EtatSortie $etatSortie
     * @param array      $filtres
     * @param array      $options
     *
     * @return Document
     * @throws \Exception
     */
    public function genererPdf(EtatSortie $etatSortie, array $filtres, array $options = []): Document
    {
        $document = new Document();
        if (isset($this->config['host'])) {
            $document->setHost($this->config['host']);
        }
        if (isset($this->config['port'])) {
            $document->setPort((int)$this->config['port']);
        }
        if (isset($this->config['command'])) {
            $document->setConvCommand($this->config['command']);
        }
        if (isset($this->config['tmp-dir'])) {
            $document->setTmpDir($this->config['tmp-dir']);
        }

        $document->getPublisher()->setAutoBreak($etatSortie->isAutoBreak());
        $document->setPdfOutput(true);
        if ($etatSortie->hasFichier()) {
            $document->loadFromData($etatSortie->getFichier());
        } else {
            throw new \Exception('Fichier modèle au format OpenDocument non fourni dans l\'état de sortie "' . $etatSortie->getLibelle() . '"');
        }

        $entityManager = $this->getEntityManager();
        $data          = $this->generateData($etatSortie, $filtres);
        $role          = $this->getServiceContext()->getSelectedIdentityRole(); // à fournir à l'évaluateur...

        if (trim($etatSortie->getPdfTraitement())) {
            $__PHP__CODE__TRAITEMENT__ = $etatSortie->getPdfTraitement();
            // Isolation de traitement pour éviter tout débordement...
            $traitement = function () use ($document, $etatSortie, $data, $filtres, $entityManager, $role, $options, $__PHP__CODE__TRAITEMENT__) {
                $dir = getcwd();

                if (Application::getInstance()->inDev() && str_starts_with($__PHP__CODE__TRAITEMENT__, 'UnicaenCode:')) {
                    $filename = getcwd() . '/code/' . substr($__PHP__CODE__TRAITEMENT__, strlen('UnicaenCode:')) . '.php';
                    if (file_exists($filename)) {
                        require $filename;
                    } else {
                        die('Fichier "' . $filename . '" introuvable');
                    }
                } else {
                    eval($__PHP__CODE__TRAITEMENT__);
                }

                return $data;
            };
            $data       = $traitement();
        }
        if (!$document->getPublisher()->isPublished()) $document->publish($data);

        return $document;
    }



    /**
     * @param EtatSortie $etatSortie
     * @param array      $filtres
     * @param array      $options
     *
     * @return CsvModel
     * @throws \Exception
     */
    public function genererCsv(EtatSortie $etatSortie, array $filtres, array $options = []): CsvModel
    {
        $csv = new CsvModel();
        //Uniquement dans le cas de la préliquidation siham
        if ($etatSortie->getCode() == 'preliquidation-siham') {
            $periode      = (array_key_exists('periode', $options)) ? $options['periode'] : null;
            $periodeCode  = (array_key_exists('periode', $options)) ? $periode->getCode() : null;
            $filtresAnnee = (array_key_exists('ANNEE_ID', $filtres)) ? $filtres['ANNEE_ID'] : null;
        }

        $entityManager = $this->getEntityManager();
        $data          = $this->generateData($etatSortie, $filtres);
        $role          = $this->getServiceContext()->getSelectedIdentityRole(); // à fournir à l'évaluateur...


        if (trim($etatSortie->getCsvTraitement() ?? '')) {
            $__PHP__CODE__TRAITEMENT__ = $etatSortie->getCsvTraitement();
            // Isolation de traitement pour éviter tout débordement...
            $traitement = function () use ($csv, $etatSortie, $data, $filtres, $entityManager, $role, $options, $__PHP__CODE__TRAITEMENT__) {
                $dir = getcwd();

                if (Application::getInstance()->inDev() && str_starts_with($__PHP__CODE__TRAITEMENT__, 'UnicaenCode:')) {
                    $filename = getcwd() . '/code/' . substr($__PHP__CODE__TRAITEMENT__, strlen('UnicaenCode:')) . '.php';
                    if (file_exists($filename)) {
                        require $filename;
                    } else {
                        die('Fichier "' . $filename . '" introuvable');
                    }
                } else {
                    eval($__PHP__CODE__TRAITEMENT__);
                }

                return $data;
            };
            $data       = $traitement();
        }


        if (!$csv->getFilename()) {
            $csv->setFilename($etatSortie->getLibelle() . '.csv');
        }
        if (empty($csv->getHeader()) && empty($csv->getData())) {
            $params = $etatSortie->getCsvParamsArray();

            $blocs = $etatSortie->getBlocs();
            $bkey  = null;
            foreach ($blocs as $bloc) {
                $bkey = $bloc['nom'] . '@' . $bloc['zone'];
                break;
            }

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
                        $csv->addLine($this->filterData($d + $bd, $params));
                    }
                } else {
                    $csv->addLine($this->filterData($d, $params));
                }
            }

            if (isset($csv->getData()[0])) {
                $head = array_keys($csv->getData()[0]);
                foreach ($head as $k => $v) {
                    if (isset($params[$v]['libelle']) && $params[$v]['libelle']) {
                        $head[$k] = $params[$v]['libelle'];
                    }
                }
                $csv->setHeader($head);
            }
        }

        return $csv;
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
                        $date = $v ? \DateTime::createFromFormat('Y-m-d H:i:s', $v) : null;
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

        $query        = "SELECT q.* FROM ($sql) q WHERE 1=1";
        $queryFilters = $filtres;
        foreach ($filtres as $filtre => $values) {
            if (is_array($values)) {
                unset($queryFilters[$filtre]);
                $index = 0;
                $query .= " AND (";
                foreach ($values as $val) {
                    $op = '=';
                    if (str_contains($val, '%')) {
                        $op = 'LIKE';
                    }
                    if ($index > 0) {
                        $query .= ' OR ';
                    }
                    $query                          .= "q.\"$filtre\" $op :$filtre$index";
                    $queryFilters[$filtre . $index] = $val;
                    $index++;
                }
                $query .= ")";
            } else {
                if (false !== strpos($filtre, ' OR ')) {
                    $newFiltre = str_replace(' ', '_', $filtre);
                    $op        = '=';
                    if (str_contains($values, '%')) {
                        $op = 'LIKE';
                    }
                    $queryFilters[$newFiltre] = $queryFilters[$filtre];
                    unset($queryFilters[$filtre]);
                    $orFiltres = explode(" OR ", $filtre);
                    $orQuery   = '';
                    foreach ($orFiltres as $orFiltre) {
                        if ($orQuery) $orQuery .= ' OR ';
                        $orQuery .= "q.\"$orFiltre\" $op :$newFiltre";
                    }
                    $query .= " AND ($orQuery)";
                } else {
                    $op = '=';
                    if (str_contains($values, '%')) {
                        $op = 'LIKE';
                    }

                    $query .= " AND q.\"$filtre\" $op :$filtre";
                }
            }
        }

        return $connection->fetchAllAssociative($query, $queryFilters);
    }



    private function connBlocFetch(string $sql, string $mainSql, string $cle, array $filtres)
    {
        $connection = $this->getEntityManager()->getConnection();

        $query        = "SELECT q.* FROM ($sql) q JOIN ($mainSql) mq ON mq.\"$cle\" = q.\"$cle\" WHERE 1=1";
        $queryFilters = $filtres;
        foreach ($filtres as $filtre => $values) {
            if (is_array($values)) {
                unset($queryFilters[$filtre]);
                $index = 0;
                $query .= " AND (";
                foreach ($values as $val) {
                    if ($index > 0) {
                        $query .= ' OR ';
                    }
                    $query                          .= "mq.\"$filtre\" = :$filtre$index";
                    $queryFilters[$filtre . $index] = $val;
                    $index++;
                }
                $query .= ")";
            } else {
                $query .= " AND mq.\"$filtre\" = :$filtre";
            }
        }

        return $connection->fetchAllAssociative($query, $queryFilters);
    }



    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }



    /**
     * @param array $config
     *
     * @return EtatSortieService
     */
    public function setConfig(array $config): EtatSortieService
    {
        $this->config = $config;

        return $this;
    }

}