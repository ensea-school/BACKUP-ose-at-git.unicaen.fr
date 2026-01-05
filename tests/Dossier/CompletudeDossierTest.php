<?php

namespace Dossier;

use Dossier\Tbl\Process\DossierProcess;
use Intervenant\Entity\Db\Statut;
use tests\OseTestCase;

/**
 * Class de test pour le calcul de la complétude des données personnelles
 */
class CompletudeDossierTest extends OseTestCase
{
    private DossierProcess $service;



    protected function setUp(): void
    {
        $this->service = new DossierProcess();

    }



    /**
     * Test de la complétude identité pour un cas particulier : au moins
     * un champs obligatoire n'est pas fourni
     *
     * @return void
     */
    public function testCompletudeIdentiteRetourneFalseSiChampManquant(): void
    {
        $dossier = [
            'CIVILITE_ID' => '1',
            'NOM_USUEL'   => 'Dupont',
            'PRENOM'      => 'Jean',
            // 'DATE_NAISSANCE' est manquant
        ];
        $this->assertFalse($this->service->getCalculateurCompletude()->completudeIdentite($dossier));
    }



    /**
     * Test de la complétude identité pour un cas particulier : tous les champs
     * obligatoires sont fournis
     *
     * @return void
     */
    public function testCompletudeIdentiteRetourneTrueSiTousChampsPresents(): void
    {
        $dossier = [
            'CIVILITE_ID'    => '1',
            'NOM_USUEL'      => 'Dupont',
            'PRENOM'         => 'Jean',
            'DATE_NAISSANCE' => '1990-01-01',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeIdentite($dossier));
    }



    /**
     * Test de la complétude identité pour un cas particulier : au moins
     * un champs obligatoire est vide
     *
     * @return void
     */
    public function testCompletudeIdentiteRetourneFalseSiChampVide(): void
    {
        $dossier = [
            'CIVILITE_ID'    => '',
            'NOM_USUEL'      => 'Dupont',
            'PRENOM'         => 'Jean',
            'DATE_NAISSANCE' => '1990-01-01',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeIdentite($dossier));
    }



    /**
     * Test de la complétude identité complémentaire pour un cas particulier : les données d'identité
     * complémentaire ne sont pas demandé donc on considéra que c'est complet
     *
     * @return void
     */
    public function testCompletudeIdentiteComplementaireRetourneTrueQuandIdentiteComplementaireDesactivee(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP' => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    /**
     * Test de la complétude identité complémentaire pour un cas particulier : au moins un champs obligatoire n'est
     * pas fourni
     *
     * @return void
     */
    public function testCompletudeIdentiteComplementaireRetourneFalseQuandChampsObligatoireManquant(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP' => true,
            'PAYS_NAISSANCE_ID'     => 1,
            'PAYS_NATIONALITE_ID'   => '',
            // vide
            'COMMUNE_NAISSANCE'     => 'Paris',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    public function testCompletudeIdentiteComplementaireRetourneFalseSiFranceSansDepartement(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP'    => true,
            'PAYS_NAISSANCE_ID'        => 1,
            'PAYS_NATIONALITE_ID'      => 1,
            'COMMUNE_NAISSANCE'        => 'Paris',
            'LIBELLE_PAYS_NAISSANCE'   => 'France',
            'DEPARTEMENT_NAISSANCE_ID' => '',
            // Manquant
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    /**
     * Test de la complétude identité complémentaire pour un cas particulier : pays naissance france et
     * département bien fourni
     *
     * @return void
     */
    public function testCompletudeIdentiteComplementaireRetourneTrueQuandFranceAvecDepartement(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP'    => true,
            'PAYS_NAISSANCE_ID'        => 1,
            'PAYS_NATIONALITE_ID'      => 1,
            'COMMUNE_NAISSANCE'        => 'Paris',
            'LIBELLE_PAYS_NAISSANCE'   => 'France',
            'DEPARTEMENT_NAISSANCE_ID' => 75,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    /**
     * Test de la complétude identité complémentaire pour un cas particulier : pays naissance différent de france et
     * département non fourni
     *
     * @return void
     */
    public function testCompletudeIdentiteComplementaireRetourneTrueQuandAutreQueFranceSansDepartement(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP'    => true,
            'PAYS_NAISSANCE_ID'        => 1,
            'PAYS_NATIONALITE_ID'      => 1,
            'COMMUNE_NAISSANCE'        => 'Paris',
            'LIBELLE_PAYS_NAISSANCE'   => 'Belgique',
            'DEPARTEMENT_NAISSANCE_ID' => '',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    /**
     * Test de la complétude identité complémentaire pour un cas particulier : la situation matrimoniqle est
     * obligatoire, mais aucune valeur n'est fournie, sans date de situation matrimoniale
     *
     * @return void
     */
    public function testCompletudeIdentiteComplementaireRetourneFalseSituationMatriSansDateNiCode(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP'          => true,
            'DOSSIER_SITUATION_MATRIMONIALE' => true,
            // Absence de SITUATION_MATRIMONIALE_CODE et de SITUATION_MATRIMONIALE_DATE
            'PAYS_NAISSANCE_ID'              => 1,
            'PAYS_NATIONALITE_ID'            => 1,
            'COMMUNE_NAISSANCE'              => 'Paris',
            'LIBELLE_PAYS_NAISSANCE'         => 'Espagne',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    /**
     * Test de la complétude identité complémentaire pour un cas particulier : situation matrimoniale obligatoire,
     * célibataire et pas de date de situation matrimoniale fournie
     *
     * @return void
     */
    public function testCompletudeIdentiteComplementaireRetourneTrueSiCelibataireSansDate(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP'          => true,
            'DOSSIER_SITUATION_MATRIMONIALE' => true,
            'SITUATION_MATRIMONIALE_CODE'    => Statut::CODE_SITUATION_MATRIMONIALE_CELIBATAIRE,
            // Célibataire
            'PAYS_NAISSANCE_ID'              => 1,
            'PAYS_NATIONALITE_ID'            => 1,
            'COMMUNE_NAISSANCE'              => 'Lyon',
            'LIBELLE_PAYS_NAISSANCE'         => 'Espagne',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    /**
     * Test de la complétude identité complémentaire pour un cas particulier : Situation matrimoniale
     * obligatoire, autre que célibataire et date de la situation non fournie
     *
     * @return void
     */
    public function testCompletudeIdentiteComplementaireRetourneFalseSiAutreQueCelibataireSansDate(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP'          => true,
            'DOSSIER_SITUATION_MATRIMONIALE' => true,
            'SITUATION_MATRIMONIALE_CODE'    => 'DIV',
            'PAYS_NAISSANCE_ID'              => 1,
            'PAYS_NATIONALITE_ID'            => 1,
            'COMMUNE_NAISSANCE'              => 'Lyon',
            'LIBELLE_PAYS_NAISSANCE'         => 'Espagne',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    /**
     * Test de la complétude identité complémentaire pour un cas particulier : tout est fourni
     * et la situation matrimoniqle est obligatoire
     *
     * @return void
     */
    public function testCompletudeIdentiteComplementaireRetourneTrueSiSituationMatriAvecDate(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP'          => true,
            'DOSSIER_SITUATION_MATRIMONIALE' => true,
            'SITUATION_MATRIMONIALE_CODE'    => 'DIV',
            'SITUATION_MATRIMONIALE_DATE'    => '1990-01-01',
            'PAYS_NAISSANCE_ID'              => 1,
            'PAYS_NATIONALITE_ID'            => 1,
            'COMMUNE_NAISSANCE'              => 'Lyon',
            'LIBELLE_PAYS_NAISSANCE'         => 'Espagne',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeIdentiteComplementaire($dossier));
    }



    /**
     * Test de la complétude contact pour un cas particulier : le bloc contact n'est pas demandé
     * donc on considéra qu'il est complet
     *
     * @return void
     */
    public function testCompletudeContactRetourneTrueQuandContactDesactive(): void
    {
        $dossier = [
            'DOSSIER_CONTACT' => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeContact($dossier));
    }



    /**
     * Test de la complétude contact pour un cas particulier : email perso obligatoire, mais
     * non renseigné
     *
     * @return void
     */
    public function testCompletudeContactRetourneFalseSiEmailPersoRequisMaisVide(): void
    {
        $dossier = [
            'DOSSIER_CONTACT'     => true,
            'DOSSIER_EMAIL_PERSO' => true,
            'EMAIL_PERSO'         => '',
            'EMAIL_PRO'           => '',
            'DOSSIER_TEL_PERSO'   => false,
            'TEL_PERSO'           => '',
            'TEL_PRO'             => '0123456789',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeContact($dossier));
    }



    /**
     * Test de la complétude contact pour un cas particulier : aucun email n'est renseigné
     * donc l'email perso devient automatiquement obligatoire
     *
     * @return void
     */
    public function testCompletudeContactRetourneFalseSiAucunEmailRenseigne(): void
    {
        $dossier = [
            'DOSSIER_CONTACT'     => true,
            'DOSSIER_EMAIL_PERSO' => false,
            'EMAIL_PERSO'         => '',
            'EMAIL_PRO'           => '',
            'DOSSIER_TEL_PERSO'   => false,
            'TEL_PERSO'           => '0611223344',
            'TEL_PRO'             => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeContact($dossier));
    }



    /**
     * Test de la complétude contact pour un cas particulier : Tel perso obligatoire,
     * mais non renseigné
     *
     * @return void
     */
    public function testCompletudeContactRetourneFalseSiTelPersoRequisMaisVide(): void
    {
        $dossier = [
            'DOSSIER_CONTACT'     => true,
            'DOSSIER_TEL_PERSO'   => true,
            'TEL_PERSO'           => '',
            'TEL_PRO'             => '',
            'DOSSIER_EMAIL_PERSO' => false,
            'EMAIL_PERSO'         => 'test@email.com',
            'EMAIL_PRO'           => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeContact($dossier));
    }



    /**
     * Test de la complétude contact pour un cas particulier : aucun téléphone n'est renseigné
     * le téléphone perso devient donc obligatoire
     *
     * @return void
     */
    public function testCompletudeContactRetourneFalseSiAucunTelephoneRenseigne(): void
    {
        $dossier = [
            'DOSSIER_CONTACT'     => true,
            'TEL_PERSO'           => '',
            'TEL_PRO'             => '',
            'DOSSIER_TEL_PERSO'   => false,
            'EMAIL_PERSO'         => 'test@email.com',
            'EMAIL_PRO'           => '',
            'DOSSIER_EMAIL_PERSO' => false,
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeContact($dossier));
    }



    /**
     * Test de la complétude contact pour un cas particulier : tous les champs sont fourni,
     * email perso et tel pro obligatoire
     *
     * @return void
     */
    public function testCompletudeContactRetourneTrueAvecEmailEtTelephoneRenseignes(): void
    {
        $dossier = [
            'DOSSIER_CONTACT'     => true,
            'DOSSIER_EMAIL_PERSO' => true,
            'EMAIL_PERSO'         => 'test@email.com',
            'EMAIL_PRO'           => '',
            'DOSSIER_TEL_PERSO'   => true,
            'TEL_PERSO'           => '0611223344',
            'TEL_PRO'             => '0611223344',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeContact($dossier));
    }



    /**
     * Test de la complétude contact pour un cas particulier : tous les champs sont fourni,
     * email perso et tel pro obligatoire
     *
     * @return void
     */
    public function testCompletudeContactRetourneTrueAvecInfosNonRenseignes(): void
    {
        $dossier = [
            'DOSSIER_CONTACT'     => true,
            'DOSSIER_EMAIL_PERSO' => true,
            'EMAIL_PERSO'         => 'test@email.com',
            'EMAIL_PRO'           => '',
            'DOSSIER_TEL_PERSO'   => true,
            'TEL_PERSO'           => '0611223344',
            'TEL_PRO'             => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeContact($dossier));
    }



    /**
     * Test de la complétude adresse pour un cas particulier : adresse demandée,
     * donc on considère qu'il est complet
     *
     * @return void
     */
    public function testCompletudeAdresseRetourneTrueQuandAdresseDesactivee(): void
    {
        $dossier = [
            'DOSSIER_ADRESSE' => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeAdresse($dossier));
    }



    /**
     * Test de la complétude adresse pour un cas particulier : aucune infos d'adresse
     * n'est fourni hormis la commune et le code postal
     *
     * @return void
     */
    public function testCompletudeAdresseRetourneFalseSiAucuneInfoAdresse(): void
    {
        $dossier = [
            'DOSSIER_ADRESSE'     => true,
            'ADRESSE_PRECISIONS'  => '',
            'ADRESSE_LIEU_DIT'    => '',
            'ADRESSE_VOIE'        => '',
            'ADRESSE_NUMERO'      => '',
            'ADRESSE_COMMUNE'     => 'Paris',
            'ADRESSE_CODE_POSTAL' => '75000',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeAdresse($dossier));
    }



    /**
     * Test de la complétude adresse pour un cas particulier : adresse fournie
     * sans commune ni code postal
     *
     * @return void
     */
    public function testCompletudeAdresseRetourneFalseSiCommuneOuCodePostalManquant(): void
    {
        $dossier = [
            'DOSSIER_ADRESSE'     => true,
            'ADRESSE_PRECISIONS'  => '',
            'ADRESSE_LIEU_DIT'    => '',
            'ADRESSE_VOIE'        => 'Rue de Rivoli',
            'ADRESSE_NUMERO'      => '1',
            'ADRESSE_COMMUNE'     => '',
            'ADRESSE_CODE_POSTAL' => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeAdresse($dossier));
    }



    /**
     * Test de la complétude adresse pour un cas particulier : adresse fournie complète avec
     * commune et code postal
     *
     * @return void
     */
    public function testCompletudeAdresseRetourneTrueSiAdresseVoieEtNumeroRenseignesEtCommuneEtCodePostalOk()
    {
        $dossier = [
            'DOSSIER_ADRESSE'     => true,
            'ADRESSE_PRECISIONS'  => '',
            'ADRESSE_LIEU_DIT'    => '',
            'ADRESSE_VOIE'        => 'Rue de la Paix',
            'ADRESSE_NUMERO'      => '10',
            'ADRESSE_COMMUNE'     => 'Paris',
            'ADRESSE_CODE_POSTAL' => '75002',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeAdresse($dossier));
    }



    /**
     * Test de la complétude adresse pour un cas particulier : adresse fourni avec un
     * lieu dit et commune et code postal
     *
     * @return void
     */
    public function testCompletudeAdresseRetourneTrueSiLieuDitEstRenseigneEtCommuneEtCodePostalOk(): void
    {
        $dossier = [
            'DOSSIER_ADRESSE'     => true,
            'ADRESSE_PRECISIONS'  => '',
            'ADRESSE_LIEU_DIT'    => 'Ferme du bois',
            'ADRESSE_VOIE'        => '',
            'ADRESSE_NUMERO'      => '',
            'ADRESSE_COMMUNE'     => 'Lyon',
            'ADRESSE_CODE_POSTAL' => '69000',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeAdresse($dossier));
    }



    /**
     * Test de la complétude adresse pour un cas particulier : adresse fourni avec une
     * précision et commune et code postal
     *
     * @return void
     */
    public function testCompletudeAdresseRetourneTrueSiPrecisionsRenseigneesEtCommuneEtCodePostalOk()
    {
        $dossier = [
            'DOSSIER_ADRESSE'     => true,
            'ADRESSE_PRECISIONS'  => 'Appartement 12B',
            'ADRESSE_LIEU_DIT'    => '',
            'ADRESSE_VOIE'        => '',
            'ADRESSE_NUMERO'      => '',
            'ADRESSE_COMMUNE'     => 'Nice',
            'ADRESSE_CODE_POSTAL' => '06000',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeAdresse($dossier));
    }



    /**
     * Test de la complétude adresse pour un cas particulier : adresse fourni avec une
     * précision et commune et code postal
     *
     * @return void
     */
    public function testCompletudeAdresseRetourneFalseSiVoieRenseigneeMaisPasNumeroVoieEtCommuneEtCodePostalOk(): void
    {
        $dossier = [
            'DOSSIER_ADRESSE'     => true,
            'ADRESSE_PRECISIONS'  => 'Appartement 12B',
            'ADRESSE_LIEU_DIT'    => '',
            'ADRESSE_VOIE'        => 'RUE',
            'ADRESSE_NUMERO'      => '',
            'ADRESSE_COMMUNE'     => 'Nice',
            'ADRESSE_CODE_POSTAL' => '06000',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeAdresse($dossier));
    }



    /**
     * Test de la complétude banque pour un cas particulier : informations bancaires non
     * demandées donc on considère que c'est complet
     *
     * @return void
     */
    public function testCompletudeBanqueRetourneTrueQuandBanqueDesactivee()
    {
        $dossier = [
            'DOSSIER_BANQUE' => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeBanque($dossier));
    }



    /**
     * Test de la complétude banque pour un cas particulier : Iban non fourni
     *
     * @return void
     */
    public function testCompletudeBanqueRetourneFalseSiIBANVide(): void
    {
        $dossier = [
            'DOSSIER_BANQUE' => true,
            'IBAN'           => '',
            'BIC'            => 'ABCDFRPPXXX',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeBanque($dossier));
    }



    /**
     * Test de la complétude banque pour un cas particulier : Bic non fourni
     *
     * @return void
     */
    public function testCompletudeBanqueRetourneFalseSiBICVide(): void
    {
        $dossier = [
            'DOSSIER_BANQUE' => true,
            'IBAN'           => 'FR7630004000031234567890143',
            'BIC'            => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeBanque($dossier));
    }



    /**
     * Test de la complétude banque pour un cas particulier : Iban et Bic non fourni
     *
     * @return void
     */
    public function testCompletudeBanqueRetourneFalseSiIBANEtBICVides(): void
    {
        $dossier = [
            'DOSSIER_BANQUE' => true,
            'IBAN'           => '',
            'BIC'            => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeBanque($dossier));
    }



    /**
     * Test de la complétude banque pour un cas particulier : Bic et Iban fourni
     *
     * @return void
     */
    public function testCompletudeBanqueRetourneTrueSiIBANEtBICRenseignes()
    {
        $dossier = [
            'DOSSIER_BANQUE' => true,
            'IBAN'           => 'FR7630004000031234567890143',
            'BIC'            => 'ABCDFRPPXXX',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeBanque($dossier));
    }



    /**
     * Test de la complétude INSEE pour un cas particulier : INSEE non demandé
     * donc on considère que c'est complet
     *
     * @return void
     */
    public function testCompletudeInseeRetourneTrueQuandInseeDesactive(): void
    {
        $dossier = [
            'DOSSIER_INSEE' => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeInsee($dossier));
    }



    /**
     * Test de la complétude INSEE pour un cas particulier : INSEE non fourni
     *
     * @return void
     */
    public function testCompletudeInseeRetourneFalseQuandNumeroInseeVide(): void
    {
        $dossier = [
            'DOSSIER_INSEE' => true,
            'NUMERO_INSEE'  => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeInsee($dossier));
    }



    /**
     * Test de la complétude INSEE pour un cas particulier : INSEE fourni
     *
     * @return void
     */
    public function testCompletudeInseeRetourneTrueQuandNumeroInseeRenseigne(): void
    {
        $dossier = [
            'DOSSIER_INSEE' => true,
            'NUMERO_INSEE'  => '1234567890123',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeInsee($dossier));
    }



    /**
     * Test de la complétude Employeur pour un cas particulier : employeur non demandé
     * donc on considéra que c'est complet
     *
     * @return void
     */
    public function testCompletudeEmployeurRetourneTrueQuandEmployeurDesactive(): void
    {
        $dossier = [
            'DOSSIER_EMPLOYEUR' => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeEmployeur($dossier));
    }



    /**
     * Test de la complétude Employeur pour un cas particulier : employeur demandé mais
     * facultatif
     *
     * @return void
     */
    public function testCompletudeEmployeurRetourneTrueQuandEmployeurFacultatifEtNonRenseigne(): void
    {
        $dossier = [
            'DOSSIER_EMPLOYEUR'            => true,
            'DOSSIER_EMPLOYEUR_FACULTATIF' => true,
            'EMPLOYEUR_ID'                 => '',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeEmployeur($dossier));
    }



    /**
     * Test de la complétude Employeur pour un cas particulier : employeur obligatoire,
     * mais non renseigné
     * @return void
     */
    public function testCompletudeEmployeurRetourneFalseQuandEmployeurObligatoireEtNonRenseigne(): void
    {
        $dossier = [
            'DOSSIER_EMPLOYEUR'            => true,
            'DOSSIER_EMPLOYEUR_FACULTATIF' => false,
            'EMPLOYEUR_ID'                 => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeEmployeur($dossier));
    }



    /**
     * Test de la complétude Employeur pour un cas particulier : employeur demandé et fourni
     *
     * @return void
     */
    public function testCompletudeEmployeurRetourneTrueQuandEmployeurObligatoireEtRenseigne(): void
    {
        $dossier = [
            'DOSSIER_EMPLOYEUR'            => true,
            'DOSSIER_EMPLOYEUR_FACULTATIF' => false,
            'EMPLOYEUR_ID'                 => 123,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeEmployeur($dossier));
    }



    /**
     * Test de la complétude champs autres pour un cas particulier : champs autres désactivés
     * donc on considéra que c'est complet
     *
     * @return void
     */
    public function testCompletudeChampsAutresRetourneTrueQuandChampDesactive(): void
    {
        $numero  = 1;
        $dossier = [
            'DOSSIER_AUTRE_1' => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeChampsAutres($dossier, $numero));
    }



    /**
     * Test de la complétude champs autres pour un cas particulier : champs autres demandé
     * mais facultatif
     *
     * @return void
     */
    public function testCompletudeChampsAutresRetourneTrueQuandChampFacultatifEtVide(): void
    {
        $numero  = 2;
        $dossier = [
            'DOSSIER_AUTRE_2'             => true,
            'DOSSIER_AUTRE_2_OBLIGATOIRE' => false,
            'AUTRE_2'                     => '',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeChampsAutres($dossier, $numero));
    }



    /**
     * Test de la complétude champs autres pour un cas particulier : champs autres obligatoires
     * mais non fourni
     *
     * @return void
     */
    public function testCompletudeChampsAutresRetourneFalseQuandChampObligatoireEtVide(): void
    {
        $numero  = 3;
        $dossier = [
            'DOSSIER_AUTRE_3'             => true,
            'DOSSIER_AUTRE_3_OBLIGATOIRE' => true,
            'AUTRE_3'                     => '',
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeChampsAutres($dossier, $numero));
    }



    /**
     * Test de la complétude champs autres pour un cas particulier : champs autres obligatoire
     * et fourni
     *
     * @return void
     */
    public function testCompletudeChampsAutresRetourneTrueQuandChampObligatoireEtRenseigne(): void
    {
        $numero  = 4;
        $dossier = [
            'DOSSIER_AUTRE_4'             => true,
            'DOSSIER_AUTRE_4_OBLIGATOIRE' => true,
            'AUTRE_4'                     => 'Quelque chose',
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeChampsAutres($dossier, $numero));
    }



    /**
     * Test du calcul complétude avant recrutement un cas particulier : Tout est demandé et tout
     * est complet
     *
     * @return void
     */
    public function testCompletudeAvantRecrutementRetourneTrueSiToutEstComplet(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP' => 1,
            'DOSSIER_CONTACT'       => 1,
            'DOSSIER_ADRESSE'       => 1,
            'DOSSIER_INSEE'         => 1,
            'DOSSIER_BANQUE'        => 1,
            'DOSSIER_EMPLOYEUR'     => 1,
            'DOSSIER_AUTRE_1'       => 1,
            'DOSSIER_AUTRE_2'       => 1,
            'DOSSIER_AUTRE_3'       => 1,
            'DOSSIER_AUTRE_4'       => 1,
            'DOSSIER_AUTRE_5'       => 1,
        ];

        $destination = [
            'COMPLETUDE_STATUT'        => true,
            'COMPLETUDE_IDENTITE'      => true,
            'COMPLETUDE_IDENTITE_COMP' => true,
            'COMPLETUDE_CONTACT'       => true,
            'COMPLETUDE_ADRESSE'       => true,
            'COMPLETUDE_INSEE'         => true,
            'COMPLETUDE_BANQUE'        => true,
            'COMPLETUDE_EMPLOYEUR'     => true,
            'COMPLETUDE_AUTRE_1'       => true,
            'COMPLETUDE_AUTRE_2'       => true,
            'COMPLETUDE_AUTRE_3'       => true,
            'COMPLETUDE_AUTRE_4'       => true,
            'COMPLETUDE_AUTRE_5'       => true,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeAvantRecrutement($dossier, $destination));
    }



    /**
     * Test du calcul complétude avant recrutement un cas particulier : les informations d'identité
     * ne sont pas complète
     *
     * @return void
     */
    public function testCompletudeAvantRecrutementRetourneFalseSiIdentiteIncomplet(): void
    {
        $dossier     = ['DOSSIER_CONTACT' => 1];
        $destination = ['COMPLETUDE_IDENTITE' => false,
                        'COMPLETUDE_CONTACT'  => true];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeAvantRecrutement($dossier, $destination));
    }



    /**
     * Test du calcul complétude avant recrutement un cas particulier : tous les blocs
     * demandés ne sont pas complets
     *
     * @return void
     */
    public function testCompletudeAvantRecrutementRetourneFalseSiUnBlocDemandeEstIncomplet()
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP' => 1,
            'DOSSIER_CONTACT'       => 1,
        ];

        $destination = [
            'COMPLETUDE_STATUT'        => true,
            'COMPLETUDE_IDENTITE'      => true,
            'COMPLETUDE_IDENTITE_COMP' => true,
            'COMPLETUDE_CONTACT'       => false,
            // bloc activé mais incomplet
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completucompletudeAvantRecrutement($dossier, $destination));
    }



    /**
     * Test du calcul complétude avant recrutement un cas particulier : des blocs sont complets,
     * mais non demandés
     *
     * @return void
     */
    public function testCompletudeAvantRecrutementIgnoreBlocNonDemande(): void
    {
        $dossier = [
            'DOSSIER_CONTACT' => 0,
            'DOSSIER_AUTRE_1' => 0,
        ];

        $destination = [
            'COMPLETUDE_STATUT'   => true,
            'COMPLETUDE_IDENTITE' => true,
            'COMPLETUDE_CONTACT'  => false,
            'COMPLETUDE_AUTRE_1'  => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeAvantRecrutement($dossier, $destination));
    }



    /**
     * Test du calcul complétude après recrutement un cas particulier : tous les
     * blocs demandés sont complets
     *
     * @return void
     */
    public function testCompletudeApresRecrutementRetourneTrueSiToutEstComplet(): void
    {
        $dossier = [
            'DOSSIER_IDENTITE_COMP' => 2,
            'DOSSIER_CONTACT'       => 2,
            'DOSSIER_ADRESSE'       => 2,
            'DOSSIER_INSEE'         => 2,
            'DOSSIER_BANQUE'        => 2,
            'DOSSIER_EMPLOYEUR'     => 2,
            'DOSSIER_AUTRE_1'       => 2,
            'DOSSIER_AUTRE_2'       => 2,
            'DOSSIER_AUTRE_3'       => 2,
            'DOSSIER_AUTRE_4'       => 2,
            'DOSSIER_AUTRE_5'       => 2,
        ];

        $destination = [
            'COMPLETUDE_STATUT'        => true,
            'COMPLETUDE_IDENTITE_COMP' => true,
            'COMPLETUDE_CONTACT'       => true,
            'COMPLETUDE_ADRESSE'       => true,
            'COMPLETUDE_INSEE'         => true,
            'COMPLETUDE_BANQUE'        => true,
            'COMPLETUDE_EMPLOYEUR'     => true,
            'COMPLETUDE_AUTRE_1'       => true,
            'COMPLETUDE_AUTRE_2'       => true,
            'COMPLETUDE_AUTRE_3'       => true,
            'COMPLETUDE_AUTRE_4'       => true,
            'COMPLETUDE_AUTRE_5'       => true,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeApresRecrutement($dossier, $destination));
    }



    /**
     * Test du calcul complétude après recrutement un cas particulier : un des blocs
     * demandés n'est pas complet
     *
     * @return void
     */
    public function testCompletudeApresRecrutementRetourneFalseSiUnBlocDemandeEstIncomplet(): void
    {
        $dossier = [
            'DOSSIER_CONTACT' => 2,
        ];

        $destination = [
            'COMPLETUDE_CONTACT' => false,
        ];

        $this->assertFalse($this->service->getCalculateurCompletude()->completudeApresRecrutement($dossier, $destination));
    }



    /**
     * Test du calcul complétude après recrutement un cas particulier : tous les
     * blocs demandés sont complets
     *
     * @return void
     */
    public function testCompletudeApresRecrutementRetourneTrueSiTousLesBlocsSontDesactives(): void
    {
        $dossier = [
            'DOSSIER_CONTACT' => 0,
            'DOSSIER_ADRESSE' => 1,
            'DOSSIER_BANQUE'  => 0,
        ];

        $destination = [
            'COMPLETUDE_CONTACT' => false,
            'COMPLETUDE_ADRESSE' => false,
            'COMPLETUDE_BANQUE'  => false,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeApresRecrutement($dossier, $destination));
    }



    /**
     * Test du calcul complétude après recrutement un cas particulier : ne vérifie que les
     * blocs demandés aprés le recrutement
     *
     * @return void
     */
    public function testCompletudeApresRecrutementIgnoreLesBlocsDemandesAvantRecrutementOuDesactive(): void
    {
        $dossier = [
            'DOSSIER_CONTACT' => 1,
            'DOSSIER_INSEE'   => 0,
            'DOSSIER_BANQUE'  => 2,
        ];

        $destination = [
            'COMPLETUDE_CONTACT' => false,
            'COMPLETUDE_INSEE'   => false,
            'COMPLETUDE_BANQUE'  => true,
        ];

        $this->assertTrue($this->service->getCalculateurCompletude()->completudeApresRecrutement($dossier, $destination));
    }

}
