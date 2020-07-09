# Connecteur Calcul

Le connecteur Calcul est un peu spécifique en ce sens que les données ne viennent pas d'un logiciel tier, mais de OSE.
Il sont ensuite réinjectés dans l'application après transformation.
Il permet de synchroniser :
  * les types d'intervention pertinents pour chaque élément pédagogique
  * les types de modulateurs disponibles pour chaque élément pédagogique

Les vues qui vont sont fournies ci-dessous ne représentent qu'un exemple. Il vous revient de les adapter à votre contexte afin que vous
retrouviez dans OSE les données dont vous avez besoin.


Créez la vue [SRC_TYPE_INTERVENTION_EP](SRC_TYPE_INTERVENTION_EP.sql).

Créez la vue [SRC_TYPE_MODULATEUR_EP](SRC_TYPE_MODULATEUR_EP.sql). 
