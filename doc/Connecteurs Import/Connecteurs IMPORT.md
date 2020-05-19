# Connecteurs Import de OSE

* [Harpège](Harpège/Connecteur.md)



Matrice des connecteurs

| Table                 | Apogée | FCA Manager | Harpège | Sifac | Calcul | Description | 
| --------------------- | ------ | ----------- | ------- | ----- | ------ | ----------- |
| AFFECTATION           |        |             |         |       |        | Affectation des utilisateurs à des rôles |				 
| AFFECTATION_RECHERCHE |        |             | Oui     |       |        | Affectations de recherche des intervenants |	 
| CENTRE_COUT 			|        |             |         | Oui   |        | Liste des centres de coûts |
| CENTRE_COUT_EP 		|        |             |         |       |        | Relation n <=> nentre les centres de coûts et les éléments pédagogiques |
| CENTRE_COUT_STRUCTURE |        |             |         | Oui   |        | Relation n <=> nentre les centres de coûts et les structures  |
| CHEMIN_PEDAGOGIQUE 	| Oui    | Oui         |         |       |        | Relation n <=> n entre les étapes et les éléments pédagogiques |
| CORPS 				|        |             | Oui     |       |        | Liste des corps |
| DEPARTEMENT 			|        |             | Oui     |       |        | Liste des départements |
| DISCIPLINE 			|        |             |         |       |        | Liste des disciplines (sections CNU, disc. second degré, etc) |
| DOMAINE_FONCTIONNEL 	|        |             |         | Oui   |        | Liste des domaines fonctionnels |
| EFFECTIFS 			| Oui    |             |         |       |        | Effectifs étudiants par élément péagogique |
| EFFECTIFS_ETAPE 		|        |             |         |       |        | Effectifs étudiants par étape |
| ELEMENT_PEDAGOGIQUE 	| Oui    | Oui         |         |       |        | Liste des éléments pédagogiques |
| ELEMENT_TAUX_REGIMES 	|        |             |         |       |        | Taux FI/FC/FA par élément pédagogique |
| EMPLOYEUR 			|        |             |         |       |        | Liste des employeurs |
| ETABLISSEMENT 		| Oui    |             |         |       |        | Liste des établissements |
| ETAPE 				| Oui    | Oui         |         |       |        | Liste des étapes |
| GRADE 				|        |             | Oui     |       |        | Liste des grades |
| GROUPE_TYPE_FORMATION | Oui    |             |         |       |        | Liste des groupes de types de formation (License, Master, DU, etc.) |
| INTERVENANT 			|        |             | Oui     |       |        | Intervenants |
| LIEN 					| Oui    |             |         |       |        | Liens entre deux noeuds |
| NOEUD 				| Oui    |             |         |       |        | Noeuds formant les arbres d'une formation, situés entre les étapes et les éléments pédagogiques |
| PAYS 				    |        |             | Oui     |       |        | Liste des pays |
| SCENARIO_LIEN 	    | Oui    |             |         |       |        | Paramétrage des liens |
| SCENARIO_NOEUD 	    | Oui    |             |         |       |        | Paramétrage des noeuds |
| SERVICE 			    |        |             |         |       |        | Lignes de service intervenant (enseignement) |
| SERVICE_REFERENTIEL   |        |             |         |       |        | Lignes de service intervenant (référentiel) |
| STRUCTURE 			|        |             | Oui     |       |        | Liste des structures |
| TYPE_FORMATION 		| Oui    |             |         |       |        | Liste des types de formation |
| TYPE_INTERVENTION_EP 	|        |             |         |       | Oui    | Relation n <=> n spécifiant quels types d'intervention sont pertinents par élément pédagogique |
| TYPE_MODULATEUR_EP 	|        |             |         |       | Oui    | Relation n <=> n spécifiant quels types de modulateurs sont pertinents par élément pédagogique |
| VOIRIE 				|        |             | Oui     |       |        | Liste des voiries |
| VOLUME_HORAIRE 		|        |             |         |       |        | Volumes horaires (grain fin de la saisie de service : heures d'enseignement) |
| VOLUME_HORAIRE_CHARGE |        |             |         |       |        | Table non exploitée : à ignorer |
| VOLUME_HORAIRE_ENS 	|        |             |         |       |        | Charge d'enseingement |
| VOLUME_HORAIRE_REF    |        |             |         |       |        | Volumes horaires (grain fin de la saisie de service : heures de référentiel) |