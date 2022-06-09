<?php

namespace Application\Service;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\ModeleContrat;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Unicaen\OpenDocument\Document;
use Laminas\Mail\Message as MailMessage;
use Laminas\Mime\Message;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;

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

    use DossierServiceAwareTrait;
    use ContextServiceAwareTrait;


    /**
     * @var array
     */
    private $config;



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
            return $this->getRank($m1, $contrat) < $this->getRank($m2, $contrat) ? 1 : 0;
        });

        $modele = reset($modeles);

        return $modele;
    }



    public function generer(Contrat $contrat, $download = true)
    {
        $fileName = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
            ($contrat->getStructure() == null ? null : $contrat->getStructure()->getCode()),
            $contrat->getIntervenant()->getNomUsuel(),
            $contrat->getIntervenant()->getCode());

        $modele = $this->getByContrat($contrat);

        if (!$modele) {
            throw new \Exception('Aucun modèle ne correspond à ce contrat');
        }

        $document = new Document();
        if (isset($this->config['host'])) {
            $document->setHost($this->config['host']);
        }
        if (isset($this->config['tmp-dir'])) {
            $document->setTmpDir($this->config['tmp-dir']);
        }

        if ($modele->hasFichier()) {
            $document->loadFromData(stream_get_contents($modele->getFichier(), -1, 0));
        } else {
            $document->loadFromFile($this->getModeleGeneriqueFile(), true);
        }

        if ($contrat->estUnProjet()) {
            $document->getStylist()->addFiligrane('PROJET');
        }
        $document->getPublisher()->setAutoBreak(true);
        $document->publish($this->generateData($modele, $contrat));
        $document->setPdfOutput(true);
        if ($download) {
            $document->download($fileName);
        } else {

            return $document;
        }
    }



    public function prepareMail(Contrat $contrat, string $htmlContent, string $from, string $to, string $cci = null, string $subject = null)
    {
        $fileName = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
            ($contrat->getStructure() == null ? null : $contrat->getStructure()->getCode()),
            $contrat->getIntervenant()->getNomUsuel(),
            $contrat->getIntervenant()->getCode());

        $document = $this->generer($contrat, false);
        $content  = $document->saveToData();

        if (empty($subject)) {
            $subject = "Contrat " . $contrat->getIntervenant()->getCivilite() . " " . $contrat->getIntervenant()->getNomUsuel();
        }


        if (empty($to)) {
            throw new \Exception("Aucun email disponible pour le destinataire / Envoi du contrat impossible");
        }
        if (empty($from)) {
            throw new \Exception("Aucun email disponible pour l'expéditeur / Envoi du contrat impossible");
        }
        $bcc = [];
        if (!empty($cci)) {
            $bcc = explode(';', $cci);
        }

        $body = new Message();

        $text          = new Part($htmlContent);
        $text->type    = Mime::TYPE_HTML;
        $text->charset = 'utf-8';
        $body->addPart($text);
        $nameFrom = "Application OSE";


        //Contrat en pièce jointe
        $attachment              = new Part($content);
        $attachment->type        = 'application/pdf';
        $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding    = Mime::ENCODING_BASE64;
        $attachment->filename    = $fileName;
        $body->addPart($attachment);

        $message     = new MailMessage();
        $messageType = 'multipart/related';
        $message->setEncoding('UTF-8')
            ->setFrom($from, $nameFrom)
            ->setSubject($subject)
            ->addTo($to)
            ->addBcc($bcc)
            ->setBody($body)
            ->getHeaders()->get('content-type')->setType($messageType);

        return $message;
    }



    /**
     * @return string
     */
    public function getModeleGeneriqueFile(): string
    {
        return getcwd() . '/data/modele_contrat.odt';
    }



    private function generateData(ModeleContrat $modele, Contrat $contrat)
    {
        $connection = $this->getEntityManager()->getConnection();

        $params = ['contrat' => $contrat->getId()];

        $mainData = $connection->fetchAssociative('SELECT * FROM V_CONTRAT_MAIN WHERE CONTRAT_ID = :contrat', $params);
        if ($modele->getRequete()) {
            $mainDataPerso = $connection->fetchAssociative($modele->getRequete(), $params);
            foreach ($mainDataPerso as $key => $value) {
                // if ($value) {
                $mainData[$key] = $value;
                // }
            }
        }

        $data = [0 => $mainData];

        $blocs = $modele->getBlocs();
        foreach ($blocs as $bname => $bquery) {
            $bdata = $connection->fetchAllAssociative($bquery, $params);
            $bkey  = $bname . '@table:table-row';

            $data[0][$bkey] = $bdata;
        }

        if (!isset($data[0]['serviceCode@table:table-row'])
            && !isset($data[0]['serviceComposante@table:table-row'])
            && !isset($data[0]['serviceLibelle@table:table-row'])
            && !isset($data[0]['serviceHeures@table:table-row'])
        ) {
            $data[0]['serviceCode@table:table-row'] =
                $connection->fetchAllAssociative('SELECT * FROM V_CONTRAT_SERVICES WHERE CONTRAT_ID = :contrat', $params);
        }

        if (isset($mainData['exemplaire1']) && $mainData['exemplaire1'] && ('0' !== $mainData['exemplaire1'])) {
            $data[0]['exemplaire'] = $mainData['exemplaire1'];
            unset($mainData['exemplaire1']);
        }
        if (isset($mainData['exemplaire2']) && $mainData['exemplaire2'] && ('0' !== $mainData['exemplaire2'])) {
            $data[1]               = $data[0];
            $data[1]['exemplaire'] = $mainData['exemplaire2'];
            unset($mainData['exemplaire2']);
        }
        if (isset($mainData['exemplaire3']) && $mainData['exemplaire3'] && ('0' !== $mainData['exemplaire3'])) {
            $data[2]               = $data[0];
            $data[2]['exemplaire'] = $mainData['exemplaire3'];
            unset($mainData['exemplaire3']);
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

        if ($modele->getStatut() && $contrat->getIntervenant()->getStatut()) {
            if ($modele->getStatut()->getCode() == $contrat->getIntervenant()->getStatut()->getCode()) {
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
     * @return ModeleContratService
     */
    public function setConfig(array $config): ModeleContratService
    {
        $this->config = $config;

        return $this;
    }
}