<?php

namespace OffreFormation\Controller;

use Application\Controller\AbstractController;
use OffreFormation\Form\Traits\DisciplineFormAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use OffreFormation\Entity\Db\Discipline;
use OffreFormation\Service\Traits\DisciplineServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;


/**
 * Description of DisciplineController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DisciplineController extends AbstractController
{
    use DisciplineServiceAwareTrait;
    use SourceServiceAwareTrait;
    use DisciplineFormAwareTrait;
    use ParametresServiceAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Discipline::class,
        ]);
        $qb = $this->getServiceDiscipline()->initQuery()[0];
        $this->getServiceDiscipline()->join($this->getServiceSource(), $qb, 'source', true);

        $disciplines = $this->getServiceDiscipline()->getList($qb);

        $libellesCodesCorresp = [];
        for ($i = 1; $i <= 4; $i++) {
            $lcc = $this->getServiceParametres()->get('discipline_codes_corresp_' . $i . '_libelle');
            if ($lcc) {
                $libellesCodesCorresp[$i] = $lcc;
            }
        }

        return compact('disciplines', 'libellesCodesCorresp');
    }

    public function indexJsonAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Discipline::class,
        ]);

        $qb = $this->getServiceDiscipline()->initQuery()[0];

        $this->getServiceDiscipline()->join(
            $this->getServiceSource(),
            $qb,
            'source',
            true
        );

        $disciplines = $this->getServiceDiscipline()->getList($qb);
        $disciplinesListe = [];
        $result = [];

        //Libelle colonnes
        $libellesCodesCorresp = [];
        for ($i = 1; $i <= 4; $i++) {
            $lcc = $this->getServiceParametres()->get('discipline_codes_corresp_' . $i . '_libelle');
            if ($lcc) {
                $libellesCodesCorresp[$i] = $lcc;
            }
        }


        //Données tableau disciplines
        foreach ($disciplines as $discipline) {
            $disciplinesListe[] = [
                'id' => $discipline->getId(),
                'sourceCode' => $discipline->getSourceCode(),
                'libelleCourt' => $discipline->getLibelleCourt(),
                'libelleLong' => $discipline->getLibelleLong(),
                'codesCorresp1' => $discipline->getCodesCorresp1(),
                'codesCorresp2' => $discipline->getCodesCorresp2(),
                'codesCorresp3' => $discipline->getCodesCorresp3(),
                'codesCorresp4' => $discipline->getCodesCorresp4(),
                'source' => (string)$discipline->getSource(),
                'sourceImportable' => $discipline->getSource()->getImportable(),
            ];
        }

        $sourceImportable = $discipline->getSource()->getImportable();

        $result[] = [
            $libellesCodesCorresp,
            $disciplinesListe,
            $sourceImportable
        ];

        return new AxiosModel($result);
    }


    public function voirAction()
    {
        return [];
    }


    public function saisirAction()
    {
        $discipline = $this->getEvent()->getParam('discipline');
        $errors = [];

        $form = $this->getFormDiscipline();
        if (empty($discipline)) {
            $title = 'Création d\'une nouvelle discipline';
            $discipline = $this->getServiceDiscipline()->newEntity();
            $form->setObject($discipline);
        } else {
            $title = 'Édition de la discipine';
            $form->bind($discipline);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceDiscipline()->save($discipline);
                    $form->get('id')->setValue($discipline->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $errors[] = $this->translate($e);
                }
            }
        }

        return compact('form', 'title', 'errors');
    }

    public function modifierJsonAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return new AxiosModel([
                'success' => false,
                'errors' => ['Méthode HTTP non autorisée.'],
            ]);
        }

        $id = $request->getPost('id');

        try {
            if ($id) {
                // Modification
                $discipline = $this->getServiceDiscipline()->get($id);

                if (!$discipline) {
                    return new AxiosModel([
                        'success' => false,
                        'errors' => ['Discipline introuvable.'],
                    ]);
                }
            } else {
                // Création
                $discipline = $this->getServiceDiscipline()->newEntity();
            }

            $form = $this->getFormDiscipline();

            $form->bind($discipline);
            $form->setData($request->getPost());

            if (!$form->isValid()) {
                return new AxiosModel([
                    'success' => false,
                    'errors' => $form->getMessages(),
                ]);
            }

            $this->getServiceDiscipline()->save($discipline);

            return new AxiosModel([
                'success' => true,
                'id' => $discipline->getId(),
            ]);
        } catch (\Exception $e) {
            return new AxiosModel([
                'success' => false,
                'errors' => [$this->translate($e)],
            ]);
        }
    }

    public function supprimerAction()
    {
        $discipline = $this->getEvent()->getParam('discipline');

        $title = "Suppression de la discipline";

        $form = $this->makeFormSupprimer(function () use ($discipline) {
            $this->getServiceDiscipline()->delete($discipline);
        });

        return compact('discipline', 'title', 'form');
    }


    public function supprimerJsonAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return new AxiosModel([
                'success' => false,
                'errors' => ['Méthode HTTP non autorisée.'],
            ]);
        }

        $id = $request->getPost('id');

        if (!$id) {
            return new AxiosModel([
                'success' => false,
                'errors' => ['Identifiant de discipline manquant.'],
            ]);
        }

        try {
            $discipline = $this->getServiceDiscipline()->get($id);

            if (!$discipline) {
                return new AxiosModel([
                    'success' => false,
                    'errors' => ['Discipline introuvable.'],
                ]);
            }

            $this->getServiceDiscipline()->delete($discipline);

            return new AxiosModel([
                'success' => true,
            ]);


        } catch (\Exception $e) {
            return new AxiosModel([
                'success' => false,
                'errors' => [$this->translate($e)],
            ]);
        }
    }
}