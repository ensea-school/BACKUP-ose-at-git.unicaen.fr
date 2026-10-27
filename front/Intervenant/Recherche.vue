<template>
    <h1 class="page-header">Rechercher un intervenant</h1>
    <div class="p-3">

        <h3>
            Saisissez le nom suivi éventuellement du prénom (2 lettres minimum)
        </h3>

        <div class="intervenant-recherche">
            <div class="critere">
                <div>
                    <input
                        id="term"
                        ref="inputRecherche"
                        v-model="searchTerm"
                        autofocus
                        class="form-control input"
                        placeholder="votre recherche..."
                        type="text"
                        @keyup="rechercher"
                    />
                    <br />
                </div>

                <div>
                    <span class="fw-bold">Types d'intervenant : </span>

                    <label class="px-1">
                        <input
                            v-model="checkedTypes"
                            name="type[]"
                            type="checkbox"
                            value="permanent"
                            @change="reload"
                        />
                        Permanent
                    </label>

                    <label class="px-1">
                        <input
                            v-model="checkedTypes"
                            name="type[]"
                            type="checkbox"
                            value="vacataire"
                            @change="reload"
                        />
                        Vacataire
                    </label>

                    <label class="px-1">
                        <input
                            v-model="checkedTypes"
                            name="type[]"
                            type="checkbox"
                            value="etudiant"
                            @change="reload"
                        />
                        Etudiant
                    </label>
                </div>

                <br />
            </div>
        </div>

        <div v-if="loading" class="text-center my-3" role="status">
            <div class="spinner-border">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>

        <table
            v-if="intervenants.length > 0"
            class="table table-bordered table-hover"
        >
            <thead>
            <tr>
                <th style="width: 90px"></th>
                <th>Civilité</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Structure</th>
                <th>Statut</th>
                <th>Date de naissance</th>
                <th v-if="numeroPersonnel">N° Personnel</th>
            </tr>
            </thead>

            <tbody>
            <tr
                v-for="intervenant in intervenants"
                :key="intervenant.code"
                :class="{ 'bg-danger': intervenant.destruction !== null }"
                :title=" intervenant.destruction !== null  ? 'Fiche historisé' : ''"
            >
                <td>
                    <a :href="urlFiche(intervenant.code)">
                        <i class="fas fa-eye"></i>
                        Fiche
                    </a>
                </td>

                <td>{{ intervenant.civilite }}</td>
                <td>{{ intervenant.nom }}</td>
                <td>{{ intervenant.prenom }}</td>
                <td>{{ intervenant.structure }}</td>
                <td>{{ intervenant.statut }}</td>

                <td>
                    <u-date :value="intervenant['date-naissance']" />
                </td>

                <td v-if="numeroPersonnel">{{ intervenant['numero-personnel'] }}</td>
            </tr>
            </tbody>
        </table>

        <table
            v-else-if="intervenants.length === 0 && noResult === 1"
            class="table table-bordered table-hover"
        >
            <thead>
            <tr>
                <th style="width: 90px"></th>
                <th>Civilité</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Structure</th>
                <th>Statut</th>
                <th>Date de naissance</th>
                <th>N° Personnel</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td colspan="8" style="text-align: center">
                    Aucun intervenant trouvé
                </td>
            </tr>
            </tbody>
        </table>

        <div class="mb-3">
            <a
                v-if="canAddIntervenant"
                :href="urlCreation"
                class="btn btn-primary"
            >
                Ajout d'un nouvel intervenant
            </a>
        </div>

        <div>
            <h3 v-if="recents.length > 0">
                Intervenants récemment consultés :
            </h3>
            <table
                v-if="recents.length > 0"
                class="table table-bordered table-hover"
            >
                <thead>
                <tr>
                    <th style="width: 90px"></th>
                    <th>Civilité</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Structure</th>
                    <th>Statut</th>
                    <th>Date de naissance</th>
                    <th v-if="numeroPersonnel">N° Personnel</th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="recent in recents"
                    :key="recent.code"
                    :title=" recent.destruction !== null  ? 'Fiche historisé' : ''"
                >
                    <td>
                        <a :href="urlFiche(recent.code)">
                            <i class="fas fa-eye"></i>
                            Fiche
                        </a>
                    </td>

                    <td>{{ recent.civilite }}</td>
                    <td>{{ recent.nom }}</td>
                    <td>{{ recent.prenom }}</td>
                    <td>{{ recent.structure }}</td>
                    <td>{{ recent.statut }}</td>

                    <td>
                        <u-date :value="recent['date-naissance']" />
                    </td>

                    <td v-if="numeroPersonnel">{{ recent['numero-personnel'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    canAddIntervenant: {
        type: Boolean,
        default: false,
    },
    urlCreation: {
        type: String,
        default: '',
    },
});

const loading = ref(false);
const searchTerm = ref('');
const noResult = ref(0);
const intervenants = ref([]);
const recents = ref([]);
const checkedTypes = ref(['vacataire', 'permanent', 'etudiant',]);
let timer = null;
const inputRecherche = ref(null);
const numeroPersonnel = ref(false);

onMounted(loadRecent);

function rechercher() {
    if (searchTerm.value === '') {
        noResult.value = 0;
        intervenants.value = [];
        return;
    }

    reload();
}

function urlFiche(code) {
    return unicaenVue.url(
        'intervenant/code:' + code + '/voir'
    );
}

async function loadRecent() {
    try {
        const response = await unicaenVue.axios.get(
            unicaenVue.url('intervenant/rechercher-json')
        );

        const datas = response.data;

        // Convertit l'objet en tableau d'intervenants
        recents.value = Array.isArray(datas)
            ? datas
            : Object.values(datas);

        numeroPersonnel.value = recents.value.some(
            recent => Boolean(recent['numero-personnel'])
        );
    } catch (error) {
        console.error(error);
    }
}


function reload() {
    inputRecherche.value?.focus();

    if (timer) {
        clearTimeout(timer);
        timer = null;
    }

    timer = setTimeout(async () => {
        loading.value = true;

        try {
            const response = await unicaenVue.axios.post(
                unicaenVue.url('intervenant/recherche-json'),
                {
                    term: searchTerm.value,
                    types: checkedTypes.value,
                }
            );

            const datas = response.data;
            const datasFiltered = [];

            for (const intervenant of datas) {
                if (
                    intervenant.typeIntervenantCode === 'E' &&
                    checkedTypes.value.includes('vacataire')
                ) {
                    datasFiltered.push(intervenant);
                    continue;
                }

                if (
                    intervenant.typeIntervenantCode === 'P' &&
                    checkedTypes.value.includes('permanent')
                ) {
                    datasFiltered.push(intervenant);
                    continue;
                }

                if (
                    intervenant.typeIntervenantCode === 'S' &&
                    checkedTypes.value.includes('etudiant')
                ) {
                    datasFiltered.push(intervenant);
                    continue;
                }
            }

            intervenants.value = datasFiltered;

            numeroPersonnel.value = datas.some(
                intervenant => Boolean(intervenant['numero-personnel'])
            );

            noResult.value =
                intervenants.value.length === 0 ? 1 : 0;

        } catch (error) {
            console.error(error);
        } finally {
            loading.value = false;
        }
    }, 800);
}


onBeforeUnmount(() => {
    if (timer) {
        clearTimeout(timer);
    }
});

</script>

<style scoped>
</style>

