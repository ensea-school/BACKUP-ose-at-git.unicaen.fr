<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },

    discipline: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'save']);

const formulaire = ref({
    sourceCode: '',
    libelleCourt: '',
    libelleLong: '',
    codesCorresp1: '',
    codesCorresp2: '',
    codesCorresp3: '',
    codesCorresp4: ''
});

watch(
    () => props.discipline,
    (discipline) => {
        formulaire.value = discipline
            ? { ...discipline }
            : {
                sourceCode: '',
                libelleCourt: '',
                libelleLong: '',
                codesCorresp1: '',
                codesCorresp2: '',
                codesCorresp3: '',
                codesCorresp4: ''
            };
    },
    { immediate: true }
);

function enregistrer() {

    emit('save', formulaire.value);
    emit('close');
}
</script>

<template>
    <div
        v-if="show"
        class="modal-overlay"
        @click.self="emit('close')"
    >
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h2 class="modal-title">
                        Ajout d'une discipline
                    </h2>

                    <button
                        type="button"
                        class="btn-close"
                        @click="emit('close')"
                    ></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label class="form-label">
                            Code
                        </label>

                        <input
                            v-model="formulaire.sourceCode"
                            type="text"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Libellé court
                        </label>

                        <input
                            v-model="formulaire.libelleCourt"
                            type="text"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Libellé long
                        </label>

                        <input
                            v-model="formulaire.libelleLong"
                            type="text"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Section(s) CNU Apogée
                        </label>

                        <input
                            v-model="formulaire.codesCorresp1"
                            type="text"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Section(s) CNU Harpège
                        </label>

                        <input
                            v-model="formulaire.codesCorresp2"
                            type="text"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Spécialité Harpège
                        </label>

                        <input
                            v-model="formulaire.codesCorresp3"
                            type="text"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Discipline du 2nd degré
                        </label>

                        <input
                            v-model="formulaire.codesCorresp4"
                            type="text"
                            class="form-control"
                        >
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="enregistrer"
                    >
                        Enregistrer
                    </button>

                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="emit('close')"
                    >
                        Fermer
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 99999;
}
</style>