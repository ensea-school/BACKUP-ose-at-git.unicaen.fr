--
-- Volumes horaires avec validations éventuelles
--
select str.libelle_court str_val, comp.libelle_court comp, v.id validation_id, v.histo_modificateur_id, 
    v.histo_modification date_validation, vh.id vh_id, vh.type_volume_horaire_id, vh.service_id, vh.heures
from volume_horaire vh
join service s on vh.service_id = s.id and 1 = OSE_DIVERS.COMPRISE_ENTRE(s.HISTO_CREATION,s.HISTO_DESTRUCTION)
join element_pedagogique ep on s.element_pedagogique_id = ep.id and 1 = OSE_DIVERS.COMPRISE_ENTRE(ep.HISTO_CREATION,ep.HISTO_DESTRUCTION)
left join validation_vol_horaire vvh on vvh.volume_horaire_id = vh.id
left join validation v on v.id = vvh.validation_id and 1 = OSE_DIVERS.COMPRISE_ENTRE(v.HISTO_CREATION,v.HISTO_DESTRUCTION)
left join structure str on str.id = v.structure_id 
join structure comp on comp.id = ep.structure_id 
where 1 = OSE_DIVERS.COMPRISE_ENTRE(vh.HISTO_CREATION, vh.HISTO_DESTRUCTION)
and s.intervenant_id = 1620
and vh.type_volume_horaire_id = 2
order by v.histo_modification
;


/**
 * Création d'un nouveau type de validation.
 */

INSERT INTO TYPE_VALIDATION  (
    ID,
    CODE,
    LIBELLE,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID
  )
  VALUES  (
    type_validation_id_seq.nextval,
    'CLOTURE_REALISE',
    'Clôture de la saisie des enseignements réalisés',
    ose_parametre.get_ose_user(),
    ose_parametre.get_ose_user()
  );
  