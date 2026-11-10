<template>
    <Teleport to="body">
        <div class="dg-fond" tabindex="-1" ref="fond" @click.self="emit('fermer')" @keydown.esc="emit('fermer')">
            <div class="dg-modale" role="dialog" aria-modal="true" aria-labelledby="dg-titre">

                <!-- En-tête -->
                <div class="dg-entete">
                    <div>
                        <h4 id="dg-titre"><i class="fas fa-stethoscope"></i> Diagnostic de la feuille de route</h4>
                        <div v-if="diagnostic" class="dg-sous-titre">
                            <strong>{{ diagnostic.intervenant.nom }}</strong>
                            · statut « {{ diagnostic.intervenant.statut }} » ({{ diagnostic.intervenant.typeIntervenant }})
                            · {{ diagnostic.intervenant.annee }}
                        </div>
                    </div>
                    <div class="dg-actions">
                        <a v-if="diagnostic" class="btn btn-light" :href="etatLieuxUrl"
                           title="Télécharger un PDF complet : diagnostic, paramétrage du statut, état des données et du workflow">
                            <i class="far fa-file-pdf"></i> État des lieux PDF
                        </a>
                        <button v-if="diagnostic" type="button" class="btn btn-light" @click="copier"
                                title="Copier le diagnostic au format texte, pour le coller dans une demande d'assistance">
                            <i :class="copie ? 'fas fa-check' : 'far fa-copy'"></i> {{ copie ? 'Copié' : 'Copier le diagnostic' }}
                        </button>
                        <button type="button" class="btn-close" title="Fermer" @click="emit('fermer')"></button>
                    </div>
                </div>

                <div class="dg-corps">
                    <div v-if="chargement" class="dg-chargement">
                        <i class="fas fa-spinner fa-spin"></i> Analyse du workflow de l'intervenant…
                    </div>

                    <template v-else-if="diagnostic">
                        <!-- Fraîcheur de la feuille de route enregistrée -->
                        <div v-if="!diagnostic.aJour" class="dg-callout dg-callout-warning">
                            <i class="fas fa-triangle-exclamation"></i>
                            <div>
                                <strong>La feuille de route affichée n'est pas à jour.</strong>
                                Le recalcul donne un résultat différent pour :
                                {{ diagnostic.ecarts.join(', ') }}.
                                Le diagnostic ci-dessous correspond au recalcul ; cliquez sur « Actualiser » pour mettre
                                à jour la feuille de route.
                            </div>
                        </div>
                        <div v-else class="dg-callout dg-callout-success">
                            <i class="fas fa-circle-check"></i>
                            <div>La feuille de route affichée est à jour avec les données et le paramétrage actuels.</div>
                        </div>

                        <!-- Filtres -->
                        <div class="dg-filtres">
                            <button v-for="f in filtres" :key="f.code" type="button"
                                    :class="['dg-filtre', {'dg-filtre-actif': filtre === f.code}]"
                                    :disabled="!f.nb" @click="filtre = f.code">
                                {{ f.libelle }} <span class="dg-filtre-nb">{{ f.nb }}</span>
                            </button>
                        </div>

                        <!-- Étapes -->
                        <div class="dg-etapes">
                            <div v-for="etape in etapesFiltrees" :key="etape.code"
                                 :class="['dg-etape', 'dg-etape-' + etape.etat, {'dg-etape-ouverte': ouvertes.has(etape.code)}]">
                                <button type="button" class="dg-etape-entete" @click="basculer(etape.code)">
                                    <span class="dg-numero">{{ etape.ordre }}</span>
                                    <span class="dg-etape-titre">
                                        <span class="dg-etape-libelle">{{ etape.libelle }}</span>
                                        <span class="dg-etape-resume">{{ etape.resume }}</span>
                                    </span>
                                    <span class="dg-badges">
                                        <span v-if="etape.courante" class="badge badge-warning dg-badge">
                                            <i class="fas fa-location-dot"></i> En cours
                                        </span>
                                        <span v-if="etape.alerte" class="badge badge-danger dg-badge" title="Incohérence à vérifier">
                                            <i class="fas fa-triangle-exclamation"></i>
                                        </span>
                                        <span v-if="etape.visibilite && !etape.visibilite.visible" class="badge badge-light dg-badge"
                                              title="Masquée pour vous dans la feuille de route">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                        <span :class="['badge', 'dg-badge', badgeEtat(etape.etat)]">{{ etape.etatLibelle }}</span>
                                        <i class="fas fa-chevron-down dg-chevron"></i>
                                    </span>
                                </button>

                                <div v-if="ouvertes.has(etape.code)" class="dg-etape-detail">
                                    <div v-if="etape.alerte" class="dg-callout dg-callout-danger">
                                        <i class="fas fa-triangle-exclamation"></i>
                                        <div>{{ etape.alerte }}</div>
                                    </div>

                                    <div class="dg-rubrique">
                                        <div class="dg-rubrique-titre"><i class="fas fa-id-badge"></i> Statut</div>
                                        <div>{{ etape.activation.texte }}</div>
                                    </div>

                                    <div v-if="etape.conditions.length" class="dg-rubrique">
                                        <div class="dg-rubrique-titre"><i class="fas fa-link"></i> Conditions</div>
                                        <div v-for="(c, i) in etape.conditions" :key="i" :class="['dg-condition', 'dg-condition-' + c.resultat]">
                                            <i :class="iconeCondition(c.resultat)"></i>
                                            <div>
                                                <div>
                                                    Requiert <strong>« {{ c.etapeRequise }} »</strong>
                                                    <span class="dg-regle">{{ c.regle }}</span>
                                                </div>
                                                <div v-if="c.precision" class="dg-precision">{{ c.precision }}</div>
                                                <div class="dg-condition-texte">{{ c.texte }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else-if="etape.activation.prevue" class="dg-rubrique">
                                        <div class="dg-rubrique-titre"><i class="fas fa-link"></i> Conditions</div>
                                        <div class="dg-vide">Aucune condition paramétrée pour cette étape.</div>
                                    </div>

                                    <div v-if="etape.structures.length" class="dg-rubrique">
                                        <div class="dg-rubrique-titre"><i class="fas fa-sitemap"></i> Avancement</div>
                                        <div v-for="(s, i) in etape.structures" :key="i" class="dg-structure">
                                            <span class="dg-structure-libelle">{{ s.libelle }}</span>
                                            <span class="dg-barre"><span :style="{width: s.pourc + '%'}"></span></span>
                                            <span class="dg-pourc">{{ s.pourc }} %</span>
                                            <span :class="['badge', 'dg-badge', s.atteignable ? 'badge-success' : 'badge-secondary']">
                                                {{ s.atteignable ? 'Accessible' : 'Bloquée' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div v-if="etape.visibilite" class="dg-rubrique">
                                        <div class="dg-rubrique-titre"><i class="fas fa-eye"></i> Visibilité pour vous</div>
                                        <div>{{ etape.visibilite.visible ? 'Affichée dans votre feuille de route.' : 'Masquée dans votre feuille de route : ' + etape.visibilite.texte.charAt(0).toLowerCase() + etape.visibilite.texte.slice(1) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="dg-pied">
                    <span v-if="diagnostic" class="dg-date">
                        Établi le {{ diagnostic.date }}
                        <template v-if="diagnostic.utilisateur.role"> · vu en tant que {{ diagnostic.utilisateur.role }}</template>
                    </span>
                    <button type="button" class="btn btn-primary" @click="emit('fermer')">Fermer</button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
<script setup>

import {computed, nextTick, onMounted, ref} from "vue";

const props = defineProps({
    intervenant: {type: Number, required: true},
});

const emit = defineEmits(['fermer']);

const fond = ref(null);
const chargement = ref(true);
const diagnostic = ref(null);
const filtre = ref('toutes');
const ouvertes = ref(new Set());
const copie = ref(false);
const etatLieuxUrl = unicaenVue.url('workflow/feuille-de-route-etat-lieux/:intervenant', {intervenant: props.intervenant});

const filtres = computed(() => {
    const etapes = diagnostic.value?.etapes || [];
    const prevues = etapes.filter(e => e.activation.prevue);
    return [
        {code: 'prevues', libelle: 'Prévues', nb: prevues.length},
        {code: 'bloquees', libelle: 'Bloquées', nb: etapes.filter(e => e.etat === 'bloquee').length},
        {code: 'alertes', libelle: 'Incohérences', nb: etapes.filter(e => e.alerte).length},
        {code: 'masquees', libelle: 'Masquées pour vous', nb: prevues.filter(e => e.visibilite && !e.visibilite.visible).length},
        {code: 'non-prevues', libelle: 'Non prévues', nb: etapes.length - prevues.length},
        {code: 'toutes', libelle: 'Toutes', nb: etapes.length},
    ];
});

const etapesFiltrees = computed(() => {
    const etapes = diagnostic.value?.etapes || [];
    switch (filtre.value) {
        case 'prevues':
            return etapes.filter(e => e.activation.prevue);
        case 'bloquees':
            return etapes.filter(e => e.etat === 'bloquee');
        case 'alertes':
            return etapes.filter(e => e.alerte);
        case 'masquees':
            return etapes.filter(e => e.activation.prevue && e.visibilite && !e.visibilite.visible);
        case 'non-prevues':
            return etapes.filter(e => !e.activation.prevue);
        default:
            return etapes;
    }
});

const basculer = (code) => {
    const set = new Set(ouvertes.value);
    set.has(code) ? set.delete(code) : set.add(code);
    ouvertes.value = set;
};

const badgeEtat = (etat) => ({
    'franchie': 'badge-success',
    'en-cours': 'badge-warning',
    'a-faire': 'badge-primary',
    'bloquee': 'badge-secondary',
    'non-prevue': 'badge-light',
    'desactivee': 'badge-light',
}[etat]);

const iconeCondition = (resultat) => ({
    'remplie': 'fas fa-circle-check',
    'non-remplie': 'fas fa-circle-xmark',
    'partielle': 'fas fa-circle-half-stroke',
    'ignoree': 'fas fa-circle-minus',
}[resultat]);

const copier = () => {
    navigator.clipboard.writeText(diagnostic.value.texte).then(() => {
        copie.value = true;
        setTimeout(() => copie.value = false, 2000);
    });
};

onMounted(() => {
    nextTick(() => fond.value?.focus());
    unicaenVue.axios.get(
        unicaenVue.url('workflow/feuille-de-route-diagnostic/:intervenant', {intervenant: props.intervenant})
    ).then(response => {
        diagnostic.value = response.data;
        if (diagnostic.value) {
            // On ouvre directement sur ce qui pose question
            const f = filtres.value;
            filtre.value = f.find(x => x.code === 'alertes').nb ? 'alertes'
                : (f.find(x => x.code === 'bloquees').nb ? 'bloquees' : 'prevues');
        }
    }).finally(() => {
        chargement.value = false;
    });
});

</script>
<style scoped>

/* Couleurs issues de la charte OSE (MDB5), cohérentes avec la feuille de route */

.dg-fond {
    position: fixed;
    inset: 0;
    z-index: 1060;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 2rem 1rem;
    background: rgba(0, 0, 0, .45);
    outline: none;
    overflow-y: auto;
    animation: dg-fondu .15s ease-out;
}

.dg-modale {
    width: 100%;
    max-width: 900px;
    background: #fff;
    border-radius: .5em;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .25);
    animation: dg-apparition .2s ease-out;
}

.dg-entete {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--mdb-border-color, #e0e0e0);
}

.dg-entete h4 {
    margin: 0;
    font-weight: 300;
    color: var(--mdb-body-color, #4f4f4f);
}

.dg-entete h4 i {
    color: var(--mdb-primary, #3b71ca);
    margin-right: .4rem;
}

.dg-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: .5rem;
    flex-shrink: 0;
}

.dg-actions .btn-close {
    margin-left: .5rem;
}

.dg-sous-titre {
    margin-top: .25rem;
    font-size: .85rem;
    color: #757575;
}

.dg-corps {
    padding: 1rem 1.25rem;
    color: var(--mdb-body-color, #4f4f4f);
}

.dg-chargement {
    padding: 2rem;
    text-align: center;
    color: #757575;
}

.dg-pied {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .5rem;
    padding: .75rem 1.25rem;
    background: #fafafa;
    border-top: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: 0 0 .5em .5em;
}

.dg-date {
    margin-right: auto;
    font-size: .8rem;
    color: #757575;
}

/* Encadrés */
.dg-callout {
    display: flex;
    gap: .75rem;
    padding: .6rem .9rem;
    margin-bottom: .75rem;
    border-radius: .5em;
    border-left: 4px solid;
    font-size: .875rem;
}

.dg-callout > i {
    margin-top: .2rem;
}

.dg-callout-warning {
    background: #fdf5e6;
    border-color: #e4a11b;
    color: #73510d;
}

.dg-callout-success {
    background: #e7f6ec;
    border-color: var(--mdb-success, #14a44d);
    color: #0d6832;
}

.dg-callout-danger {
    background: #fbe7e9;
    border-color: var(--mdb-danger, #dc4c64);
    color: #8a2232;
}

/* Filtres */
.dg-filtres {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    margin-bottom: .75rem;
}

.dg-filtre {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .25rem .7rem;
    font-size: .8rem;
    background: #fff;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: 1rem;
    color: var(--mdb-body-color, #4f4f4f);
}

.dg-filtre:hover:not(:disabled) {
    border-color: #c4d3ee;
}

.dg-filtre:disabled {
    opacity: .45;
    cursor: default;
}

.dg-filtre-actif {
    background: #ebf0fa;
    border-color: var(--mdb-primary, #3b71ca);
    color: #2c58a0;
    font-weight: 600;
}

.dg-filtre-nb {
    padding: 0 .4rem;
    border-radius: 1rem;
    background: #eeeeee;
    font-size: .7rem;
}

.dg-filtre-actif .dg-filtre-nb {
    background: #c4d3ee;
}

/* Étapes */
.dg-etapes {
    display: flex;
    flex-direction: column;
    gap: .35rem;
}

.dg-etape {
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-left: 3px solid var(--mdb-primary, #3b71ca);
    border-radius: .5em;
    background: #fff;
}

.dg-etape-franchie { border-left-color: var(--mdb-success, #14a44d); }
.dg-etape-en-cours { border-left-color: var(--mdb-warning, #e4a11b); }
.dg-etape-bloquee { border-left-color: var(--mdb-secondary, #9fa6b2); }
.dg-etape-non-prevue,
.dg-etape-desactivee {
    border-left-color: #e0e0e0;
    background: #fafafa;
}

.dg-etape-entete {
    display: flex;
    align-items: center;
    gap: .65rem;
    width: 100%;
    padding: .45rem .8rem;
    background: none;
    border: 0;
    text-align: left;
    color: inherit;
}

.dg-etape-entete:hover {
    background: #f5f5f5;
}

.dg-numero {
    flex: 0 0 auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.6rem;
    height: 1.6rem;
    border-radius: .35rem;
    background: #eeeeee;
    color: #757575;
    font-weight: 600;
    font-size: .75rem;
}

.dg-etape-titre {
    flex: 1 1 auto;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.dg-etape-libelle {
    font-size: .875rem;
    font-weight: 600;
}

.dg-etape-non-prevue .dg-etape-libelle,
.dg-etape-desactivee .dg-etape-libelle {
    color: #9e9e9e;
    font-weight: 400;
}

.dg-etape-resume {
    font-size: .78rem;
    color: #757575;
}

.dg-badges {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    gap: .3rem;
}

.dg-badge {
    border-radius: 1rem;
    font-size: .7rem;
    font-weight: 500;
    white-space: nowrap;
}

/* Dans app.css, le .badge de Bootstrap (color: #fff) écrase la couleur de texte des .badge-* de MDB */
.dg-badge.badge-primary   { color: #2c58a0; }
.dg-badge.badge-secondary { color: #40464f; }
.dg-badge.badge-success   { color: #0d6832; }
.dg-badge.badge-warning   { color: #73510d; }
.dg-badge.badge-danger    { color: #8a2232; }
.dg-badge.badge-light     { color: #4f4f4f; }

.dg-chevron {
    font-size: .75rem;
    color: #9e9e9e;
    transform: rotate(-90deg);
    transition: transform .2s ease;
}

.dg-etape-ouverte .dg-chevron {
    transform: none;
}

/* Détail */
.dg-etape-detail {
    padding: .5rem .9rem .75rem 3.05rem;
    border-top: 1px solid var(--mdb-border-color, #e0e0e0);
    background: #fafafa;
    border-radius: 0 0 .5em .5em;
    font-size: .85rem;
}

.dg-rubrique {
    margin-top: .5rem;
}

.dg-rubrique-titre {
    margin-bottom: .2rem;
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--mdb-secondary, #9fa6b2);
}

.dg-rubrique-titre i {
    margin-right: .25rem;
}

.dg-vide {
    color: #9e9e9e;
    font-style: italic;
}

.dg-condition {
    display: flex;
    gap: .5rem;
    padding: .35rem .55rem;
    margin-bottom: .3rem;
    background: #fff;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: .4rem;
}

.dg-condition > i {
    margin-top: .2rem;
}

.dg-condition-remplie > i { color: var(--mdb-success, #14a44d); }
.dg-condition-non-remplie > i { color: var(--mdb-danger, #dc4c64); }
.dg-condition-partielle > i { color: var(--mdb-warning, #e4a11b); }
.dg-condition-ignoree { background: #f5f5f5; color: #757575; }
.dg-condition-ignoree > i { color: #bdbdbd; }

.dg-regle {
    margin-left: .35rem;
    font-size: .75rem;
    color: #757575;
}

.dg-precision {
    font-size: .75rem;
    font-style: italic;
    color: #9e9e9e;
}

.dg-condition-texte {
    margin-top: .1rem;
}

.dg-condition-non-remplie .dg-condition-texte { color: #8a2232; }
.dg-condition-partielle .dg-condition-texte { color: #73510d; }

.dg-structure {
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-bottom: .25rem;
}

.dg-structure-libelle {
    flex: 0 0 12rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dg-barre {
    flex: 1 1 auto;
    height: .4rem;
    border-radius: 1rem;
    background: #e0e0e0;
    overflow: hidden;
}

.dg-barre span {
    display: block;
    height: 100%;
    background: var(--mdb-success, #14a44d);
}

.dg-pourc {
    flex: 0 0 3rem;
    text-align: right;
    font-size: .78rem;
    color: #757575;
}

@keyframes dg-fondu {
    from { opacity: 0; }
}

@keyframes dg-apparition {
    from { opacity: 0; transform: translateY(-12px); }
}

@media (max-width: 768px) {
    .dg-etape-entete {
        flex-wrap: wrap;
    }

    .dg-badges {
        width: 100%;
        justify-content: flex-end;
    }

    .dg-etape-detail {
        padding-left: 1rem;
    }

    .dg-structure-libelle {
        flex-basis: 7rem;
    }
}

</style>
