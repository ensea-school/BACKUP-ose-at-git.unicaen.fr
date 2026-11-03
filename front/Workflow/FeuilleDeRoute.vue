<template>
    <div class="feuille-de-route row">
        <div :class="pleineLargeur ? 'col-12' : 'col-md-9'">
            <div class="fdr-entete">
                <div class="fdr-entete-titre">
                    <span class="fdr-entete-icone"><i class="fas fa-route"></i></span>
                    <div class="fdr-entete-textes">
                        <h2>Feuille de route</h2>
                        <div v-if="nbEtapes" class="fdr-progression"
                             :title="nbFaites + ' étape(s) terminée(s) sur ' + nbEtapes">
                            <div class="fdr-progression-barre">
                                <div class="fdr-progression-remplie" :style="{ width: pourcFaites + '%' }"></div>
                            </div>
                            <span class="fdr-progression-texte">
                                <strong>{{ nbFaites }}</strong> étape{{ nbFaites > 1 ? 's' : '' }}
                                terminée{{ nbFaites > 1 ? 's' : '' }} sur {{ nbEtapes }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="fdr-entete-actions">
                    <span v-if="derniereMaj" class="fdr-maj" title="Heure du dernier calcul de la feuille de route">
                        <i class="far fa-clock"></i> Mise à jour à {{ derniereMaj }}
                    </span>
                    <button type="button" class="btn btn-light btn-sm fdr-refresh"
                            :disabled="actualisation"
                            title="Recalculer la feuille de route à partir des dernières données saisies"
                            @click="refresh">
                        <i class="fas fa-arrows-rotate" :class="{ 'fa-spin': actualisation }"></i>
                        {{ actualisation ? 'Actualisation…' : 'Actualiser' }}
                    </button>
                </div>
            </div>

            <div v-if="feuilleDeRoute && feuilleDeRoute.length" class="fdr-liste">

                <div v-for="etape in feuilleDeRoute" :key="etape.code"
                     class="fdr-etape"
                     :class="{
                         'fdr-etape-bloquee': !etape.atteignable,
                         'fdr-etape-courante': etape.courante,
                         'fdr-etape-faite': etape.realisationPourc == 100,
                     }"
                     :title="formatWhyNonAtteignable(etape)">

                    <div class="fdr-etape-header">
                        <!-- Numéro d'étape -->
                        <span class="fdr-numero">
                            <i v-if="!etape.atteignable && etape.realisationPourc < 100" class="fas fa-lock"></i>
                            <template v-else>{{ etape.numero }}</template>
                        </span>

                        <!-- Libellé et lien éventuel -->
                        <div class="fdr-titre">
                            <a v-if="etape.url && etape.navigable" class="fdr-libelle fdr-lien"
                               title="Cliquez sur ce lien pour accéder à la page correspondant à cette étape"
                               :href="etape.url">{{ etape.libelle }}</a>
                            <span v-else class="fdr-libelle">{{ etape.libelle }}</span>

                            <span v-if="!etape.atteignable && etape.whyNonAtteignable.length" class="fdr-why">
                                <i class="fas fa-circle-info"></i>
                                {{ etape.whyNonAtteignable.join(' · ') }}
                            </span>
                        </div>

                        <!-- Indicateurs -->
                        <div class="fdr-badges">
                            <span v-if="etape.courante" class="badge badge-warning fdr-badge"
                                  title="Prochaine étape à réaliser">
                                <i class="fas fa-location-dot"></i> Étape en cours
                            </span>

                            <span v-if="etape.realisationPourc == 100" class="badge badge-success fdr-badge" title="Fait">
                                <i class="fas fa-check"></i> Fait
                            </span>
                            <span v-else-if="etape.realisationPourc > 0" class="badge badge-warning fdr-badge"
                                  title="En cours">
                                <i class="fas fa-hourglass-half"></i> {{ etape.realisationPourc }} %
                            </span>
                            <span v-else-if="etape.atteignable" class="badge badge-primary fdr-badge" title="À faire">
                                <i class="fas fa-pen"></i> À faire
                            </span>
                            <span v-else class="badge badge-secondary fdr-badge" title="Non accessible pour le moment">
                                <i class="fas fa-lock"></i> Bloquée
                            </span>

                            <a v-if="etapeHasStructures(etape)" href="#" class="fdr-action"
                               :title="isDepliee(etape) ? 'Masquer le détail par composante' : 'Afficher le détail par composante'"
                               @click.prevent="toggle(etape)">
                                <i class="fas fa-sitemap"></i>
                                <i class="fas fa-chevron-down fdr-chevron" :class="{ 'fdr-chevron-ferme': !isDepliee(etape) }"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Détail par composante -->
                    <div v-if="etapeHasStructures(etape) && isDepliee(etape)" class="fdr-etape-body">
                        <div class="fdr-structures-titre">Détail par composante</div>
                        <div v-for="etapeStructure in etape.structures" class="fdr-structure"
                             :class="{ 'fdr-structure-bloquee': !etapeStructure.atteignable }"
                             :title="formatWhyNonAtteignable(etapeStructure)">
                            <i class="fas fa-building fdr-structure-icone"></i>
                            <div class="fdr-structure-libelle">
                                {{ etapeStructure.libelle }}
                                <span v-if="!etapeStructure.atteignable && etapeStructure.whyNonAtteignable?.length"
                                      class="fdr-why">
                                    <i class="fas fa-circle-info"></i>
                                    {{ etapeStructure.whyNonAtteignable.join(' · ') }}
                                </span>
                            </div>

                            <span v-if="etapeStructure.realisationPourc == 100" class="badge badge-success fdr-badge">
                                <i class="fas fa-check"></i> Fait
                            </span>
                            <span v-else-if="etapeStructure.realisationPourc > 0" class="badge badge-warning fdr-badge">
                                <i class="fas fa-hourglass-half"></i> {{ etapeStructure.realisationPourc }} %
                            </span>
                            <span v-else-if="etapeStructure.atteignable" class="badge badge-primary fdr-badge">
                                <i class="fas fa-pen"></i> À faire
                            </span>
                            <span v-else class="badge badge-secondary fdr-badge">
                                <i class="fas fa-lock"></i> Bloquée
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="alert alert-info">
                La feuille de route ne comporte aucune étape
            </div>
        </div>
    </div>
</template>
<script setup>

import {computed, defineProps, onMounted, ref} from "vue";

const props = defineProps({
    intervenant: {type: Number},
    // Affichage sur toute la largeur disponible (ex. : page d'accueil)
    pleineLargeur: {type: Boolean, default: false},
});

const feuilleDeRoute = ref([]);

const nbEtapes = computed(() => feuilleDeRoute.value?.length || 0);
const nbFaites = computed(() => (feuilleDeRoute.value || []).filter(e => e.realisationPourc == 100).length);
const pourcFaites = computed(() => nbEtapes.value ? Math.round(nbFaites.value * 100 / nbEtapes.value) : 0);

const actualisation = ref(false);
const derniereMaj = ref(null);

const heureCourante = () => new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'});

// Étapes dont le détail par composante est déplié (par code d'étape)
const depliees = ref(new Set());

const isDepliee = (etape) => depliees.value.has(etape.code);

const toggle = (etape) => {
    const set = new Set(depliees.value);
    set.has(etape.code) ? set.delete(etape.code) : set.add(etape.code);
    depliees.value = set;
};

const load = () => {
    unicaenVue.axios.get(
        unicaenVue.url('workflow/feuille-de-route-data/:intervenant', {intervenant: props.intervenant})
    ).then(response => {
        feuilleDeRoute.value = response.data;
        derniereMaj.value = heureCourante();
    });
};

const refresh = () => {
    if (actualisation.value) {
        return;
    }
    actualisation.value = true;

    unicaenVue.axios.get(
        unicaenVue.url('workflow/feuille-de-route-refresh/:intervenant', {intervenant: props.intervenant})
    ).then(response => {
        if (response?.data) {
            feuilleDeRoute.value = response.data;
            derniereMaj.value = heureCourante();
        }
    }).finally(() => {
        actualisation.value = false;
    });
};

const formatWhyNonAtteignable = (etape) => {
    if (etape.atteignable || !etape.whyNonAtteignable || etape.whyNonAtteignable.length == 0) {
        return "";
    }

    let explication = "Non atteignable :";

    for (const index in etape.whyNonAtteignable) {
        explication += "\n    - " + etape.whyNonAtteignable[index];
    }

    return explication;
}

const etapeHasStructures = (etape) => {
    let count = 0;
    for (const i in etape.structures) {
        count++;
        if (count > 1){
            return true;
        }
    }
    return false;
}

onMounted(() => {
    load();
});

</script>
<style scoped>

/* Couleurs issues de la charte OSE (MDB5), cohérentes avec l'administration du workflow */

.fdr-entete {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: .5rem 1rem;
    margin-bottom: 1rem;
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--mdb-border-color, #e0e0e0);
}

.fdr-entete-titre {
    display: flex;
    align-items: center;
    gap: .75rem;
    min-width: 0;
}

.fdr-entete-icone {
    flex: 0 0 auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.3rem;
    height: 2.3rem;
    border-radius: .45rem;
    background: #dfe7f6;
    color: var(--mdb-primary, #3b71ca);
    font-size: 1rem;
}

.fdr-entete-textes {
    display: flex;
    flex-direction: column;
    gap: .25rem;
}

.fdr-entete h2 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 300;
    line-height: 1.2;
    color: var(--mdb-body-color, #4f4f4f);
}

.fdr-progression {
    display: flex;
    align-items: center;
    gap: .6rem;
}

.fdr-progression-barre {
    width: 9rem;
    height: .4rem;
    border-radius: 1rem;
    background: #e0e0e0;
    overflow: hidden;
}

.fdr-progression-remplie {
    height: 100%;
    border-radius: 1rem;
    background: var(--mdb-success, #14a44d);
    transition: width .4s ease;
}

.fdr-progression-texte {
    font-size: .8rem;
    color: #757575;
}

.fdr-progression-texte strong {
    color: var(--mdb-body-color, #4f4f4f);
}

.fdr-entete-actions {
    display: flex;
    align-items: center;
    gap: .75rem;
}

.fdr-maj {
    font-size: .8rem;
    color: #757575;
}

.fdr-maj i {
    margin-right: .15rem;
}

.fdr-refresh {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    color: var(--mdb-primary, #3b71ca);
    font-weight: 500;
}

.fdr-refresh:hover:not(:disabled) {
    background: #dfe7f6;
}

.fdr-refresh:disabled {
    opacity: .7;
    cursor: progress;
}

.fdr-liste {
    display: flex;
    flex-direction: column;
    gap: .35rem;
    margin-bottom: 1rem;
}

/* Carte d'étape */
.fdr-etape {
    background: #fff;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-left: 3px solid var(--mdb-primary, #3b71ca);
    border-radius: .5em;
    transition: box-shadow .15s ease, background-color .15s ease;
}

.fdr-etape:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
}

.fdr-etape:hover,
.fdr-etape:hover .fdr-etape-body {
    background-color: #e8e8e8;
}

.fdr-etape-faite {
    border-left-color: var(--mdb-success, #14a44d);
}

.fdr-etape-courante {
    border-left-color: var(--mdb-warning, #e4a11b);
    box-shadow: 0 0 0 2px rgba(228, 161, 27, .25);
}

.fdr-etape-bloquee {
    border-left-color: var(--mdb-secondary, #9fa6b2);
    background: #fafafa;
}

.fdr-etape-bloquee .fdr-libelle {
    color: #9e9e9e;
}

/* En-tête */
.fdr-etape-header {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .35rem .8rem;
}

.fdr-numero {
    flex: 0 0 auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.6rem;
    height: 1.6rem;
    border-radius: .35rem;
    background: var(--mdb-primary, #3b71ca);
    color: #fff;
    font-weight: 600;
    font-size: .75rem;
}

.fdr-etape-faite .fdr-numero {
    background: var(--mdb-success, #14a44d);
}

.fdr-etape-courante .fdr-numero {
    background: var(--mdb-warning, #e4a11b);
}

.fdr-etape-bloquee .fdr-numero {
    background: #e0e0e0;
    color: #757575;
    font-size: .65rem;
}

.fdr-titre {
    flex: 1 1 auto;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.fdr-libelle {
    font-family: "Open Sans", Helvetica, Arial, sans-serif;
    font-size: .875rem;
    color: var(--mdb-body-color, #4f4f4f);
}

.fdr-lien {
    color: var(--mdb-primary, #3b71ca);
    text-decoration: none;
}

.fdr-lien:hover {
    text-decoration: underline;
}

.fdr-why {
    font-size: .72rem;
    color: #757575;
    font-style: italic;
}

.fdr-why i {
    color: var(--mdb-secondary, #9fa6b2);
    margin-right: .15rem;
}

/* Badges : couleurs = classes MDB badge-*, ici seulement la forme */
.fdr-badges {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .35rem;
    justify-content: flex-end;
}

.fdr-badge {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    border-radius: 1rem;
    font-size: .7rem;
    font-weight: 500;
    white-space: nowrap;
}

/* Dans app.css, le .badge de Bootstrap (color: #fff) écrase la couleur de texte des .badge-* de MDB */
.fdr-badge.badge-primary   { color: #2c58a0; }
.fdr-badge.badge-secondary { color: #40464f; }
.fdr-badge.badge-success   { color: #0d6832; }
.fdr-badge.badge-warning   { color: #73510d; }

.fdr-action {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    height: 1.6rem;
    padding: 0 .4rem;
    font-size: .8rem;
    border-radius: .375rem;
    color: var(--mdb-primary, #3b71ca);
    text-decoration: none;
}

.fdr-action:hover {
    background: #dfe7f6;
}

.fdr-chevron {
    font-size: .75rem;
    transition: transform .2s ease;
}

.fdr-chevron-ferme {
    transform: rotate(-90deg);
}

/* Détail par composante */
.fdr-etape-body {
    padding: .5rem .9rem .6rem 3.05rem;
    background: #fafafa;
    border-top: 1px solid var(--mdb-border-color, #e0e0e0);
    border-bottom-left-radius: .5em;
    border-bottom-right-radius: .5em;
}

.fdr-structures-titre {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--mdb-secondary, #9fa6b2);
    margin-bottom: .35rem;
}

.fdr-structure {
    display: flex;
    align-items: center;
    gap: .55rem;
    padding: .25rem .55rem;
    font-size: .8rem;
    margin-bottom: .3rem;
    background: #fff;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: .4rem;
}

.fdr-structure-icone {
    color: var(--mdb-secondary, #9fa6b2);
}

.fdr-structure-libelle {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    color: var(--mdb-body-color, #4f4f4f);
}

.fdr-structure-bloquee {
    background: #f5f5f5;
}

.fdr-structure-bloquee .fdr-structure-libelle {
    color: #9e9e9e;
}

@media (max-width: 768px) {
    .fdr-etape-header {
        flex-wrap: wrap;
    }

    .fdr-badges {
        width: 100%;
        justify-content: flex-start;
        padding-left: 2.25rem;
    }

    .fdr-etape-body {
        padding-left: 1rem;
    }
}

</style>
