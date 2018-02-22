-- Quelques requêtes por faire quelques légers contrôles sur les vues fournies pour OSE

-- Trouver les élements qui font référence à des étapes inconnues dans ose_etape (= formations diplômantes émanents de FCA Manager)
select * from OSE_ELEMENT_PEDAGOGIQUE WHERE Z_ETAPE_ID not in (select SOURCE_CODE from OSE_ETAPE);

-- Trouver les volumes horaires reliés à un élément inexistant
select * from OSE_VOLUME_HORAIRE_ENS WHERE Z_ELEMENT_PEDAGOGIQUE_ID NOT IN (select SOURCE_CODE FROM OSE_ELEMENT_PEDAGOGIQUE);

-- Trouver les éléments pédagogiques dans le chemin pédagogique qui sont absent de la vue étape fournie (= formations diplômantes émanents de FCA Manager)
select * from OSE_CHEMIN_PEDAGOGIQUE WHERE Z_ETAPE_ID NOT IN (select SOURCE_CODE FROM OSE_ETAPE);
