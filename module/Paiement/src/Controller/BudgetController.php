<?php

namespace Paiement\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Lieu\Entity\Db\Structure;
use Lieu\Form\Element\Structure as StructureElement;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Entity\Db\Dotation;
use Paiement\Entity\Db\TypeRessource;
use Paiement\Form\Budget\DotationSaisieFormAwareTrait;
use Paiement\Service\BudgetServiceAwareTrait;
use Paiement\Service\DotationServiceAwareTrait;
use Paiement\Service\TypeRessourceServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenVue\View\Model\AxiosModel;


/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class BudgetController extends AbstractController
{
    use StructureServiceAwareTrait;
    use ContextServiceAwareTrait;
    use TypeRessourceServiceAwareTrait;
    use DotationServiceAwareTrait;
    use DotationSaisieFormAwareTrait;
    use AnneeServiceAwareTrait;
    use BudgetServiceAwareTrait;


    public function indexAction ()
    {
        return [];
    }



    public function tableauDeBordAction ()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Structure::class,
            TypeRessource::class,
            Dotation::class,
        ]);

        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');

        $annee = $this->getServiceContext()->getAnnee();

        $tbl = $this->getServiceDotation()->getTableauBord();
        $prv = $this->getServiceBudget()->getTotalPrevisionnelValide();
        $liq = $this->getServiceBudget()->getTblLiquidation();

        $typesRessources = $this->getServiceTypeRessource()->getList();
        /* @var $typesRessources TypeRessource[] */
        $qb = $this->getServiceStructure()->finderByHistorique();
        if ($structure) $this->getServiceStructure()->finderByStructure($structure, $qb);
        $structures = $this->getServiceStructure()->getList($qb);
        /* @var $structures Structure[] */

        $data = [];
        foreach ($structures as $sid => $structure) {

            $hab = isset($tbl[$sid]['total']) ? $tbl[$sid]['total'] : 0;
            $hli = isset($prv[$sid]) ? $prv[$sid] : 0;

            $data[$sid]['prev'] = $hab - $hli; // Solde abondé - ce qui a été liquidé (dépensé)

            foreach ($typesRessources as $trid => $typeRessource) {

                $hab = isset($tbl[$sid][$trid]) ? $tbl[$sid][$trid] : 0;
                $hli = isset($liq[$sid][$trid]) ? $liq[$sid][$trid] : 0;

                $data[$sid]['dmep'][$trid] = $hab - $hli; // Solde abondé - ce qui a été liquidé (dépensé)
            }
        }

        return compact('annee', 'structures', 'typesRessources', 'data');
    }



    public function getJsonAction ()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Structure::class,
            TypeRessource::class,
            Dotation::class,
        ]);

        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');

        $tbl = $this->getServiceDotation()->getTableauBord();
        $liq = $this->getServiceBudget()->getTblLiquidation();

        $typesRessources = $this->getServiceTypeRessource()->getList();
        /* @var $typesRessources TypeRessource[] */
        $qb = $this->getServiceStructure()->finderByHistorique();
        if ($structure) $this->getServiceStructure()->finderById($structure->getId(), $qb);
        $structures = $this->getServiceStructure()->getList($qb);
        /* @var $structures Structure[] */

        $data = [];
        foreach ($structures as $sid => $structure) {

            $hab = isset($tbl[$sid]['total']) ? $tbl[$sid]['total'] : 0;

            foreach ($typesRessources as $trid => $typeRessource) {

                $hab = isset($tbl[$sid][$trid]) ? $tbl[$sid][$trid] : 0;
                $hli = isset($liq[$sid][$trid]) ? $liq[$sid][$trid] : 0;

                $data[$sid][$trid] = [
                    'dotation' => $hab,
                    'usage'    => $hli,
                ];
            }
        }

        return new JsonModel($data);
    }



    function getBudgetStructureAction ()
    {
        $budget    = [
            'dotation'    => [
                'paieEtat'        => 0,
                'ressourcePropre' => 0,
                'total'           => 0,
            ],
            'liquidation' => [
                'paieEtat'        => 0,
                'ressourcePropre' => 0,
                'total'           => 0,
            ],

        ];
        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');
        if ($structure instanceof Structure) {
            $dotation    = $this->getServiceDotation()->getTableauBord([$structure->getId()]);
            $liquidation = $this->getServiceBudget()->getTblLiquidation($structure);
            foreach ($dotation as $key => $value) {
                if ($key == $structure->getId()) {
                    $budget['dotation']['paieEtat']        = (key_exists(1, $value)) ? $value['1'] : 0;
                    $budget['dotation']['ressourcePropre'] = (key_exists(2, $value)) ? $value['2'] : 0;
                    $budget['dotation']['total']           = $value['total'];
                    break;
                }
            }
            //liquidation
            $budget['liquidation']['paieEtat']        = (key_exists('1', $liquidation)) ? $liquidation['1'] : 0;
            $budget['liquidation']['ressourcePropre'] = (key_exists('2', $liquidation)) ? $liquidation['2'] : 0;
            $budget['liquidation']['total']           = $liquidation['total'];
        }


        return new AxiosModel($budget);
    }



    public function engagementsLiquidationAction ()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Structure::class,
            TypeRessource::class,
            Dotation::class,
        ]);

        $annee     = $this->getServiceContext()->getAnnee();
        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $this->getEvent()->getParam('structure');
        if (!$structure) {
            $structure = $role->getStructure();
        }
        /* @var $structure Structure */
        $structureElement = $this->getStructureElement($structure);

        $liquidation = [];
        if ($structure) {
            $dotations          = $this->getServiceDotation()->getDotations($structure);
            $previsionnelValide = $this->getServiceBudget()->getTotalPrevisionnelValide($structure);
            $ld                 = $this->getServiceBudget()->getTblLiquidation($structure);
            foreach ($dotations['typesRessources'] as $dtrId => $dtr) {
                $typeRessource = $dtr['entity'];
                /* @var $typeRessource TypeRessource */

                $dmep                = isset($ld[$dtrId]) ? $ld[$dtrId] : 0;
                $liquidation[$dtrId] = [
                    'dmep'  => $dmep,
                    'solde' => $dotations['typesRessources'][$dtrId]['total']['heures'] - $dmep,
                ];
            }
        } else {
            $dotations          = [];
            $previsionnelValide = 0;
        }

        return compact(
            'annee', 'structureElement', 'structure', 'dotations', 'previsionnelValide', 'liquidation'
        );
    }



    public function saisieDotationAction ()
    {
        $annee     = $this->getEvent()->getParam('annee');
        $anneePrec = $this->getServiceAnnee()->getPrecedente($annee);
        $structure = $this->getEvent()->getParam('structure');
        $libelle   = $this->params()->fromQuery('libelle');
        /* @var $structure Structure */
        $typeRessource = $this->getEvent()->getParam('typeRessource');
        /* @var $typeRessource TypeRessource */

        $title = 'Saisie de dotation ' . $typeRessource . ' / ' . $structure;

        $dotation1 = $this->getServiceDotation()->get($this->params()->fromRoute('dotation1'));
        /* @var $dotation1 Dotation */
        if (!$dotation1) {
            $dotation1 = $this->getServiceDotation()->newEntity()
                ->setAnnee($anneePrec)
                ->setAnneeCivile($annee->getId())
                ->setStructure($structure)
                ->setTypeRessource($typeRessource);
        }

        $dotation2 = $this->getServiceDotation()->get($this->params()->fromRoute('dotation2'));
        /* @var $dotation2 Dotation */
        if (!$dotation2) {
            $dotation2 = $this->getServiceDotation()->newEntity()
                ->setAnnee($annee)
                ->setAnneeCivile($annee->getId())
                ->setStructure($structure)
                ->setTypeRessource($typeRessource);
        }

        if (!$this->isAllowed($dotation1, $typeRessource->getPrivilegeBudgetEdition())) {
            $this->flashMessenger()->addErrorMessage('Vous n\'êtes pas autorisé(e) à éditer ces informations');
            $form = null;
        } else {
            $form = $this->getFormBudgetDotationSaisie();
            $form->get('structure')->setOption('root', $structure);
            $form->get('structure')->setValue($structure->getId());
            $form->get('annee1')->setValue($dotation1->getHeures())->setLabel('Dont, au titre de ' . $anneePrec);
            $form->get('annee2')->setValue($dotation2->getHeures())->setLabel('Dont, au titre de ' . $annee);
            $form->get('anneeCivile')->setValue($dotation1->getHeures() + $dotation2->getHeures())->setLabel('Année civile ' . ($annee->getId()));
            $form->get('libelle')->setValue($libelle);

            $form->requestSave($this->getRequest(), function ($data) use ($dotation1, $dotation2) {
                $structure = $this->getServiceStructure()->get($data['structure']);
                $h1        = (float)str_replace([',', ' '], ['.', ''], $data['annee1']);
                if ($dotation1->getId() && 0 == $h1) {
                    $this->getServiceDotation()->delete($dotation1);
                } else {
                    $dotation1->setStructure($structure);
                    $dotation1->setLibelle($data['libelle']);
                    $dotation1->setHeures($h1);
                    $this->getServiceDotation()->save($dotation1);
                }

                $h2 = (float)str_replace([',', ' '], ['.', ''], $data['annee2']);
                if ($dotation2->getId() && 0 == $h2) {
                    $this->getServiceDotation()->delete($dotation2);
                } else {
                    $dotation2->setStructure($structure);
                    $dotation2->setLibelle($data['libelle']);
                    $dotation2->setHeures($h2);
                    $this->getServiceDotation()->save($dotation2);
                }
            });
        }

        return compact('form', 'title', 'structure', 'typeRessource');
    }



    public function exportAction ()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $structure = $this->getEvent()->getParam('structure');
        if (!$structure) {
            $structure = $role->getStructure();
        }

        $data = $this->getServiceBudget()->getTableauBord($structure);

        $csvModel = new CsvModel();
        $csvModel->setHeader([
            'annee-libelle' => 'Année universitaire',

            'intervenant-code'               => 'Code intervenant',
            'intervenant-code-rh'            => 'Code RH',
            'intervenant-nom'                => 'Intervenant',
            'intervenant-date-naissance'     => 'Date de naissance',
            'intervenant-statut-libelle'     => 'Statut intervenant',
            'intervenant-type-code'          => 'Type d\'intervenant (Code)',
            'intervenant-type-libelle'       => 'Type d\'intervenant',
            'intervenant-grade-code'         => 'Grade (Code)',
            'intervenant-grade-libelle'      => 'Grade',
            'intervenant-discipline-code'    => 'Discipline intervenant (Code)',
            'intervenant-discipline-libelle' => 'Discipline intervenant',
            'service-structure-aff-libelle'  => 'Structure d\'affectation',

            'service-structure-ens-libelle' => 'Structure d\'enseignement',
            'groupe-type-formation-libelle' => 'Groupe de type de formation',
            'type-formation-libelle'        => 'Type de formation',
            'etape-niveau'                  => 'Niveau',
            'etape-code'                    => 'Code formation',
            'etape-etablissement-libelle'   => 'Formation ou établissement',
            'element-code'                  => 'Code enseignement',
            'element-fonction-libelle'      => 'Enseignement ou fonction référentielle',
            'element-discipline-code'       => 'Discipline ens. (Code)',
            'element-discipline-libelle'    => 'Discipline ens.',
            'element-taux-fi'               => 'Taux FI',
            'element-taux-fc'               => 'Taux FC',
            'element-taux-fa'               => 'Taux FA',
            'commentaires'                  => 'Commentaires',
            'element-source-libelle'        => 'Source enseignement',

            'type-ressource-libelle'      => 'Enveloppe',
            'centre-couts-code'           => 'Centre de coûts ou EOTP (code)',
            'centre-couts-libelle'        => 'Centre de coûts ou EOTP (libellé)',
            'domaine-fonctionnel-code'    => 'Domaine fonctionnel (code)',
            'domaine-fonctionnel-libelle' => 'Domaine fonctionnel (libellé)',
            'etat'                        => 'État',
            'periode-libelle'             => 'Période de paiement',
            'date-mise-en-paiement'       => 'Date de mise en paiement',

            'heures-fi'          => 'FI',
            'heures-fa'          => 'FA',
            'heures-fc'          => 'FC',
            'heures-referentiel' => 'Référentiel',
            'heures-primes'      => 'Prime Rém. FC D714-60',
        ]);
        $csvModel->addLines($data);
        $csvModel->setFilename('budget_mises_en_paiement.csv');

        return $csvModel;
    }



    protected function getStructureElement (?Structure $structure): StructureElement
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $structureElement = new StructureElement('structure');
        $structureElement->init();
        $structureElement->setLabel('');
        if ($structure) {
            $structureElement->setValue($structure->getId());
        } else {
            $structureElement->setEmptyOption('Veuillez sélectionner une composante s\'il vous plaît...');
        }
        $structureElement->setAttribute('onchange', 'window.location.href="' . $this->url()->fromRoute('budget/engagements-liquidation') . '/"+this.value');

        return $structureElement;
    }
}