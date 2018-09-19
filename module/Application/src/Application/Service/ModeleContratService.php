<?php

namespace Application\Service;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\ModeleContrat;
use Unicaen\OpenDocument\Document;

/**
 * Description of ModeleContratService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method ModeleContrat get($id)
 * @method ModeleContrat[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method ModeleContrat newEntity()
 *
 */
class ModeleContratService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return ModeleContrat::class;
    }



    /**
     * @param Contrat $contrat
     *
     * @return ModeleContrat|null
     */
    public function getByContrat(Contrat $contrat)
    {
        $modeles = $this->getList();

        usort($modeles, function (ModeleContrat $m1, ModeleContrat $m2) use ($contrat) {
            return $this->getRank($m1,$contrat) < $this->getRank($m2,$contrat);
        });

        $modele = reset($modeles);

        return $modele;
    }



    public function generer( Contrat $contrat)
    {
        $fileName = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
            $contrat->getStructure()->getCode(),
            $contrat->getIntervenant()->getNomUsuel(),
            $contrat->getIntervenant()->getCode());

        $modele = $this->getByContrat($contrat);

        if (!$modele){
            throw new \Exception('Aucun modèle ne correspond à ce contrat');
        }

        $document = new Document();
        $document->setTmpDir(getcwd().'/data/cache/');
        $document->loadFromData($modele->getFichier());
        if ($contrat->estUnProjet()){
            $document->getStylist()->addFiligrane('PROJET');
        }
        $document->publish($this->generateData($modele, $contrat));
        $document->setPdfOutput(true);
        $document->download($fileName);
    }



    private function generateData( ModeleContrat $modele, Contrat $contrat)
    {
        if (!$modele->getRequete()){
            throw new \Exception('Impossible de générer le contrat: pas de requête pour le modèle "'.$modele->getLibelle().'"');
        }

        $connection = $this->getEntityManager()->getConnection();

        $params = ['contrat' => $contrat->getId()];
        $data = [0 => $connection->fetchAssoc($modele->getRequete(), $params)];

        $blocs = $modele->getBlocs();
        foreach($blocs as $bname => $bquery){
            $bdata = $connection->fetchAll($bquery, $params);
            $bkey = $bname.'@table:table-row';
            $data[0][$bkey] = $bdata;
        }

        return $data;
    }



    private function getRank(ModeleContrat $modele, Contrat $contrat)
    {
        $rank = 100;

        if ($modele->getStructure() && $contrat->getStructure()) {
            if ($modele->getStructure() == $contrat->getStructure()) {
                $rank += 40;
            } else {
                return 0;
            }
        }

        if ($modele->getStatutIntervenant() && $contrat->getIntervenant()->getStatut()) {
            if ($modele->getStatutIntervenant() == $contrat->getIntervenant()->getStatut()) {
                $rank += 55;
            } else {
                return 0;
            }
        }

        return $rank;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'modele_contrat';
    }

}