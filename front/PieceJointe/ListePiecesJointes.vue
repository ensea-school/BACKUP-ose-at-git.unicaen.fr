<template>

    <!-- Affichage des pièces jointes post recrutement -->

    <template v-if="hasPiecesAvantRecrutement && datasPiecesJointes?.privileges">
        <div class="tab-pane fade show active" id="avantRecrutement" role="tabpanel"
             aria-labelledby="avantRecrutement-tab">
            <div
                v-for="(message, key) in datasPiecesJointes?.messagesPiecesJointes || []"
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
    <template v-if="hasPiecesApresRecrutement && datasPiecesJointes?.privileges">
        <div class="tab-pane fade show" id="apresRecrutement" role="tabpanel"
             aria-labelledby="apresRecrutement-tab">
            <div
                v-for="(message, key) in datasPiecesJointes?.messagesPiecesJointes || []"
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
        test: 'test'
    })
)

const pieceAvantRecrutement = computed(() =>
    datasPiecesJointes.value?.piecesJointes?.filter(p => !p.demandeApresRecrutement) || []
)

const hasPiecesAvantRecrutement = computed(() =>
    pieceAvantRecrutement.value.length > 0
)

const pieceApresRecrutement = computed(() =>
    datasPiecesJointes.value?.piecesJointes?.filter(p => p.demandeApresRecrutement) || []
)

const hasPiecesApresRecrutement = computed(() =>
    pieceApresRecrutement.value.length > 0
)


function getPiecesJointes()
{

    unicaenVue.axios.get(urlGetPiecesJointes.value).then(response => {
        datasPiecesJointes.value = response.data
        console.log(pieceAvantRecrutement.value);

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
<script setup lang="ts">
</script>