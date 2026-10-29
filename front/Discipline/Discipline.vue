<script setup>
import { ref, computed, onMounted } from 'vue';
import DisciplineForm from './DisciplineForm/DisciplineForm.vue';

const disciplines = ref([]);
const entetes = ref([]);

const pageCourante = ref(1);
const lignesParPage = ref(10);
const recherche = ref('');
const totalRows = ref(0);

const disciplineEnEdition = ref(null);
const disciplineEnModal = ref(null);
const showModal = ref(false);

const { canEdit } = defineProps({
    canEdit: Boolean
});

const fields = computed(() => [
    { key: 'sourceCode', label: 'Code' },
    { key: 'libelleCourt', label: 'Libellé court' },
    { key: 'libelleLong', label: 'Libellé long' },
    { key: 'codesCorresp1', label: entetes.value[1] },
    { key: 'codesCorresp2', label: entetes.value[2] },
    { key: 'codesCorresp3', label: entetes.value[3] },
    { key: 'codesCorresp4', label: entetes.value[4] },
    { key: 'actions', label: 'Src/Actions' }
]);

const lignesParPageOptions = [10, 25, 50, 100];

async function chargerDisciplines(pageId = null) {
    try {
        const response = await unicaenVue.axios.get(
            unicaenVue.url('discipline/index-json')
        );

        entetes.value = response.data[0][0];
        disciplines.value = response.data[0][1];
        totalRows.value = disciplines.value.length;

        if (pageId) {
            const index = disciplines.value.findIndex(
                discipline => discipline.id === pageId
            );

            if (index !== -1) {
                pageCourante.value =
                    Math.floor(index / lignesParPage.value) + 1;
            }
        }
    } catch (error) {
        console.error(error);
    }
}

function filtrerDisciplines(items) {
    totalRows.value = items.length;
    pageCourante.value = 1;
}

async function sauvegarderDiscipline(discipline) {
    if (!discipline) {
        return false;
    }

    const data = new URLSearchParams();

    if (discipline.id) {
        data.append('id', discipline.id);
    }

    data.append('source-code', discipline.sourceCode ?? '');
    data.append('libelle-court', discipline.libelleCourt ?? '');
    data.append('libelle-long', discipline.libelleLong ?? '');
    data.append('codes-corresp-1', discipline.codesCorresp1 ?? '');
    data.append('codes-corresp-2', discipline.codesCorresp2 ?? '');
    data.append('codes-corresp-3', discipline.codesCorresp3 ?? '');
    data.append('codes-corresp-4', discipline.codesCorresp4 ?? '');

    try {
        const response = await unicaenVue.axios.post(
            unicaenVue.url('discipline/modifier-json'),
            data
        );
        if (!response.data.success) {
            console.error(response.data.errors);
            return false;
        }else{
            await chargerDisciplines(discipline.id);
            return true;
        }

    } catch (error) {
        console.error(error);
        return false;
    }
}

async function supprimerDiscipline(discipline) {
    if (!discipline?.id) return;

    if (
        !confirm(
            `Voulez-vous vraiment supprimer la discipline "${discipline.libelleCourt}" ?`
        )
    ) {
        return;
    }

    try {
        const data = new URLSearchParams();
        data.append('id', discipline.id);

        const response = await unicaenVue.axios.post(
            unicaenVue.url('discipline/supprimer-json'),
            data
        );

        if (response.data.success) {
            await chargerDisciplines();
        }
    } catch (error) {
        console.error(error);
    }
}

function ouvrirModalAjout() {
    disciplineEnModal.value = {
        sourceCode: '',
        libelleCourt: '',
        libelleLong: '',
        codesCorresp1: '',
        codesCorresp2: '',
        codesCorresp3: '',
        codesCorresp4: ''
    };

    showModal.value = true;
}

function fermerModal() {
    showModal.value = false;
    disciplineEnModal.value = null;
}

function truncCodesCorresp(value) {
    if (!value) return '';

    const texte = value.replaceAll(',', ', ');

    return texte.length > 23
        ? `${texte.substring(0, 22)}...`
        : texte;
}

async function enregistrer() {
    if (await sauvegarderDiscipline(disciplineEnEdition.value)) {
        disciplineEnEdition.value = null;
    }
}

async function enregistrerAjout(discipline) {
    if (await sauvegarderDiscipline(discipline)) {
        fermerModal();
    }
}
onMounted(async()=> {
    await chargerDisciplines()
})
</script>

<template>
    <h1 class="page-header">
        Gestion des disciplines
    </h1>

    <div class="d-flex justify-content-between align-items-center my-2">
        <div class="d-flex align-items-center">
            <span class="me-2">Afficher</span>

            <b-form-select
                v-model="lignesParPage"
                :options="lignesParPageOptions"
                size="sm"
                class="w-auto"
            />

            <span class="ms-2">éléments</span>
        </div>

        <b-form-input
            v-model="recherche"
            type="search"
            placeholder="Rechercher..."
            size="sm"
            class="w-auto"
        />
    </div>

    <b-table
        :items="disciplines"
        :fields="fields"
        :filter="recherche"
        :per-page="lignesParPage"
        :current-page="pageCourante"
        @filtered="filtrerDisciplines"
        bordered
        class="table b-table mb-0"
        responsive
    >
        <template #cell(sourceCode)="data">
            <div style="text-align: center">
                <b-form-textarea
                    v-if="disciplineEnEdition?.id === data.item.id"
                    v-model="disciplineEnEdition.sourceCode"
                    size="sm"
                    rows="1"
                    max-rows="10"
                    no-resize
                />

                <span v-else>
                    {{ data.value }}
                </span>
            </div>
        </template>

        <template #cell(libelleCourt)="data">
            <b-form-textarea
                v-if="disciplineEnEdition?.id === data.item.id"
                v-model="disciplineEnEdition.libelleCourt"
                rows="1"
                max-rows="10"
                no-resize
            />

            <span v-else>
                {{ data.value }}
            </span>
        </template>

        <template #cell(libelleLong)="data">
            <b-form-textarea
                v-if="disciplineEnEdition?.id === data.item.id"
                v-model="disciplineEnEdition.libelleLong"
                rows="1"
                max-rows="10"
                no-resize
            />

            <span v-else>
                {{ data.value }}
            </span>
        </template>

        <template
            v-for="champ in [
                'codesCorresp1',
                'codesCorresp2',
                'codesCorresp3'
            ]"
            #[`cell(${champ})`]="data"
        >
            <b-form-textarea
                v-if="disciplineEnEdition?.id === data.item.id"
                v-model="disciplineEnEdition[champ]"
                size="sm"
                rows="1"
                max-rows="10"
                no-resize
            />

            <span
                v-else
                :title="data.value"
            >
                {{ truncCodesCorresp(data.value) }}
            </span>
        </template>

        <template #cell(codesCorresp4)="data">
            <b-form-textarea
                v-if="disciplineEnEdition?.id === data.item.id"
                v-model="disciplineEnEdition.codesCorresp4"
                rows="1"
                max-rows="10"
                no-resize
            />

            <span
                v-else
                :title="data.value"
            >
                {{ truncCodesCorresp(data.value) }}
            </span>
        </template>

        <template #cell(actions)="data">
            <template v-if="disciplineEnEdition?.id === data.item.id">
                <div style="text-align: center">
                    <b-button
                        variant="link"
                        style="padding: 2px"
                        @click="enregistrer"
                    >
                        <i
                            class="fas fa-check"
                            style="font-size: 1.2rem;"
                        />
                    </b-button>

                    <b-button
                        variant="link"
                        style="padding: 2px"
                        @click="disciplineEnEdition = null"
                    >
                        <i
                            class="fas fa-xmark"
                            style="font-size: 1.2rem;"
                        />
                    </b-button>
                </div>
            </template>

            <template
                v-else-if="!data.item.sourceImportable && canEdit"
            >
                <div style="text-align: center">
                    <b-button
                        variant="link"
                        style="padding: 2px"
                        @click="disciplineEnEdition = { ...data.item }"
                    >
                        <i
                            class="fas fa-pen-to-square"
                            style="font-size: 0.9rem;"
                        />
                    </b-button>

                    <b-button
                        variant="link"
                        style="padding: 2px"
                        @click="supprimerDiscipline(data.item)"
                    >
                        <i
                            class="fas fa-trash-can"
                            style="font-size: 0.9rem;"
                        />
                    </b-button>
                </div>
            </template>

            <span v-else>
                {{ data.item.source }}
            </span>
        </template>
    </b-table>

    <div class="d-flex justify-content-between align-items-center mb-1">
        <span>
            Affichage de l'élément
            {{ totalRows ? (pageCourante - 1) * lignesParPage + 1 : 0 }}
            à
            {{ Math.min(pageCourante * lignesParPage, totalRows) }}
            sur
            {{ totalRows }}
        </span>

        <b-pagination
            v-model="pageCourante"
            :total-rows="totalRows"
            :per-page="lignesParPage"
            first-text="Premier"
            prev-text="Précédent"
            next-text="Suivant"
            last-text="Dernier"
            class="mb-0"
        />
    </div>

    <b-button
        v-if="canEdit"
        variant="primary"
        size="sm"
        @click="ouvrirModalAjout"
    >
        <i class="fas fa-plus" />
        Ajout d'une nouvelle discipline
    </b-button>

    <DisciplineForm
        :show="showModal"
        :discipline="disciplineEnModal"
        @close="fermerModal"
        @save="enregistrerAjout"
    />
</template>

<style scoped>
.table th {
    font-weight: bold;
}
</style>