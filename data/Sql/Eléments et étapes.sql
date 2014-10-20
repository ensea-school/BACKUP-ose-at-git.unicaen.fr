select
  ep.id id,
  ep.source_code code,
  ep.libelle libelle,
  e.id etape_id,
  cp.etape_id cp_id,
  e.libelle etape_libelle,
  CASE WHEN cp.etape_id <> ep.etape_id THEN 'SECONDAIRE' ELSE 'PRINCIPALE' END statut
from
  element_pedagogique ep
  LEFT JOIN chemin_pedagogique cp ON cp.element_pedagogique_id = ep.id AND cp.histo_destruction IS NULL
  JOIN etape e ON e.id = ep.etape_id OR e.id = cp.etape_id AND e.histo_destruction IS NULL
WHERE
  ep.histo_destruction IS NULL
  AND ep.source_code = 'DOCTORANTS1'
order by
  code, statut, etape_libelle;
  
select * from chemin_pedagogique cp where cp.element_pedagogique_id = 16773;
select * from element_pedagogique ep where id = 16773;
select * from etape where id = 1551;


select * from (
  select ep.id,
    rank() over (partition by ep.id order by cp.ordre) rang,
    count(*) over (partition by ep.id) nb_ch,
    ep.source_code, ep.libelle, e.libelle libelle_etape, e.niveau, gtf.libelle_court libelle_gtf, tf.libelle_long libelle_tf,
    ep.source_code || ' ' || ep.libelle|| ' ' || e.source_code || ' ' || e.libelle || ' ' || gtf.LIBELLE_COURT || ' ' || e.NIVEAU || ' ' || tf.LIBELLE_COURT etape_info
  from chemin_pedagogique cp
  JOIN element_pedagogique ep ON cp.element_pedagogique_id = ep.id  and  ep.HISTO_DESTRUCTEUR_ID is null and sysdate between ep.VALIDITE_DEBUT and nvl(ep.VALIDITE_FIN, sysdate)
  JOIN etape e ON cp.etape_id = e.id                                and   e.HISTO_DESTRUCTEUR_ID is null and sysdate between  e.VALIDITE_DEBUT and nvl( e.VALIDITE_FIN, sysdate)
  JOIN TYPE_FORMATION tf on e.TYPE_FORMATION_ID = tf.ID
  JOIN GROUPE_TYPE_FORMATION gtf on tf.GROUPE_ID = gtf.ID
  JOIN structure s ON ep.structure_id = s.id
  where ep.id = 16773
)
where rang = 1