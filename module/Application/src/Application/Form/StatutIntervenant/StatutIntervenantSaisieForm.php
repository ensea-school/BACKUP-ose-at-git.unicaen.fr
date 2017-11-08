<?php

namespace Application\Form\StatutIntervenant;

use Application\Form\AbstractForm;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\TypeIntervenantAwareTrait;

/**
 * Description of StatutIntervenantSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class StatutIntervenantSaisieForm extends AbstractForm
{
    use ContextAwareTrait;
    use TypeIntervenantAwareTrait;

    public function init()
    {
        $hydrator = new StatutIntervenantHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle",
            ],
            'attributes' => [
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'service-statutaire',
            'options' => [
                'label' => "Service statutaire",
            ],
            'attributes' => [
            ],
            'type' => 'Number',
        ]);

        $this->add([
            'name' => 'plafond-referentiel',
            'options' => [
                'label' => "Plafond référentiel",
            ],
            'attributes' => [
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'depassement',
            'options' => [
                'label' => '<abbr title="Définit si la dépassement est autorisé">Dépassement</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "Définit si la dépassement est autorisé",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'fonction-e-c',
            'options' => [
                'label' => '<abbr title="fonction enseignement complémentaire">Ens Chercheur</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "fonction enseignement complémentaire",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'non-autorise',
            'options' => [
                'label' => '<abbr title="non autorisé">Non autorisé</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "non autorisé",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'peut-saisir-service',
            'options' => [
                'label' => '<abbr title="Peut saisir service">Peut saisir service</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "Peut saisir service",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'peut-choisir-dans-dossier',
            'options' => [
                'label' => '<abbr title="Peut choisir dans dossier">Peut choisir dans dossier</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "Peut choisir dans dossier",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'peut-saisir-dossier',
            'options' => [
                'label' => '<abbr title="Peut saisir dossier">Peut saisir dossier</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "Peut saisir dossier",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'peut-saisir-referentiel',
            'options' => [
                'label' => '<abbr title="peut saisir référentiel">Peut saisir référentiel</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "peut saisir référentiel",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'peut-saisir-motif-non-paiement',
            'options' => [
                'label' => '<abbr title="Peut saisir motif non paiement">Peut saisir motif non paiement</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "Peut saisir non paiement",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'peut-avoir-contrat',
            'options' => [
                'label' => '<abbr title="peut avoir contrat">Peut avoir contrat</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "peut avoir contrat",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'peut-cloturer-saisie',
            'options' => [
                'label' => '<abbr title="peut cloturer saisie">Peut cloturer saisie</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "peut cloturer saisie",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'peut-saisir-service-ext',
            'options' => [
                'label' => '<abbr title="peut saisir ext">Peut saisir service ext</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "peut saisir service ext",
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'name' => 'TEM-ATV',
            'options' => [
                'label' => '<abbr title="ATV">ATV</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "ATV",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'depassement-sdshc',
            'options' => [
                'label' => '<abbr title="depassement service du sans hc">depassement service du sans hc</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "depassement service du sans hc",
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'TEM-BIATSS',
            'options' => [
                'label' => '<abbr title="BIATSS">BIATSS</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => "BIATSS",
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'name' => 'service-statutaire',
            'options' => [
                'label' => "Service statutaire",
            ],
            'attributes' => [
            ],
            'type' => 'Number',
        ]);
        $this->add([
            'name' => 'type-intervenant',
            'options' => [
                'label' => 'type d\'intervenant',
            ],
            'attributes' => [
            ],
            'type' => 'Select',
        ]);
        $this->add([
            'name' => 'source-code',
            'options' => [
                'label' => "code",
            ],
            'attributes' => [
            ],
            'type' => 'Text',
        ]);

        $this->add([
            'name' => 'plafond-h-h-c',
            'options' => [
                'label' => "Plafond hors contrat hors heure compl.",
            ],
            'attributes' => [
            ],
            'type' => 'Number',
        ]);
        $this->add([
            'name' => 'plafond-h-c',
            'options' => [
                'label' => "Plafond hors contrat heure compl.",
            ],
            'attributes' => [
            ],
            'type' => 'Number',
        ]);
        $this->add([
            'name' => 'maximum-HETD',
            'options' => [
                'label' => "Maximum HETD",
            ],
            'attributes' => [
            ],
            'type' => 'Number',
        ]);
        $this->add([
            'name' => 'ordre',
            'options' => [
                'label' => "Ordre",
            ],
            'attributes' => [
            ],
            'type' => 'Number',
        ]);
        $this->add(new Csrf('security'));
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary'
            ],
        ]);
        // peuplement liste des types d'intervenants
        $this->get('type-intervenant')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeIntervenant()->getList()));

        return $this;
    }


    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'libelle' => [
                'required' => true,
            ],
            'service-statutaire' => [
                'required' => true,
            ],
            'plafond-referentiel' => [
                'required' => false,
            ],
            'depassement' => [
                'required' => true,
            ],
            'fonction-e-c' => [
                'required' => true,
            ],
            'non-autorise' => [
                'required' => true,
            ],
            'peut-saisir-service' => [
                'required' => true,
            ],
            'peut-saisir-service-ext' => [
                'required' => true,
            ],
            'peut-choisir-dans-dossier' => [
                'required' => true,
            ],
            'peut-saisir-dossier' => [
                'required' => true,
            ],
            'peut-saisir-referentiel' => [
                'required' => true,
            ],
            'peut-saisir-motif-non-paiement' => [
                'required' => true,
            ],
            'peut-avoir-contrat' => [
                'required' => true,
            ],
            'peut-cloturer-saisie' => [
                'required' => true,
            ],
            'TEM-ATV' => [
                'required' => true,
            ],
            'TEM-BIATSS' => [
                'required' => true,
            ],
            'maximum-HETD' => [
                'required' => true,
            ],
            'ordre' => [
                'required' => true,
            ], 
            'depassement-sdshc' => [
                'required' => true,
            ],
        ];
    }

}

class StatutIntervenantHydrator implements HydratorInterface
{

    use TypeIntervenantAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\StatutIntervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setPlafondReferentiel(isset($data['plafond-referentiel']) ? $data['plafond-referentiel']:0);
        $object->setDepassement($data['depassement']);
        $object->setServiceStatutaire($data['service-statutaire']);
        $object->setFonctionEC($data['fonction-e-c']);
        if (array_key_exists('type-intervenant', $data)) {
            $object->setTypeIntervenant($this->getServiceTypeIntervenant()->get($data['type-intervenant']));
        }
        $object->setNonAutorise($data['non-autorise']);
        $object->setPeutSaisirService($data['peut-saisir-service']);
        $object->setPeutSaisirDossier($data['peut-saisir-dossier']);
        $object->setPeutSaisirReferentiel($data['peut-saisir-referentiel']);
        $object->setPeutSaisirMotifNonPaiement($data['peut-saisir-motif-non-paiement']);
        $object->setPeutAvoirContrat($data['peut-avoir-contrat']);
        $object->setPeutCloturerSaisie($data['peut-cloturer-saisie']);
        $object->setPeutSaisirServiceExt($data['peut-saisir-service-ext']);
        $object->setTemAtv($data['TEM-ATV']);
        $object->setTemBiatss($data['TEM-BIATSS']);
        $object->setSourceCode($data['source-code']);
        $object->setPlafondHcHorsRemuFc($data['plafond-h-h-c']);
        $object->setPlafondHcRemuFc($data['plafond-h-c']);
        $object->setPeutChoisirDansDossier($data['peut-choisir-dans-dossier']);
        $object->setMaximumHETD($data['maximum-HETD']);
        $object->setOrdre($data['ordre']);
        $object->setDepassementSDSHC($data['depassement-sdshc']);
        return $object;
    }


    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\StatutIntervenant $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id' => $object->getId(),
            'libelle' => $object->getLibelle(),
            'service-statutaire' => $object->getServiceStatutaire(),
            'plafond-referentiel' => $object->getPlafondReferentiel(),
            'depassement' => $object->getDepassement(),
            'fonction-e-c' => $object->getFonctionEC(),
            'non-autorise' => $object->getNonAutorise(),
            'peut-saisir-service' => $object->getPeutSaisirService(),
            'peut-saisir-referentiel' => $object->getPeutSaisirReferentiel(),
            'peut-saisir-motif-non-paiement' => $object->getPeutSaisirMotifNonPaiement(),
            'peut-avoir-contrat' => $object->getPeutAvoirContrat(),
            'peut-cloturer-saisie' => $object->getPeutCloturerSaisie(),
            'peut-saisir-service-ext' => $object->getPeutSaisirServiceExt(),
            'TEM-ATV' => $object->getTemAtv(),
            'TEM-BIATSS' => $object->getTemBiatss(),
            'type-intervenant' => ($s = $object->getTypeIntervenant()) ? $s->getId() : null,
            'source-code' => $object->getSourceCode(),
            'plafond-h-h-c' => $object->getPlafondHcHorsRemuFc(),
            'plafond-h-c' => $object->getPlafondHcRemuFc(),
            'maximum-HETD' => $object->getMaximumHETD(),
            'ordre' => $object->getOrdre(), 
            'depassement-sdshc' => $object->getDepassementSDSHC(),
        ];

        return $data;
    }
}