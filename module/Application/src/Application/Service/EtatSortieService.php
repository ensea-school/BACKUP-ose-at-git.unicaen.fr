<?php

namespace Application\Service;

use Application\Entity\Db\EtatSortie;
use Unicaen\OpenDocument\Document;

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



    public function generer(EtatSortie $etatSortie, array $filtres)
    {
        $fileName = $etatSortie->getLibelle() . '.pdf';

        $document = new Document();
        $document->setTmpDir(getcwd() . '/data/cache/');

        if ($etatSortie->hasFichier()) {
            $document->loadFromData(stream_get_contents($etatSortie->getFichier(), -1, 0));
        }

        $document->getPublisher()->setAutoBreak($etatSortie->isAutoBreak());
        $document->publish($this->generateData($etatSortie, $filtres));
        $document->setPdfOutput(true);
        $document->download($fileName);
    }



    private function generateData(EtatSortie $etatSortie, array $filtres)
    {
        $connection = $this->getEntityManager()->getConnection();

        if ($etatSortie->getCle()) return $this->generateDataWithCle($etatSortie, $filtres);

        if (!$etatSortie->getRequete()){
            throw new \Exception('Aucune requête n\'est associée à l\'état de sortie');
        }

        return $this->connFetch($etatSortie->getRequete(), $filtres);
    }



    private function generateDataWithCle(EtatSortie $etatSortie, array $filtres)
    {
        $cle        = $etatSortie->getCle();

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
            $bdata = $this->connBlocFetch($boptions['requete'], $etatSortie->getRequete(), $cle, $filtres);
            $blocKey = $boptions['nom'].'@'.$boptions['zone'];
            foreach( $bdata as $d ){
                if (!array_key_exists($cle, $d)) {
                    throw new \Exception('Aucune colonne de la requête de bloc "'.$bname.'" ne correspond à la clé "' . $cle . '"');
                }
                if (!isset($data[$d[$cle]][$blocKey])){
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
        foreach($filtres as $filtre => $null){
            $query .= " AND q.\"$filtre\" = :$filtre";
        }

        return $connection->fetchAll( $query, $filtres);
    }



    private function connBlocFetch(string $sql, string $mainSql, string $cle, array $filtres)
    {
        $connection = $this->getEntityManager()->getConnection();

        $query = "SELECT q.* FROM ($sql) q JOIN ($mainSql) mq ON mq.\"$cle\" = q.\"$cle\" WHERE 1=1";
        foreach($filtres as $filtre => $null){
            $query .= " AND mq.\"$filtre\" = :$filtre";
        }

        return $connection->fetchAll( $query, $filtres);
    }
}