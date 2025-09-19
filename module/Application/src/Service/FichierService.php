<?php

namespace Application\Service;

use Application\Entity\Db\Fichier;
use Framework\Application\Application;

/**
 * Description of FichierService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Fichier get($id)
 * @method Fichier[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Fichier newEntity()
 *
 */
class FichierService extends AbstractEntityService
{
    const STOCKAGE_BDD  = 'bdd';
    const STOCKAGE_FILE = 'file';



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Fichier::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'fich';
    }



    /**
     * @param Fichier $entity
     *
     * @return mixed
     */
    public function save($entity)
    {
        $stockage = self::getConfigStockage();
        if (self::STOCKAGE_FILE == $stockage) {
            $contenuBdd = $entity->getContenu(true);
            if (is_resource($contenuBdd)) {
                $contenuBdd = stream_get_contents($contenuBdd, -1, 0);
            }
            $entity->setContenu(null);
        }
        parent::save($entity);

        if ((self::STOCKAGE_FILE == $stockage) && $contenuBdd) {
            $filename = $this->getFichierFilename($entity);
            if (!file_exists(dirname($filename))) {
                mkdir(dirname($filename));
                chmod(dirname($filename), 0777);
            }
            $r = file_put_contents($filename, $contenuBdd);
            if (!$r || !file_exists($filename)) {
                $entity->setContenu($contenuBdd);
                parent::save($entity);
            }
        }

        return $entity;
    }



    public function getFichierFilename(Fichier $fichier): string
    {
        if (!$fichier->getId()) {
            throw new \Exception('Le contenu ne peut pas être récupéré ou stocké : le fichier n\'a pas d\'ID');
        }

        $id       = $fichier->getId();
        $filename = 'd' . (str_pad((string)floor($id / 1000), 4, '0', STR_PAD_LEFT))
            . '/f'
            . str_pad((string)($id % 1000), 3, '0', STR_PAD_LEFT);

        return $this->getConfigDir() . $filename;
    }




    public function isValide(Fichier $fichier): bool
    {
        $exts = [
            'pdf', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'tif', 'tiff', 'rtf', 'txt', 'csv', 'html', 'htm', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odg', 'odp',
        ];
        $ext  = strtolower($fichier->getNom());
        $ext  = substr($ext, strrpos($ext, '.') + 1);

        if (in_array($ext, $exts)) return true;


        $patterns = [
            // PDF
            '#application/pdf$#i',
            '#^image/pdf$#i',
            '#^application/rugpdf$#i',
            '#^application/x-unknown-application-pdf$#i',
            '#^application/x-pdf$#i',
            '#^document/pdf$#i',
            '#^pdf/pdf$#i',
            '#^text/pdf$#i',
            '#^pdf/application$#i',

            // Images
            '#^image/jpeg$#i',
            '#^image/png$#i',
            '#^application/png$#i',
            '#^image/tiff$#i',
            '#^image/gif$#i',
            '#^image/bmp$#i',
            '#^image/pjpeg$#i',
            '#^image/heic$#i',

            // Bureautique
            '#^application/msword$#i',
            '#^application/vnd.openxmlformats-officedocument#i',
            '#^application/vnd.oasis.opendocument.#i',
            '#^application/xls$#i',
            '#^application/x-msword$#i',
            '#^application/doc$#i',
            '#^application/vnd.ms-xpsdocument#i',
            '#^application/vnd.ms-word#i',
            '#^application/vnd.ms-powerpoint#i',
            '#^application/vnd.ms-excel#i',
            '#^text/rtf$#i',
            '#^application/docx$#i',
            '#^application/rtf$#i',

            // Texte
            '#^text/plain$#i',
            '#^application/csv$#i',
            '#^text/html$#i',
            '#^text/richtext$#i',

        ];

        $mime = str_replace('"', '', $fichier->getTypeMime());
        $mime = str_replace("'", '', $mime);
        $mime = str_replace("%22", '', $mime);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $mime)) return true;
        }

        return false;
    }



    public function getConfigStockage(): string
    {
        $stockage = Application::getInstance()->config()['fichiers']['stockage'] ?? 'bdd';

        return $stockage;
    }



    protected function getConfigDir(): string
    {
        $dir = Application::getInstance()->config()['fichiers']['dir'] ?? 'data/fichiers';

        if (substr($dir, -1) != '/') $dir .= '/';

        return $dir;
    }



    public function getFichierContenu(Fichier $fichier)
    {
        $stockage = self::getConfigStockage();
        if (self::STOCKAGE_FILE == $stockage) {
            $filename = $this->getFichierFilename($fichier);
            if (file_exists($filename)) {
                return fopen($filename, 'r');
            }
        }

        return $fichier->getContenu(true);
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param Fichier $entity Entité à détruire
     * @param bool    $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if (!$softDelete) {
            $sql = "DELETE FROM PIECE_JOINTE_FICHIER WHERE FICHIER_ID = " . (int)$entity->getId();
            $this->getEntityManager()->getConnection()->executeQuery($sql);
        }
        $stockage = self::getConfigStockage();
        if (self::STOCKAGE_FILE == $stockage) {
            $filename = $this->getFichierFilename($entity);
            if (file_exists($filename)) {
                unlink($filename);
            }
        }

        return parent::delete($entity, $softDelete); // TODO: Change the autogenerated stub
    }

}