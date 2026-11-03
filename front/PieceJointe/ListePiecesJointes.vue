<template>

    <!-- Affichage des pièces jointes post recrutement -->

    <template v-if="hasPiecesAvantRecrutement && datasPiecesJointes?.privileges">
        <div class="tab-pane fade show active" id="avantRecrutement" role="tabpanel"
             aria-labelledby="avantRecrutement-tab">
            <div
                v-for="(message, key) in messAvantRecrutement || []"
                :key="'msg-' + key"
                :class="'mt-3 messenger alert alert-dismissible alert-' + message.type">
                <span class="fas fa-info-circle"></span>
                {{ message.text }}
                <button
                    type="button"
                    class="btn-close alert-dismissible"
                    title="Fermer cette alerte"
                    data-bs-dismiss="alert">
                </button>
            </div>
            <div class="card">
                <div class="card-header text-light bg-success">
                    <h3>Pièces justificatives</h3>
                </div>
                <div class="card-body">

                    <pieceJointe
                        v-for="pieceJointe in pieceAvantRecrutement"
                        :key="pieceJointe.id"
                        :datas="pieceJointe"
                        :privileges="datasPiecesJointes.privileges"
                        :intervenant="intervenant"
                        @refresh="getPiecesJointes"
                    />
                </div>
            </div>
        </div>
    </template>

    <!-- Affichage des pièces jointes post recrutement -->
    <template v-if="hasPiecesApresRecrutement && datasPiecesJointes?.privileges?.canVisualiserComp">
        <div class="tab-pane fade show" id="apresRecrutement" role="tabpanel"
             aria-labelledby="apresRecrutement-tab">
            <div v-if="!pjComplAccessible" class="mt-3 messenger alert alert-info">
                <span class="fas fa-info-circle"></span>
                L'étape des pièces justificatives complémentaires n'est pas encore accessible :
                seules les pièces déjà fournies sont affichées.
            </div>
            <div
                v-for="(message, key) in messApresRecrutement || []"
                :key="'msg-' + key"
                :class="'mt-3 messenger alert alert-dismissible alert-' + message.type">
                <span class="fas fa-info-circle"></span>
                {{ message.text }}
                <button
                    type="button"
                    class="btn-close alert-dismissible"
                    title="Fermer cette alerte"
                    data-bs-dismiss="alert">
                </button>
            </div>

            <div class="card">
                <div class="card-header text-light bg-success">
                    <h3>Pièces justificatives complémentaires</h3>
                </div>
                <div class="card-body">

                    <PieceJointe
                        v-for="pieceJointe in pieceApresRecrutement"
                        :key="pieceJointe.id"
                        :datas="pieceJointe"
                        :privileges="datasPiecesJointes.privileges"
                        :intervenant="intervenant"
                        @refresh="getPiecesJointes"
                    />
                </div>
            </div>
        </div>
    </template>

</template>

<script setup>

import {ref, onMounted, computed} from 'vue'
import PieceJointe from './PieceJointe.vue'

const props = defineProps(['intervenant']);

// Refs
const datasPiecesJointes = ref(null)

const urlGetPiecesJointes = computed(() =>
    unicaenVue.url('piece-jointe/intervenant/:intervenant/get-pieces-jointes', {
        intervenant: props.intervenant,
    })
)

const pieceAvantRecrutement = computed(() =>
    datasPiecesJointes.value?.piecesJointes?.filter(p => !p.demandeApresRecrutement) || []
)

const messAvantRecrutement = computed(() =>
    datasPiecesJointes.value?.messagesPiecesJointes?.filter(p => p.avantRecrutement) || []
)

const hasPiecesAvantRecrutement = computed(() =>
    pieceAvantRecrutement.value.length > 0
)

const pjComplAccessible = computed(() =>
    datasPiecesJointes.value?.etapes?.pjComplAccessible ?? false
)

// Si l'étape PJ complémentaires n'est pas (encore) atteignable dans le workflow, on n'affiche que les pièces
// déjà fournies, afin qu'elles restent consultables, modifiables et supprimables.
const pieceApresRecrutement = computed(() =>
    datasPiecesJointes.value?.piecesJointes?.filter(p =>
        p.demandeApresRecrutement && (pjComplAccessible.value || p.pieceJointe)
    ) || []
)

// Les messages d'état (pièces manquantes, etc.) n'ont de sens que si l'étape est accessible
const messApresRecrutement = computed(() =>
    pjComplAccessible.value
        ? datasPiecesJointes.value?.messagesPiecesJointes?.filter(p => !p.avantRecrutement) || []
        : []
)


const hasPiecesApresRecrutement = computed(() =>
    pieceApresRecrutement.value.length > 0
)


function getPiecesJointes()
{

    unicaenVue.axios.get(urlGetPiecesJointes.value).then(response => {
        datasPiecesJointes.value = response.data
    }).catch(error => {
        console.error(error)
    })
}

// Initialisation
onMounted(() => {
    getPiecesJointes()
})
</script>

<style scoped>

</style>
<script setup lang="ts">
</script>
