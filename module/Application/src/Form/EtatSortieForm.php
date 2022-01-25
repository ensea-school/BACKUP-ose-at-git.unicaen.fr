<?php

namespace Application\Form;

use Application\Entity\Db\EtatSortie;
use Intervenant\Service\StatutServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;


/**
 * Description of EtatSortieForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EtatSortieForm extends AbstractForm
{
    public function init()
    {
        $hydrator = new EtatSortieHydrator;
        $this->setHydrator($hydrator);


        $this->setAttributes([
            'action'  => $this->getCurrentUrl(),
            'class'   => 'etat-sortie-saisir',
            'enctype' => 'multipart/form-data',
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'code',
            'options' => [
                'label' => "Code",
            ],
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'libelle',
            'options' => [
                'label' => "Libellé",
            ],
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'cle',
            'options' => [
                'label' => "Champ clé",
            ],
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'csv-params',
            'options'    => [
                'label' => "Paramètres d'export CSV (format JSON)",
            ],
            'attributes' => [
                'id'   => 'csv-params',
                'rows' => '25',
            ],
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'pdf-traitement',
            'options'    => [
                'label' => "Traitement des données",
            ],
            'attributes' => [
                'id'   => 'pdf-traitement',
                'rows' => '25',
            ],
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'csv-traitement',
            'options'    => [
                'label' => "Traitement des données",
            ],
            'attributes' => [
                'id'   => 'csv-traitement',
                'rows' => '25',
            ],
        ]);

        $this->add([
            'type'    => 'Checkbox',
            'name'    => 'auto-break',
            'options' => [
                'label'              => 'Saut de page automatique',
                'use_hidden_element' => true,
                'checked_value'      => 'true',
                'unchecked_value'    => 'false',
            ],
        ]);

        $this->add([
            'type'       => 'File',
            'name'       => 'fichier',
            'options'    => [
                'label'         => "Modèle au format OpenDocument Texte (ODT) <small>(à fournir seulement si changement)</small>",
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                'id'       => 'fichier',
                'multiple' => false,
                'accept'   => 'application/vnd.oasis.opendocument.text',
            ],
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'requete',
            'options'    => [
                'label' => "Requête SQL",
            ],
            'attributes' => [
                'id'   => 'requete',
                'rows' => '20',
            ],
        ]);

        for ($i = 1; $i <= 10; $i++) {
            $this->add([
                'type'       => 'Text',
                'name'       => "bloc-$i-nom",
                'options'    => [
                    'label' => "Nom",
                ],
                'attributes' => [
                    'class' => 'form-control bloc-nom',
                    'style' => 'width:30%',
                ],
            ]);

            $this->add([
                'type'       => 'Select',
                'name'       => "bloc-$i-zone",
                'options'    => [
                    'label'         => "",
                    'value_options' => [
                        'table:table-row' => 'Tableau',
                    ],
                ],
                'attributes' => [
                    'class' => 'form-control bloc-zone',
                    'style' => 'width:30%',
                ],
            ]);

            $this->add([
                'type'       => 'Textarea',
                'name'       => "bloc-$i-requete",
                'options'    => [
                    'label' => "Requête générale",
                ],
                'attributes' => [
                    'id'   => "bloc-$i-requete",
                    'rows' => '15',
                ],
            ]);
        }

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary btn-save',
            ],
        ]);
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters = [
        ];

        for ($i = 1; $i <= 10; $i++) {
            $filters["bloc-$i-nom"]     = ['required' => false];
            $filters["bloc-$i-requete"] = ['required' => false];
        }

        return $filters;
    }
}





class EtatSortieHydrator implements HydratorInterface
{
    use StatutServiceAwareTrait;
    use StructureServiceAwareTrait;


    /**
     * @param array      $data
     * @param EtatSortie $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setCle($data['cle']);
        $object->setCsvParams($data['csv-params']);
        $object->setPdfTraitement($data['pdf-traitement']);
        $object->setCsvTraitement($data['csv-traitement']);
        $object->setAutoBreak($data['auto-break'] === 'true');
        $object->setRequete($data['requete']);
        if (isset($data['fichier']['tmp_name']) && $data['fichier']['tmp_name']) {
            $object->setFichier(file_get_contents($data['fichier']['tmp_name']));
            unlink($data['fichier']['tmp_name']);
        }

        $blocs = [];

        for ($i = 1; $i <= 10; $i++) {
            if (isset($data["bloc-$i-nom"]) && $data["bloc-$i-nom"]
                && isset($data["bloc-$i-requete"]) && $data["bloc-$i-requete"]) {
                $blocs[$data["bloc-$i-nom"]] = [
                    'nom'     => $data["bloc-$i-nom"],
                    'zone'    => $data["bloc-$i-zone"],
                    'requete' => $data["bloc-$i-requete"],
                ];
            }
        }
        $object->setBlocs($blocs);

        return $object;
    }



    /**
     * @param EtatSortie $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'code'           => $object->getCode(),
            'libelle'        => $object->getLibelle(),
            'cle'            => $object->getCle(),
            'csv-params'     => $object->getCsvParams(),
            'pdf-traitement' => $object->getPdfTraitement(),
            'csv-traitement' => $object->getCsvTraitement(),
            'auto-break'     => $object->isAutoBreak() ? 'true' : 'false',
            'requete'        => $object->getRequete(),
        ];

        $blocs = $object->getBlocs();
        $i     = 1;
        foreach ($blocs as $nom => $boptions) {
            $data["bloc-$i-nom"]     = $boptions['nom'];
            $data["bloc-$i-zone"]    = $boptions['zone'];
            $data["bloc-$i-requete"] = $boptions['requete'];
            $i++;
        }

        return $data;
    }
}