**ATTENTION** : cette documentation est valable à partir de la version 15 de OSE. Pour les versions antérieures, les vues sources sont plutôt à récupérer dans
l'ancienne version de la documentation : [Ancienne documentation](https://git.unicaen.fr/open-source/OSE/-/tree/b14/connecteurs)

# Mécanisme

L'import de données se passe au niveau de la base de données.

Toutes les données exploitées par l'application doivent être enregistrées dans sa base de données. Ceci implique donc d'y
importer les données et de les synchroniser à intervalle régulier afin de les maintenir à jour.

Le module Import de OSE se charge se faire le lien avec la base de données du logiciel. Pour cela, il génère des vues
différentielles. Ces dernières permettent de déterminer les différences entre les données fournies par les vues sources et les
tables correspondantes. Il gérère également des procédures de mise à jour qui vont se baser sur les vues différentielles pour
mettre à jour OSE. En cas de modification d'une vue source, il faut donc procéder à la mise à jour des vues et procédures
d'import.

Une interface d'administration (menu Administration / Import) vous permettra de :

- visualiser le différentiel des données entre vos sources et OSE, et de mettre à jour l'application au cas par cas
- gérer vos différentes sources de données
- visualiser (page Branchement) les tables synchronisables de OSE et leurs spécifications (utile de nouveaux connecteurs une
  l'adaptation de ceux existants)
- mettre à jour les vues et les procédures d'import

Un certain nombre de tables de la base de données sont importables, c'est-à-dire qu'elles possèdent deux colonnes, SOURCE_ID
et SOURCE_CODE qui permettent respectivement de

* Savoir quelle est la source de la donnée (SOURCE_ID faisant référence à SOURCE.ID, donc l'identifiant de la source).
* avoir, ligne par ligne, un identifiant **unique** qui permet d'établir la correspondance avec la donnée d'origine.

Les données à importer devront être listées dans des vues écrites au format attendu par OSE. Le format est disponible
directement dans l'application, page Administration / Synchronisation / Tableau de bord principal. Généralement, les vues
accèdent aux données en passant par des DBLinks, mais rien n'empêche de faire autrement, par exemple en créant des tables tampon remplies par des scripts et qui seront exploitées par les vues sources.
C'est d'ailleurs comme cela que fonctionne le connecteur [Actul](Actul/Connecteur.md).

OSE permet de faire quatre opérations d'importation :

* INSERT : pour ajouter une donnée
* UPDATE : mise à jour d'une donnée
* DELETE : pour supprimer une donnée (sachant que dans OSE les données ne sont pas réellement supprimées, mais historisées
  avec un horodatage)
* UNDELETE : pour restaurer une donnée qui avait été supprimée

# Informations sur l'architecture des connecteurs.

Un connecteur est composé d'au moins deux parties :

1. la requête qui va permettre de remonter les données selon le schéma OSE Cette requête peut s'apppuyer le cas échéant sur
   d'autres dispositifs (vues matérialisées, scripts de peuplement de tables, etc)
   Pour les identifiants, si le champ fait référence à une autre table, alors on pourra fournir une valeur qui permettra de
   retrouver ensuite l'identifiant OSE. On utilisera donc pour convention z_ + nom du champ pour signaler que la données
   transmise n'est pas celle attendue. Cette requête peut éventuellement être intégrée directement dans la vue source.
1. la vue source, qui fournit à OSE les données nécessaires. Si des champs z_* existent, il convient alors de les exploiter
   pour retrouver l'identifiant OSE correspondant à leur contenu. Cela se fait le plus souvent à l'aide d'une jointure. Par
   exemple, on donne U10 dans z_structure_id. Or U10 est le code de la composante IAE. Donc on retourne structure.id si
   structure.source_code = z_structure_id à l'aide d'une jointure à gauche.

# Sources de données

OSE peut accepter plusieurs sources de données.

Deux d'entres elles, particulières, sont présentes par défaut dans l'application.

La source "OSE" n'est pas vraiment une source de données. Une donnée dont la source est OSE signifie qu'elle a été saisie
directement dans l'application. Cette donnée ne pourra donc pas être récupérée ailleurs et elle ne peut pas être mise à jour
de manière automatique.

La source "Calcul", quand à elle, est utilisée pour des données qui n'ont pas été saisies dans l'application, mais calculées
et intégrées dans l'application à partir d'autres données déjà présentes dans OSE en utilisant le mécanisme d'import de
données. Par exemple, dans le tableau ci-dessous nous avons la table TYPE_INTERVENTION_EP. Cette table permet de lister tous
les types d'intervention (CM, TD)
pour lesquels il est possible de saisir des heures d'enseignement pour chaque élément pédagogique. Cette information peut être
déduite d'une autre, à savoir la présence de charges d'enseignement. Donc nous prenons les charges et s'il il y en a par
exemple en CM sur un élément de Maths, alors TYPE_INTERVENTION_EP sera peuplé avec une ligne ("CM", "Maths") ce qui aura pour
conséquence de pouvoir saisir des heures de service en CM sur cet élément de maths.

Les autres sources dont vous aurez la nécessité seront créées au besoin par vos soins.

Il est possible, pour une même table, d'intégrer des données provenant de plusieurs sources. Par exemple à Caen l'offre de
formation est à la fois

* importée d'Apogée
* importée de FCA Manager
* saisie directement dans OSE

Chaque élément aura donc comme source soit Apogée, soit FCA Manager, soit OSE.

Il n'existe en revanche qu'une seule vue source par table. Il vous revient donc de fusionner les données de ces différentes
sources au moyen d'un "UNION ALL". Par ailleurs, OSE ne gère pas le dédoublonnage des données sources. A vous, donc, de gérer
cet aspect.
**Pour chaque vue source, la colonne SOURCE_CODE doit avoir des valeurs uniques**.

# Connecteurs Import de OSE

Il existe déjà plusieurs connecteurs. Ceux-ci vous sont fournis à titre d'exemple. Ils devront être adaptés aux spécifités de
votre système d'information. Les connecteurs ne seront pas "écrasés" ou impactés par les futures mises à jour de OSE.

En voici la liste :

* [Harpège](Harpège/Connecteur.md) pour les données RH et diverses
* [Octopus](Octopus/Connecteur.md)  (spécifique à Caen) pour les données RH et diverses
* [Mangue](Mangue/Connecteur.md) pour les intervenants
* [Siham](Siham/Connecteur.md) pour les données RH et diverses
* [Sifac](Sifac/Connecteur.md) pour les données comptables
* [Apogée](Apogée/Connecteur.md) pour l'offre de formation
* [FCA Manager](FCA-Manager/Connecteur.md) également pour l'offre de formation
* [Calcul](Calcul/Connecteur.md) pour des données essentiellement liées à l'offre de formation

Et voici ci-dessous la matrice des connecteurs qui reprend, table par table, ce qu'ils peuvent fournir. Les tables sont
présentées dans l'ordre où il faut les traiter.

<table cellpadding="1" style="padding:1px">
  <tr>
    <th>Table</th>
    <th>Apogée</th>
    <th>ActUL</th>
    <th>FCA Manager</th> 
    <th>Harpège</th>
    <th>Siham</th>
    <th>Octopus</th>
    <th>Mangue</th>
    <th>Sifac</th>
    <th>Calcul</th>
    <th>Description</th>
  </tr>


  <tr>
    <th colspan="50">Nomenclatures diverses</th>
  </tr>
  <tr>
    <td>PAYS</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td>Oui</td> <!-- Harpège -->
    <td>Oui</td> <!-- Siham -->
    <td>Oui</td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des pays</td> <!-- Description -->
  </tr>
  <tr>
    <td>DEPARTEMENT</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td>Oui</td> <!-- Harpège -->
    <td>Oui</td> <!-- Siham -->
    <td>Oui</td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des départements</td> <!-- Description -->
  </tr>
  <tr>
    <td>VOIRIE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td>Oui</td> <!-- Harpège -->
    <td>Oui</td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des voiries</td> <!-- Description -->
  </tr>
  <tr>
    <td>ETABLISSEMENT</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des établissements</td> <!-- Description -->
  </tr>
  <tr>
    <td>STRUCTURE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td>Oui</td> <!-- Harpège -->
    <td>Oui</td> <!-- Siham -->
    <td>Oui</td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des structures</td> <!-- Description -->
  </tr>
  <tr>
    <td>DISCIPLINE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des disciplines (sections CNU, disc. second degré, etc)</td> <!-- Description -->
  </tr>


  <tr>
    <th colspan="50">Données "RH"</th>
  </tr>
  <tr>
    <td>AFFECTATION</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td>Oui</td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Affectation des utilisateurs à des rôles</td> <!-- Description -->
  </tr>
  <tr>
    <td>EMPLOYEUR</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des employeurs. Un mécanisme spécifique vous offre la possibilité de bénéficier dans OSE de la liste de tous les employeurs issue de la base SIRENE</td> <!-- Description -->
  </tr>
  <tr>
    <td>CORPS</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td>Oui</td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td>Oui</td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des corps</td> <!-- Description -->
  </tr>
  <tr>
    <td>GRADE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td>Oui</td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td>Oui</td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des grades</td> <!-- Description -->
  </tr>
  <tr>
    <td>INTERVENANT</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td>Oui</td> <!-- Harpège -->
    <td>Oui</td> <!-- Siham -->
    <td>Oui</td> <!-- Octopus -->
    <td>Oui</td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Intervenants (vacataires et permanents)</td> <!-- Description -->
  </tr>
  <tr>
    <td>AFFECTATION_RECHERCHE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td>Oui</td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td>Oui</td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Affectations de recherche des intervenants</td> <!-- Description -->
  </tr>


  <tr>
    <th colspan="50">Données comptables</th>
  </tr>
  <tr>
    <td>DOMAINE_FONCTIONNEL</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td>Oui</td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des domaines fonctionnels</td> <!-- Description -->
  </tr>
  <tr>
    <td>CENTRE_COUT</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td>Oui</td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des centres de coûts</td> <!-- Description -->
  </tr>
  <tr>
    <td>CENTRE_COUT_EP</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Relation n <=> n entre les centres de coûts et les éléments pédagogiques</td> <!-- Description -->
  </tr>
  <tr>
    <td>CENTRE_COUT_STRUCTURE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td>Oui</td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Relation n <=> n entre les centres de coûts et les structures</td> <!-- Description -->
  </tr>


  <tr>
    <th colspan="50">Données décrivant l'offre de formation</th>
  </tr>
  <tr>
    <td>GROUPE_TYPE_FORMATION</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des groupes de types de formation (License, Master, DU, etc.)</td> <!-- Description -->
  </tr>
  <tr>
    <td>TYPE_FORMATION</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des types de formation</td> <!-- Description -->
  </tr>
  <tr>
    <td>ETAPE</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td>Oui</td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des étapes</td> <!-- Description -->
  </tr>
  <tr>
    <td>ELEMENT_PEDAGOGIQUE</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td>Oui</td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liste des éléments pédagogiques</td> <!-- Description -->
  </tr>
  <tr>
    <td>CHEMIN_PEDAGOGIQUE</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td>Oui</td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Relation n <=> n entre les étapes et les éléments pédagogiques</td> <!-- Description -->
  </tr>
  <tr>
    <td>VOLUME_HORAIRE_ENS</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td>Oui</td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td>Oui</td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Charge d'enseingement</td> <!-- Description -->
  </tr>  
  <tr>
    <td>EFFECTIFS</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Effectifs étudiants par élément péagogique</td> <!-- Description -->
  </tr>
  <tr>
    <td>EFFECTIFS_ETAPE</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Effectifs étudiants par étape</td> <!-- Description -->
  </tr>
  <tr>
    <td>ELEMENT_TAUX_REGIMES</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Taux FI/FC/FA par élément pédagogique</td> <!-- Description -->
  </tr>
  <tr>
    <td>NOEUD</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Noeuds formant les arbres d'une formation, situés entre les étapes et les éléments pédagogiques. Nécessaire uniquement pour le module Charges.</td> <!-- Description -->
  </tr>
    <tr>
    <td>LIEN</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Liens entre deux noeuds. Nécessaire uniquement pour le module Charges.</td> <!-- Description -->
  </tr>  
  <tr>
    <td>SCENARIO_NOEUD</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Paramétrage des noeuds. Utile uniquement pour le module Charges.</td> <!-- Description -->
  </tr>
    <tr>
    <td>SCENARIO_NOEUD_EFFECTIF</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Paramétrage des effectifs des noeuds d'étapes. Utile uniquement pour le module Charges.</td> <!-- Description -->
  </tr>
  <tr>
    <td>SCENARIO_LIEN</td> <!-- Table -->
    <td>Oui</td> <!-- Apogée -->
    <td>Oui</td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Paramétrage des liens. Utile uniquement pour le module Charges.</td> <!-- Description -->
  </tr>


  <tr>
    <th colspan="50">Données liées aux services d'enseignement</th>
  </tr>
  <tr>
    <td>SERVICE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Lignes de service intervenant (enseignement)</td> <!-- Description -->
  </tr>
  <tr>
    <td>SERVICE_REFERENTIEL</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Lignes de service intervenant (référentiel)</td> <!-- Description -->
  </tr>
  <tr>
    <td>VOLUME_HORAIRE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Volumes horaires (grain fin de la saisie de service : heures d'enseignement)</td> <!-- Description -->
  </tr>
  <tr>
    <td>VOLUME_HORAIRE_REF</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Volumes horaires (grain fin de la saisie de service : heures de référentiel)</td> <!-- Description -->
  </tr>  
  <tr>
    <td>TYPE_INTERVENTION_EP</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td>Oui</td> <!-- Calcul -->
    <td>Relation n <=> n spécifiant quels types d'intervention sont pertinents par élément pédagogique</td> <!-- Description -->
  </tr>
  <tr>
    <td>TYPE_MODULATEUR_EP</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td>Oui</td> <!-- Calcul -->
    <td>Relation n <=> n spécifiant quels types de modulateurs sont pertinents par élément pédagogique</td> <!-- Description -->
  </tr>
  <tr>
    <td>VOLUME_HORAIRE_CHARGE</td> <!-- Table -->
    <td></td> <!-- Apogée -->
    <td></td> <!-- ActUL -->
    <td></td> <!-- FCA Manager -->
    <td></td> <!-- Harpège -->
    <td></td> <!-- Siham -->
    <td></td> <!-- Octopus -->
    <td></td><!-- Mangue -->
    <td></td> <!-- Sifac -->
    <td></td> <!-- Calcul -->
    <td>Table non exploitée : à ignorer</td> <!-- Description -->
  </tr>
</table>

# Créer vos propres connecteurs

Si vous ne trouvez pas votre bonheur dans les connecteurs déjà fournis, il vous est possible de développer vos propres
connecteurs. Vous pourrez suivre pour cela la [procédure de création](creer.md).

# Filtres et traitements

La synchronisation peut se faire de plusieurs manières :

1. soit par le biais du CRON (commande `./bin/ose synchronisation <nom_du_job>`)
1. soit dans la page Administration/Synchronisation/Différentiel
1. soit en base de données (`unicaen_import.synchroniser('<NOM_TABLE>');`);

Pour configurer les filtres et traitements antérieurs/postérieurs, allez sur OSE dans Administration/Synchronisation/Tables.

## Filtres automatiques

Les filtres permettent de ne synchroniser qu'une partie des données sans prendre en compte le reste du différentiel. Ils sont
appliqués sur les deux premières manières présentées ci-dessus.

### Création d'un filtre

Un filtre correspond à une partie de requête SQL portant sur une vue différentielle.

Par exemple, pour ne lister que le différentiel des étapes dont l'année est supérieure à 2019, on fait :

```sql
SELECT * FROM v_diff_etape WHERE annee_id > 2019;
```

Le filtre est ici `WHERE annee_id > 2019`.

### Utilisation pour contrôler la synchronisation des intervenants.

La synchronisation des intervenants est alimentée par la [src_intervenant](Générique/SRC_INTERVENANT.md).

Il est nécessaire de mettre en place un filtre portant sur la table INTERVENANT afin de ne pas historiser de fiches si :
 - elles ont été créées localement
 - si des données ont déjà été saisies dessus

Voici le filtre, à copier/coller :

```sql
WHERE (import_action <> 'delete' OR (
  source_id <> ose_divers.get_ose_source_id 
  AND (NOT exists(SELECT intervenant_id FROM intervenant_dossier WHERE histo_destruction IS NULL AND intervenant_id = v_diff_intervenant.id))
  AND (NOT exists(SELECT intervenant_id FROM piece_jointe WHERE histo_destruction IS NULL AND intervenant_id = v_diff_intervenant.id))
  AND (NOT exists(SELECT intervenant_id FROM service WHERE histo_destruction IS NULL AND intervenant_id = v_diff_intervenant.id))
))
```

### Filtres de synchronisation pour les structures

Certaines données relatives aux structures doivent être mises à jour automatiquement.

Deux tables nécessitent des post-traitements après leur mise à jour :
STRUCTURE et CENTRE_COUT_STRUCTURE.

Le filtre suivant est donc à insérer en tant que post traitement pour ces deux tables :

```plsql
OSE_DIVERS.UPDATE_STRUCTURE_IDS();
```


### Utilisation pour contrôler la synchronisation de l'offre de formation

Prenons l'exemple d'une offre de formation importée à la fois d'Apogée et de FCA Manager. Dans OSE ainsi que dans FCA Manager,
l'offre de formation est annualisée. Ce n'est pas la cas dans Apogée. Il peut donc être utile de "figer" l'offre de formation
issue d'Apogée afin que les changements d'offre ne soient pas systématiquement répercutés sur l'année en cours. Pour se faire,
on peut définit une année minimale d'import de l'offre de formation et toutes les données venant d'Apogée ne seront pas
synchronisées si les données sont antérieures.

#### Définition de l'année minimale d'import des données d'offre de formation

Le paramètre général "Année minimale d'import pour l'ODF" vous permet de définir à partir de quelle année
votre offre de formation se synchronisera. Pour les années précédentes, même si votre connecteur fournit des données,
rien ne sera modifié dans OSE, sous réserve que vous ayez mis en place les filtres listés ci-dessous.

#### Mise en place des filtres

Reste à exploiter ce paramètre pour filtrer les données import ne venant pas de FCA Manager. Bien entendu, les filtres
ci-dessous vous sont fournis à titre indicatif. Il vous revient de les adapter à vos besoins.

- Etapes, éléments, effectifs et noeuds (tables ETAPE, ELEMENT_PEDAGOGIQUE, EFFECTIFS et NOEUD)

Ces tables sont annualisées. On synchronise toutes les données issues de FCA Manager et les autres données si leur année n'est
pas inférieure à l'année d'import ou à l'année minimale d'import de l'ODF.

```sql
JOIN source ON source.code = 'FCAManager'
JOIN parametre amio ON amio.nom = 'annee_minimale_import_odf'
WHERE 
  (annee_id >= to_number(amio.valeur) OR source_id = source.id)
```

- Effectifs/étapes (table EFFECTIFS_ETAPE)

On synchronise toutes les données issues de FCA Manager et les autres données si leur année n'est
pas inférieure à l'année d'import ou à l'année minimale d'import de l'ODF.

```sql
JOIN source ON source.code = 'FCAManager'
JOIN etape e ON e.id = v_diff_effectifs_etape.etape_id
JOIN parametre amio ON amio.nom = 'annee_minimale_import_odf'
WHERE
  (e.annee_id >= to_number(amio.valeur) OR v_diff_effectifs_etape.source_id = source.id)
```

- Chemins pédagogiques (table CHEMIN_PEDAGOGIQUE)

Cette table n'est pas annualisée. En revanche on peut se baser sur l'année de l'élément pédagogique dont elle dépend. 
Le principe des filtre reste le même que ci-dessus.

```sql
JOIN source ON source.code = 'FCAManager'
JOIN parametre amio ON amio.nom = 'annee_minimale_import_odf'
JOIN element_pedagogique ep ON ep.id = element_pedagogique_id
WHERE 
    (ep.annee_id >= to_number(amio.valeur) OR v_diff_chemin_pedagogique.source_id = source.id)
```


- Volumes horaires d'enseignement (table VOLUME_HORAIRE_ENS)

Cette table n'est pas annualisée. En revanche on peut se baser sur l'année de l'élément pédagogique dont elle dépend.
Le principe des filtre reste le même que ci-dessus.

```sql
JOIN source ON source.code = 'FCAManager'
JOIN parametre amio ON amio.nom = 'annee_minimale_import_odf'
JOIN element_pedagogique ep ON ep.id = element_pedagogique_id
WHERE 
    (ep.annee_id >= to_number(amio.valeur) OR v_diff_volume_horaire_ens.source_id = source.id)
```


- Liens et scénarios par liens (tables LIEN et SCENARIO_LIEN)

Ces tables ne sont pas annualisées. Dans ce cas, on se base sur le `SOURCE_CODE` dont la valeur débute par l'année
universitaire (exemple : `2018_{}MD22ENTB_M.DM240`).

```sql
JOIN source ON source.code = 'FCAManager'
JOIN parametre amio ON amio.nom = 'annee_minimale_import_odf'
WHERE 
  (SUBSTR(source_code,0,4) >= amio.valeur OR source_id = source.id)
```


- Taux de mixité FI/FA/FC (ELEMENT_TAUX_REGIMES)

Dans OSE, on peut affecter das taux de mixité FI/FA/FC aux éléments pédagogiques. Ceci peut se faire directement dans le
logiciel. On peut aussi, comme ce qui se fait à Caen, pré-calculer ces taux sur la base des effectifs de l'année précédente, puis actuelle selon la période.

Les nouveaux taux peuvent être importés en même temps que toutes les autres données issues de l'offre de formation.
Les modifications de taux ne sont pas faites automatiquement pour éviter de perturnber d'éventuelles mises en paiement,
elles sont réalisées le 15 décembre.

```sql
JOIN parametre amio ON amio.nom = 'annee_minimale_import_odf'
JOIN element_pedagogique ep ON ep.id = element_pedagogique_id
WHERE 
  (IMPORT_ACTION IN ('insert','undelete') AND ep.annee_id >= to_number(amio.valeur))
```

Et voici la commande permettant d'actualiser les taux de régime, que nous exécutons le 15 décembre :

```sh
./bin/ose maj-taux-mixite
```

Elle fait un `UPDATE` ou un `DELETE` de tous les taux qui ont évolué pour les années supérieures ou égales à l'année d'import courante.
Ceci présente l'avantage de ne rien modifier des années antérieures à l'année d'import courante.
Dans la [procédure d'installation](../../INSTALL.md), il est mentionné de lancer cette commande au moyen du `cron` tous les 15 décembre.



## Traitement automatiques

Préalablement ou après une opération de synchronisation, il est parfois nécessaire de déclencher des opérations (mise à jour
d'une vue matérialisée, etc.).

Pour le préalable, il y a les traitements antérieurs. Pour l'après, il y a les traitements postérieurs.

Attention : ces traitements ne se déclenchent que si la syncro se fait par le biais de la
commande `./bin/ose synchronisation <nom_du_job>`. Cela concerne donc le job CRON de synvchronisation.

Voici deux traitements qu'il est fortement conseillé de déclencher automatiquement :

- Table INTERVENANT : mise à jour de la vue matérialisée MV_INTERVENANT avant la synchro :

```sql
UNICAEN_IMPORT.REFRESH_MV('MV_INTERVENANT');
```

- Table NOEUD : mise à jour de tableaux de bord après la synchro :

```sql
OSE_CHARGENS.MAJ_CACHE;
```