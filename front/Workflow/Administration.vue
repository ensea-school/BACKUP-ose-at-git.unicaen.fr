<template>
    <h1 class="page-header">Workflow</h1>

    <!-- Barre d'outils -->
    <div class="wf-toolbar">
        <div class="wf-toolbar-info">
            <span class="wf-stat"><i class="fas fa-list-ol"></i> {{ etapes.length }} étape{{ etapes.length > 1 ? 's' : '' }}</span>
            <span class="wf-stat"><i class="fas fa-link"></i> {{ nbDependances }} dépendance{{ nbDependances > 1 ? 's' : '' }}</span>
            <span v-if="canEdit" class="wf-hint">
                <i class="fas fa-circle-info"></i>
                Glissez-déposez les étapes avec <i class="fas fa-grip-vertical"></i> pour modifier leur ordre
            </span>
        </div>
        <div class="wf-toolbar-boutons">
            <button type="button" class="btn btn-light btn-sm" @click="aideVisible = !aideVisible">
                <i class="fas fa-circle-question"></i> Comment lire cette page ?
            </button>
            <button type="button" class="btn btn-light btn-sm" @click="toggleToutes">
                <i :class="toutesMasquees ? 'fas fa-up-right-and-down-left-from-center' : 'fas fa-down-left-and-up-right-to-center'"></i>
                {{ toutesMasquees ? 'Déplier' : 'Replier' }} toutes les étapes
            </button>
            <a v-if="canEdit" class="btn btn-light btn-sm" :href="rapportUrl"
               title="Télécharger le rapport PDF du paramétrage du workflow, à joindre à une demande d'assistance">
                <i class="fas fa-spider"></i>
                Debuggage
            </a>
            <a v-if="canEdit" class="btn btn-light btn-sm" :href="exportUrl"
               title="Télécharger la configuration du workflow (fichier JSON), pour la recharger sur une autre instance d'OSE">
                <i class="fas fa-file-export"></i>
                Exporter le workflow
            </a>
            <button v-if="canEdit" type="button" class="btn btn-light btn-sm" :disabled="importEnCours"
                    title="Remplacer le paramétrage du workflow de l'année en cours par celui d'un fichier exporté"
                    @click="fichierImport.click()">
                <i :class="importEnCours ? 'fas fa-spinner fa-spin' : 'fas fa-file-import'"></i>
                Importer un workfloww
            </button>
            <input v-if="canEdit" ref="fichierImport" type="file" accept=".json,application/json" hidden
                   @change="importer">
        </div>
    </div>

    <!-- Aide -->
    <div v-show="aideVisible" class="wf-aide">
        <button type="button" class="btn-close wf-aide-fermer" title="Fermer l'aide" @click="aideVisible = false"></button>
        <h4><i class="fas fa-route"></i> Le workflow en bref</h4>
        <p>
            Le workflow est le <strong>parcours</strong> que suit chaque intervenant, étape par étape.
            Une étape n'est <strong>accessible</strong> que lorsque toutes ses conditions sont remplies :
            ce sont les <strong>dépendances</strong> listées sous chaque étape.
        </p>
        <div class="wf-aide-grille">
            <div>
                <h5><i class="fas fa-flag-checkered"></i> Niveau d'avancement exigé</h5>
                <ul>
                    <li><strong>Commencée</strong> : il suffit que l'étape précédente ait démarré
                        (une heure saisie, une pièce fournie, une heure validée...)</li>
                    <li><strong>Terminée en partie</strong> : l'étape précédente est terminée pour au moins une partie
                        (par exemple une composante)</li>
                    <li><strong>Entièrement terminée</strong> : l'étape précédente doit être complète</li>
                </ul>
            </div>
            <div>
                <h5><i class="fas fa-sitemap"></i> Périmètre</h5>
                <ul>
                    <li><strong>Dans la même composante</strong> : la condition est vérifiée composante par composante.
                        <em>Ex. : un intervenant enseigne à l'IUT et en Droit, son service n'est validé qu'à l'IUT ;
                            l'étape suivante s'ouvre pour l'IUT et reste fermée pour le Droit.</em></li>
                    <li><strong>Quelle que soit la composante</strong> : la condition est vérifiée globalement pour l'intervenant.</li>
                </ul>
            </div>
        </div>
        <h5><i class="fas fa-arrows-up-down"></i> Ordre des étapes</h5>
        <p>
            Les étapes peuvent être <strong>réordonnées</strong> en les faisant glisser avec la poignée
            <i class="fas fa-grip-vertical"></i>. Cet ordre est celui de la <strong>feuille de route</strong>
            et du menu de l'intervenant.
        </p>
        <ul>
            <li>Gardez une <strong>logique chronologique</strong> : une étape doit toujours être placée
                <strong>après</strong> les étapes dont elle dépend.
                <em>Ex. : la validation des enseignements après leur saisie, le contrat après la validation.</em></li>
            <li>Certains déplacements sont <strong>refusés</strong> : une étape ne peut pas passer avant une étape
                dont elle dépend, ni avant une étape qui doit la précéder par conception.</li>
            <li>L'ordre <strong>ne crée pas</strong> de condition : seules les dépendances décident si une étape
                est accessible. Un ordre incohérent avec les dépendances rendrait la feuille de route difficile
                à comprendre pour les intervenants.</li>
        </ul>
        <p class="wf-aide-note">
            <i class="fas fa-lightbulb"></i>
            Une étape sans condition est accessible dès le début du parcours.
            Pensez à vérifier ce qu'une étape <strong>débloque</strong> avant de modifier ses conditions.
        </p>
    </div>

    <VueDraggable v-model="etapes" direction="vertical" :animation="150" handle=".drag-handle" :scroll="true"
                  ghost-class="wf-ghost" @end="tri" class="wf-liste">
        <div v-for="(etape, index) in etapes" :key="etape.code" class="wf-etape"
             :id="'wf-etape-' + etape.code"
             :class="{ 'wf-etape-repliee': isMasquee(etape), 'wf-etape-surlignee': surlignee === etape.code }">

            <!-- En-tête de l'étape -->
            <div class="wf-etape-header">
                <span v-if="canEdit" class="drag-handle"
                      title="Vous pouvez ordonnancer les étapes du workflow, sous condition qu'il n'y ait pas de blocage ou de dépendance bloquante">
                    <i class="fas fa-grip-vertical"></i>
                </span>

                <span class="wf-numero">{{ index + 1 }}</span>

                <div class="wf-titre" role="button" @click="toggle(etape)">
                    <span class="wf-libelle">{{ etape.libelleAutres }}</span>
                    <span class="wf-code">{{ etape.code }}</span>
                </div>

                <div class="wf-badges">
                    <span v-if="etape.perimetre" class="badge badge-info wf-badge"
                          :title="'Périmètre de l\'étape : ' + etape.perimetre.libelle">
                        <i :class="perimetreIcone(etape.perimetre.code)"></i> {{ etape.perimetre.libelle }}
                    </span>
                    <span class="badge wf-badge" :class="etape.dependances.length ? 'badge-primary' : 'badge-light'"
                          :title="etape.dependances.length + ' dépendance(s)'">
                        <i class="fas fa-link"></i> {{ etape.dependances.length }}
                    </span>
                </div>

                <div class="wf-actions">
                    <a v-if="canEdit" class="wf-action"
                       title="Personnalisation des libellés & messages"
                       :href="personnalisationUrl + '/' + etape.id"
                       @click.prevent="saisie"><i class="fas fa-pen-to-square"></i></a>
                    <a href="#" class="wf-action"
                       :title="isMasquee(etape) ? 'Afficher les dépendances' : 'Masquer les dépendances'"
                       @click.prevent="toggle(etape)">
                        <i class="fas fa-chevron-down wf-chevron" :class="{ 'wf-chevron-ferme': isMasquee(etape) }"></i>
                    </a>
                </div>
            </div>

            <!-- Dépendances -->
            <div v-show="!isMasquee(etape)" class="wf-etape-body">
                <div v-if="etape.dependances.length" class="wf-deps">
                    <div class="wf-deps-titre">
                        <i class="fas fa-lock"></i>
                        Accessible quand {{ etape.dependances.length > 1 ? 'toutes ces conditions sont remplies' : 'cette condition est remplie' }}
                    </div>

                    <div v-for="d in etape.dependances" :key="d.id" class="wf-dep"
                         :class="{ 'wf-dep-inactive': !d.active }">
                        <i class="fas fa-turn-up wf-dep-icone"></i>

                        <div class="wf-dep-etape">
                            <div class="wf-phrase">
                                « <strong>{{ d.etapePrecedante.libelleAutres }}</strong> »
                                {{ phraseAvancement(d) }}
                            </div>
                            <div class="wf-phrase-details">
                                <span :title="perimetreDescription(d.perimetre.code)">
                                    <i :class="perimetreIcone(d.perimetre.code)"></i> {{ phrasePerimetre(d) }}
                                </span>
                                <span>
                                    <i :class="d.typeIntervenant ? 'fas fa-user' : 'fas fa-users'"></i>
                                    {{ d.typeIntervenant ? 'uniquement pour les ' + d.typeIntervenant.libelle.toLowerCase() + 's' : 'pour tous les intervenants' }}
                                </span>
                                <span v-if="!d.active" class="badge badge-secondary wf-badge"
                                      title="Cette condition est désactivée : elle n'est pas prise en compte">
                                    <i class="fas fa-power-off"></i> Désactivée
                                </span>
                            </div>
                        </div>

                        <div v-if="canEdit" class="wf-dep-actions">
                            <a class="wf-action"
                               :href="saisieUrl + '/' + etape.id + '/' + d.id"
                               title="Modification de la dépendance"
                               @click.prevent="saisie"><i class="fas fa-pen-to-square"></i></a>
                            <a class="wf-action wf-action-danger" :href="suppressionUrl + '/' + d.id"
                               data-content="Êtes-vous sur de vouloir supprimer la règle de dépendance ?"
                               data-title="Suppression de la dépendance"
                               title="Suppression de la dépendance"
                               @click.prevent="suppression"><i class="fas fa-trash-can"></i></a>
                        </div>
                    </div>
                </div>

                <div v-else class="wf-nodep">
                    <i class="fas fa-unlock"></i> Aucune dépendance : cette étape n'est conditionnée par aucune autre.
                </div>

                <div v-if="debloque(etape).length" class="wf-debloque">
                    <span class="wf-debloque-titre"><i class="fas fa-unlock-keyhole"></i> Débloque :</span>
                    <a v-for="suivante in debloque(etape)" :key="suivante.code" href="#" class="wf-debloque-etape"
                       :title="'Aller à l\'étape « ' + suivante.libelleAutres + ' »'"
                       @click.prevent="allerA(suivante)">
                        <span class="wf-debloque-num">{{ numero(suivante) }}</span> {{ suivante.libelleAutres }}
                    </a>
                </div>

                <a v-if="canEdit && index > 0" :href="saisieUrl + '/' + etape.id" class="btn btn-primary btn-sm wf-ajout"
                   @click.prevent="saisie">
                    <i class="fas fa-plus"></i> Ajouter une dépendance</a>
            </div>
        </div>
    </VueDraggable>

    <!-- Confirmation d'import -->
    <Teleport to="body">
        <div v-if="importModale" class="wf-modale-fond" @click.self="fermerImport"
             @keydown.esc="fermerImport" tabindex="-1" ref="modaleImport">
            <div class="wf-modale" role="dialog" aria-modal="true" aria-labelledby="wf-modale-titre">
                <div class="wf-modale-entete">
                    <h4 id="wf-modale-titre"><i class="fas fa-file-import"></i> Importer une configuration du workflow</h4>
                    <button type="button" class="btn-close" title="Fermer" @click="fermerImport"></button>
                </div>

                <div class="wf-modale-corps">
                    <div class="wf-fichier">
                        <i class="fas fa-file-code"></i>
                        <span>{{ importModale.fichier.name }}</span>
                    </div>

                    <div v-if="importModale.erreur" class="wf-callout wf-callout-danger">
                        <i class="fas fa-circle-xmark"></i>
                        <div>{{ importModale.erreur }}</div>
                    </div>

                    <template v-else>
                        <dl class="wf-origine">
                            <dt>Établissement</dt>
                            <dd>{{ importModale.origine.etablissement || 'Non renseigné' }}</dd>
                            <dt>Année</dt>
                            <dd>{{ importModale.origine.annee ? importModale.origine.annee + '/' + (importModale.origine.annee + 1) : 'Non renseignée' }}</dd>
                            <dt>Version d'OSE</dt>
                            <dd>{{ importModale.origine.version || 'Non renseignée' }}</dd>
                            <dt>Exporté le</dt>
                            <dd>{{ importModale.date }}<span v-if="importModale.origine.utilisateur"> par {{ importModale.origine.utilisateur }}</span></dd>
                            <dt>Contenu</dt>
                            <dd>
                                <span class="badge badge-primary">{{ importModale.nbEtapes }} étape{{ importModale.nbEtapes > 1 ? 's' : '' }}</span>
                                <span class="badge badge-info">{{ importModale.nbDependances }} dépendance{{ importModale.nbDependances > 1 ? 's' : '' }}</span>
                            </dd>
                        </dl>

                        <fieldset class="wf-portee">
                            <legend>Années concernées</legend>
                            <label :class="{'wf-portee-choisie': portee === 'suivantes'}">
                                <input type="radio" value="suivantes" v-model="portee">
                                <span>
                                    <strong>{{ annee }} et les années suivantes</strong>
                                    <small>Les années suivantes sont alignées sur {{ annee }}.</small>
                                </span>
                            </label>
                            <label :class="{'wf-portee-choisie': portee === 'annee'}">
                                <input type="radio" value="annee" v-model="portee">
                                <span>
                                    <strong>{{ annee }} uniquement</strong>
                                    <small>Les années suivantes gardent leur paramétrage actuel.</small>
                                </span>
                            </label>
                        </fieldset>

                        <div class="wf-annees">
                            <div class="wf-annee wf-annee-passee">
                                <i class="fas fa-lock"></i>
                                <span>Années précédentes</span>
                                <small>non modifiées</small>
                            </div>
                            <i class="fas fa-chevron-right wf-annee-fleche"></i>
                            <div class="wf-annee wf-annee-cible">
                                <i class="fas fa-file-import"></i>
                                <span>{{ annee }}</span>
                                <small>remplacée</small>
                            </div>
                            <i class="fas fa-chevron-right wf-annee-fleche"></i>
                            <div :class="['wf-annee', portee === 'suivantes' ? 'wf-annee-cible' : 'wf-annee-passee']">
                                <i :class="portee === 'suivantes' ? 'fas fa-forward' : 'fas fa-lock'"></i>
                                <span>Années suivantes</span>
                                <small>{{ portee === 'suivantes' ? 'alignées sur ' + annee : 'non modifiées' }}</small>
                            </div>
                        </div>

                        <div class="wf-callout wf-callout-warning">
                            <i class="fas fa-triangle-exclamation"></i>
                            <div>
                                <template v-if="portee === 'suivantes'">
                                    Le paramétrage du workflow (ordre, libellés et dépendances des étapes) de
                                    <strong>{{ annee }}</strong> et de <strong>toutes les années suivantes</strong> sera
                                    <strong>remplacé</strong> par celui de ce fichier, y compris les personnalisations
                                    déjà faites sur une année future.
                                </template>
                                <template v-else>
                                    Le paramétrage du workflow (ordre, libellés et dépendances des étapes) de
                                    <strong>{{ annee }}</strong> sera <strong>remplacé</strong> par celui de ce fichier.
                                    Les autres années ne seront pas modifiées.
                                </template>
                                Pensez à exporter la configuration actuelle si vous souhaitez pouvoir y revenir.
                            </div>
                        </div>
                    </template>
                </div>

                <div class="wf-modale-pied">
                    <button type="button" class="btn btn-light" @click="fermerImport">Annuler</button>
                    <button v-if="!importModale.erreur" type="button" class="btn btn-primary" @click="confirmerImport">
                        <i class="fas fa-file-import"></i> Importer
                    </button>
                </div>
            </div>
        </div>
    </Teleport>

</template>
<script setup>
import {ref, computed, nextTick, onMounted, defineProps} from 'vue';
import {VueDraggable} from 'vue-draggable-plus'

const props = defineProps({
    canEdit: {type: Boolean},
    annee: {type: String},
    anneePassee: {type: Boolean},
});

const etapes = ref([]);

const aideVisible = ref(false);
const surlignee = ref(null);

// Constantes WorkflowEtapeDependance::AVANCEMENT_*
const AVANCEMENT_DEBUTE = 1;
const AVANCEMENT_PARTIEL = 2;
const AVANCEMENT_INTEGRAL = 3;

/**
 * Traduit la règle d'avancement en français, en fonction de la nature de l'étape précédente
 * (saisie, validation, ou autre).
 */
const phraseAvancement = (d) => {
    const code = d.etapePrecedante.code || '';
    const estValidation = code.includes('validation');
    const estSaisie = code.includes('saisie') || code === 'demande_mep';

    switch (d.avancement) {
        case AVANCEMENT_DEBUTE:
            if (estValidation) return 'est commencée (au moins une partie est validée)';
            if (estSaisie) return 'est commencée (au moins une donnée saisie)';
            return 'est commencée';
        case AVANCEMENT_PARTIEL:
            if (estValidation) return 'est terminée en partie (validation complète pour au moins une partie)';
            return 'est terminée en partie';
        case AVANCEMENT_INTEGRAL:
            if (estValidation) return 'est entièrement terminée (tout est validé)';
            if (estSaisie) return 'est entièrement terminée (tout est saisi)';
            return 'est entièrement terminée';
        default:
            return '(' + (d.avancementLibelle || 'règle inconnue') + ')';
    }
};

const phrasePerimetre = (d) => {
    switch (d.perimetre?.code) {
        case 'composante':
            return 'dans la même composante';
        case 'etablissement':
            return 'quelle que soit la composante';
        default:
            return d.perimetre?.libelle || '';
    }
};

// Pour chaque étape, liste des étapes qu'elle conditionne (dépendances actives uniquement)
const debloqueParCode = computed(() => {
    const res = {};
    for (const e of etapes.value) {
        for (const d of e.dependances || []) {
            if (!d.active) continue;
            const prec = d.etapePrecedante.code;
            (res[prec] ??= []);
            if (!res[prec].some(s => s.code === e.code)) {
                res[prec].push(e);
            }
        }
    }
    return res;
});

const debloque = (etape) => debloqueParCode.value[etape.code] || [];

const numero = (etape) => etapes.value.findIndex(e => e.code === etape.code) + 1;

// Déplie l'étape ciblée, la fait défiler à l'écran et la met brièvement en évidence
const allerA = (etape) => {
    if (isMasquee(etape)) {
        toggle(etape);
    }
    document.getElementById('wf-etape-' + etape.code)?.scrollIntoView({behavior: 'smooth', block: 'center'});
    surlignee.value = etape.code;
    setTimeout(() => {
        if (surlignee.value === etape.code) surlignee.value = null;
    }, 1600);
};

// Codes des étapes dont les dépendances sont masquées (indexé par code pour survivre aux rechargements/tris)
const masquees = ref(new Set());

const isMasquee = (etape) => masquees.value.has(etape.code);

const toggle = (etape) => {
    const set = new Set(masquees.value);
    set.has(etape.code) ? set.delete(etape.code) : set.add(etape.code);
    masquees.value = set;
};

const toutesMasquees = computed(() =>
    etapes.value.length > 0 && etapes.value.every(e => masquees.value.has(e.code))
);

const toggleToutes = () => {
    masquees.value = toutesMasquees.value
        ? new Set()
        : new Set(etapes.value.map(e => e.code));
};

const nbDependances = computed(() =>
    etapes.value.reduce((n, e) => n + (e.dependances?.length || 0), 0)
);

const load = () => {
    unicaenVue.axios.get(
        unicaenVue.url('workflow/administration/data')
    ).then(response => {
        etapes.value = response.data;
    });
};

const saisieUrl = unicaenVue.url('workflow/administration/saisie-dependance');
const suppressionUrl = unicaenVue.url('workflow/administration/suppression-dependance');
const personnalisationUrl = unicaenVue.url('workflow/administration/modification-etape');
const rapportUrl = unicaenVue.url('workflow/administration/rapport');
const exportUrl = unicaenVue.url('workflow/administration/export');

const fichierImport = ref(null);
const importEnCours = ref(false);

const importModale = ref(null);
// Sur une année passée, on modifie par défaut cette seule année ; sinon l'année et les suivantes
const portee = ref('suivantes');
const modaleImport = ref(null);

const importer = (event) => {
    const fichier = event.target.files[0];
    event.target.value = '';
    if (!fichier) return;

    const lecteur = new FileReader();
    lecteur.onload = () => {
        const modale = {fichier, erreur: null, origine: {}, date: '', nbEtapes: 0, nbDependances: 0};
        try {
            const data = JSON.parse(lecteur.result);
            if (data.format !== 'ose-workflow-parametrage' || !Array.isArray(data.etapes)) {
                throw new Error();
            }
            modale.origine = data.origine || {};
            modale.date = modale.origine.date ? new Date(modale.origine.date).toLocaleString('fr-FR', {dateStyle: 'long', timeStyle: 'short'}) : 'Date inconnue';
            modale.nbEtapes = data.etapes.length;
            modale.nbDependances = data.etapes.reduce((n, e) => n + (e.dependances?.length || 0), 0);
        } catch (e) {
            modale.erreur = 'Ce fichier n\'est pas un export de configuration du workflow OSE.';
        }
        portee.value = props.anneePassee ? 'annee' : 'suivantes';
        importModale.value = modale;
        nextTick(() => modaleImport.value?.focus());
    };
    lecteur.readAsText(fichier);
};

const fermerImport = () => {
    importModale.value = null;
};

const confirmerImport = () => {
    const formData = new FormData();
    formData.append('fichier', importModale.value.fichier);
    formData.append('portee', portee.value);
    fermerImport();

    importEnCours.value = true;
    unicaenVue.axios.post(
        unicaenVue.url('workflow/administration/import'),
        formData,
        {headers: {'Content-Type': 'multipart/form-data'}}
    ).then(response => {
        etapes.value = response.data;
    }).finally(() => {
        importEnCours.value = false;
    });
};

const saisie = (event) => {
    modAjax(event.currentTarget, (widget) => {
        load();
    });
}

const suppression = (event) => {
    popConfirm(event.currentTarget, (response) => {
        load();
    });
}

const tri = (event) => {
    let etapesCodes = [];
    for (const etapeIndex in etapes.value) {
        etapesCodes.push(etapes.value[etapeIndex].code);
    }

    unicaenVue.axios.post(
        unicaenVue.url('workflow/administration/tri'),
        {etapes: etapesCodes}
    ).then(response => {
        etapes.value = response.data;
    });
}

const perimetreDescription = (perimetre) => {
    switch (perimetre) {
        case 'composante':
            return 'La règle de franchissement est limitée à la composante courante, le franchissement dans les autres structures n\'est pas pris en compte';
        case 'etablissement':
            return 'La règle s\'applique quelle que soit la structure des données servant de base de calcul';
    }
}

const perimetreIcone = (perimetre) => {
    switch (perimetre) {
        case 'composante':
            return 'fas fa-sitemap';
        case 'etablissement':
            return 'fas fa-building-columns';
        default:
            return 'fas fa-globe';
    }
}


onMounted(() => {
    load();
});

</script>
<style scoped>

/* Couleurs issues de la charte OSE (MDB5) : --mdb-primary, --mdb-body-color, --mdb-border-color... */

/* Barre d'outils */
.wf-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    padding: .6rem 1rem;
    margin-bottom: 1rem;
    background: #fff;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: .5em;
}

.wf-toolbar-info {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1.25rem;
    color: var(--mdb-body-color, #4f4f4f);
}

.wf-stat {
    font-weight: 600;
}

.wf-stat i {
    color: var(--mdb-primary, #3b71ca);
    margin-right: .25rem;
}

.wf-hint {
    font-size: .85rem;
    color: var(--mdb-secondary, #9fa6b2);
}

.wf-toolbar-boutons {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
}

/* Aide */
.wf-aide {
    position: relative;
    margin-bottom: 1rem;
    padding: 1rem 1.25rem;
    background: #fff;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-left: 3px solid var(--mdb-info, #54b4d3);
    border-radius: .5em;
    color: var(--mdb-body-color, #4f4f4f);
}

.wf-aide-fermer {
    position: absolute;
    top: .75rem;
    right: .75rem;
}

.wf-aide h4 {
    font-size: 1.15rem;
    margin-bottom: .6rem;
}

.wf-aide h4 i,
.wf-aide h5 i {
    color: var(--mdb-primary, #3b71ca);
    margin-right: .3rem;
}

.wf-aide h5 {
    font-size: .95rem;
    font-weight: 600;
    font-family: inherit;
}

.wf-aide-grille {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr));
    gap: 1rem 2rem;
}

.wf-aide ul {
    padding-left: 1.1rem;
    margin-bottom: .5rem;
}

.wf-aide li {
    margin-bottom: .3rem;
}

.wf-aide-note {
    margin: .4rem 0 0;
    font-size: .9rem;
    color: #73510d;
}

/* Liste des étapes */
.wf-liste {
    display: flex;
    flex-direction: column;
    gap: .5rem;
}

.wf-etape {
    background: #fff;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-left: 3px solid var(--mdb-primary, #3b71ca);
    border-radius: .5em;
    transition: box-shadow .15s ease, background-color .15s ease;
}

.wf-etape:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
}

/* Survol : fond gris clair sur toute la carte de l'étape */
.wf-etape:hover,
.wf-etape:hover .wf-etape-body {
    background-color: #e8e8e8;
}

.wf-etape-surlignee {
    box-shadow: 0 0 0 3px rgba(59, 113, 202, .35);
    transition: box-shadow .3s ease;
}

.wf-ghost {
    opacity: .5;
    background: #dfe7f6;
    border-style: dashed;
}

/* En-tête d'étape */
.wf-etape-header {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .5rem .9rem;
}

.wf-etape:not(.wf-etape-repliee) .wf-etape-header {
    border-bottom: 1px solid var(--mdb-border-color, #e0e0e0);
}

.drag-handle {
    cursor: grab;
    color: var(--mdb-secondary, #9fa6b2);
    font-size: 1.05rem;
    padding: 0 .15rem;
}

.drag-handle:hover {
    color: var(--mdb-primary, #3b71ca);
}

.drag-handle:active {
    cursor: grabbing;
}

.wf-numero {
    flex: 0 0 auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.9rem;
    height: 1.9rem;
    border-radius: .35rem;
    background: var(--mdb-primary, #3b71ca);
    color: #fff;
    font-weight: 600;
    font-size: .85rem;
}

.wf-titre {
    flex: 1 1 auto;
    min-width: 0;
    display: flex;
    flex-direction: column;
    cursor: pointer;
    user-select: none;
}

.wf-libelle {
    font-family: "Open Sans", Helvetica, Arial, sans-serif;
    font-size: 1.1rem;
    font-weight: 400;
    color: var(--mdb-body-color, #4f4f4f);
}

.wf-code {
    font-family: var(--mdb-font-monospace, monospace);
    font-size: .75rem;
    color: var(--mdb-secondary, #9fa6b2);
}

.wf-badges {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    justify-content: flex-end;
}

.wf-actions,
.wf-dep-actions {
    display: flex;
    gap: .1rem;
}

.wf-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.9rem;
    height: 1.9rem;
    border-radius: .375rem;
    color: var(--mdb-primary, #3b71ca);
    text-decoration: none;
}

.wf-action:hover {
    background: #dfe7f6;
}

.wf-action-danger {
    color: var(--mdb-danger, #dc4c64);
}

.wf-action-danger:hover {
    background: #f9e1e5;
}

.wf-chevron {
    transition: transform .2s ease;
}

.wf-chevron-ferme {
    transform: rotate(-90deg);
}

/* Badges : couleurs = classes MDB badge-* de la charte, ici seulement la forme */
.wf-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    border-radius: 1rem;
    font-weight: 500;
    white-space: nowrap;
}

.wf-badge-tous {
    font-style: italic;
}

/* Dans app.css, le .badge de Bootstrap (color: #fff) est chargé après les .badge-* de MDB
   et écrase leur couleur de texte : on réapplique ici les couleurs de texte de la charte. */
.wf-badge.badge-primary   { color: #2c58a0; }
.wf-badge.badge-secondary { color: #40464f; }
.wf-badge.badge-success   { color: #0d6832; }
.wf-badge.badge-info      { color: #1c657d; }
.wf-badge.badge-warning   { color: #73510d; }
.wf-badge.badge-light     { color: #4f4f4f; }

/* Corps : dépendances */
.wf-etape-body {
    padding: .7rem 1rem .85rem 3.6rem;
    background: #fafafa;
    border-bottom-left-radius: .5em;
    border-bottom-right-radius: .5em;
}

.wf-deps-titre i {
    margin-right: .2rem;
}

.wf-deps-titre {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--mdb-secondary, #9fa6b2);
    margin-bottom: .4rem;
}

.wf-deps {
    display: flex;
    flex-direction: column;
    gap: .3rem;
}

.wf-dep {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .6rem;
    padding: .4rem .6rem;
    background: #fff;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: .4rem;
}

.wf-dep-icone {
    color: var(--mdb-secondary, #9fa6b2);
    transform: rotate(90deg) scaleX(-1);
}

.wf-dep-etape {
    flex: 1 1 14rem;
    color: var(--mdb-body-color, #4f4f4f);
}

.wf-phrase strong {
    font-weight: 600;
}

.wf-phrase-details {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .3rem 1rem;
    margin-top: .15rem;
    font-size: .82rem;
    color: #757575;
}

.wf-phrase-details i {
    color: var(--mdb-secondary, #9fa6b2);
    margin-right: .15rem;
}

/* Étapes débloquées */
.wf-debloque {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .35rem;
    margin-top: .7rem;
    font-size: .85rem;
}

.wf-debloque-titre {
    color: #0d6832;
    font-weight: 600;
    margin-right: .2rem;
}

.wf-debloque-etape {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .15rem .55rem .15rem .2rem;
    border-radius: 1rem;
    background: #d6f0e0;
    color: #0d6832;
    text-decoration: none;
}

.wf-debloque-etape:hover {
    background: #c3e8d1;
    color: #0d6832;
}

.wf-debloque-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.3rem;
    height: 1.3rem;
    border-radius: .3rem;
    background: #fff;
    font-size: .72rem;
    font-weight: 700;
}

.wf-dep-attribs {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
}

.wf-dep-inactive {
    opacity: .45;
    background: #f5f5f5;
}

.wf-dep-inactive .wf-dep-etape {
    text-decoration: line-through;
}

.wf-nodep {
    color: var(--mdb-secondary, #9fa6b2);
    font-style: italic;
    font-size: .9rem;
}

.wf-ajout {
    margin-top: .6rem;
}

@media (max-width: 768px) {
    .wf-etape-header {
        flex-wrap: wrap;
    }

    .wf-badges {
        order: 5;
        width: 100%;
        justify-content: flex-start;
        padding-left: 2.75rem;
    }

    .wf-etape-body {
        padding-left: 1rem;
    }
}


/* Modale de confirmation d'import */
.wf-modale-fond {
    position: fixed;
    inset: 0;
    z-index: 1060;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(0, 0, 0, .45);
    outline: none;
    animation: wf-fondu .15s ease-out;
}

.wf-modale {
    width: 100%;
    max-width: 560px;
    background: #fff;
    border-radius: .5em;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .25);
    animation: wf-apparition .2s ease-out;
}

.wf-modale-entete {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--mdb-border-color, #e0e0e0);
}

.wf-modale-entete h4 {
    margin: 0;
    font-weight: 300;
    color: var(--mdb-body-color, #4f4f4f);
}

.wf-modale-entete h4 i {
    color: var(--mdb-primary, #3b71ca);
    margin-right: .4rem;
}

.wf-modale-corps {
    padding: 1.25rem;
    color: var(--mdb-body-color, #4f4f4f);
}

.wf-modale-pied {
    display: flex;
    justify-content: flex-end;
    gap: .5rem;
    padding: .75rem 1.25rem;
    background: #fafafa;
    border-top: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: 0 0 .5em .5em;
}

.wf-fichier {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .5rem .75rem;
    margin-bottom: 1rem;
    background: #fafafa;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: .5em;
    font-weight: 600;
    word-break: break-all;
}

.wf-fichier i {
    color: var(--mdb-primary, #3b71ca);
}

.wf-origine {
    display: grid;
    grid-template-columns: max-content 1fr;
    gap: .35rem 1rem;
    margin-bottom: 1rem;
}

.wf-origine dt {
    font-weight: 400;
    color: var(--mdb-secondary, #9fa6b2);
}

.wf-origine dd {
    margin: 0;
    font-weight: 600;
}

.wf-origine .badge {
    margin-right: .3rem;
}

.wf-origine .badge-primary {
    color: #2c58a0;
}

.wf-origine .badge-info {
    color: #1c657d;
}

.wf-callout {
    display: flex;
    gap: .75rem;
    padding: .75rem 1rem;
    border-radius: .5em;
    border-left: 4px solid;
}

.wf-callout > i {
    margin-top: .2rem;
}

.wf-callout-warning {
    background: #fdf5e6;
    border-color: #e4a11b;
    color: #73510d;
}

.wf-callout-danger {
    background: #fbe7e9;
    border-color: var(--mdb-danger, #dc4c64);
    color: #8a2232;
}

.wf-portee {
    display: flex;
    flex-direction: column;
    gap: .4rem;
    margin-bottom: 1rem;
}

.wf-portee legend {
    font-size: .85rem;
    font-weight: 400;
    color: var(--mdb-secondary, #9fa6b2);
    margin-bottom: .1rem;
}

.wf-portee label {
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    padding: .5rem .75rem;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    border-radius: .5em;
    cursor: pointer;
    transition: border-color .15s, background-color .15s;
}

.wf-portee label:hover {
    border-color: #c4d3ee;
}

.wf-portee input {
    margin-top: .25rem;
    accent-color: var(--mdb-primary, #3b71ca);
}

.wf-portee span {
    display: flex;
    flex-direction: column;
}

.wf-portee small {
    color: var(--mdb-secondary, #9fa6b2);
}

.wf-portee-choisie {
    background: #ebf0fa;
    border-color: var(--mdb-primary, #3b71ca) !important;
}

.wf-portee-choisie strong {
    color: #2c58a0;
}

.wf-annees {
    display: flex;
    align-items: stretch;
    gap: .4rem;
    margin-bottom: 1rem;
}

.wf-annee {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .15rem;
    padding: .5rem .4rem;
    border-radius: .5em;
    border: 1px solid var(--mdb-border-color, #e0e0e0);
    text-align: center;
    font-size: .85rem;
}

.wf-annee span {
    font-weight: 600;
}

.wf-annee small {
    font-size: .75rem;
}

.wf-annee-passee {
    background: #fafafa;
    color: var(--mdb-secondary, #9fa6b2);
}

.wf-annee-cible {
    background: #ebf0fa;
    border-color: #c4d3ee;
    color: #2c58a0;
}

.wf-annee-fleche {
    align-self: center;
    color: var(--mdb-secondary, #9fa6b2);
    font-size: .75rem;
}

@keyframes wf-fondu {
    from { opacity: 0; }
}

@keyframes wf-apparition {
    from { opacity: 0; transform: translateY(-12px); }
}
</style>
