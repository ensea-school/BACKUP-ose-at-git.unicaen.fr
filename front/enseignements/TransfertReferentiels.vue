<template>
    <Modal id="transfert-referentiels-modal" size="lg" scrollable>
    <div class="modal-header">
        <h5 class="modal-title">{{ title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
    </div>
    <div class="modal-body">
        <div v-if="loading || saving" class="alert alert-info text-center py-4" role="status">
            <img :src="loaderUrl" alt="Attente…" />
            <div v-if="saving">Transfert des référentiels en cours… Merci de patienter.</div>
            <div v-else>Chargement des référentiels en cours… Merci de patienter.</div>
        </div>
        <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
        <template v-else>
            <p v-if="mode === 'prevu-to-realise'">Sélectionnez les référentiels prévisionnels validés à reporter comme réalisés.</p>
            <p v-else>Sélectionnez les référentiels validés de l’année précédente à reprendre dans le prévisionnel.</p>
            <div v-if="submitError" class="alert alert-danger" role="alert">{{ submitError }}</div>
            <div v-if="rows.length === 0" class="alert alert-info">Aucun référentiel à transférer n’a été trouvé.</div>
            <div v-else class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead><tr>
                        <th scope="col"><input type="checkbox" :checked="allTransferableSelected" @change="toggleAll"></th>
                        <th scope="col">Référentiel</th><th scope="col" class="text-end">Heures</th><th scope="col">État</th>
                    </tr></thead>
                    <tbody><tr v-for="row in rows" :key="row.id" :class="{'table-danger text-muted': !row.transferable}">
                        <td><input type="checkbox" v-model="selected" :value="row.id" :disabled="!row.transferable" @change="clearSubmitError"></td>
                        <td>{{ row.label }}</td><td class="text-end">{{ formatHours(row.heures) }}</td>
                        <td>
                            <span v-if="!row.transferable">{{ row.reason }}</span>
                            <span v-else-if="row.warning" class="text-warning">{{ getWarningMessage(row) }}</span>
                            <span v-else>Transférable</span>
                        </td>
                    </tr></tbody>
                </table>
            </div>
        </template>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" :disabled="loading || saving || selected.length === 0" @click="submit">
            {{ saving ? 'Transfert en cours…' : 'OK' }}
        </button>
    </div>
    </Modal>
</template>

<script setup>
import {computed, onBeforeUnmount, onMounted, ref} from 'vue';
import Modal from '../Application/UI/Modal.vue';

const props = defineProps({
    intervenantId: {type: Number, required: true},
    loaderUrl: {type: String, required: true},
    replaceExistingPrevisionnel: {type: Boolean, default: false},
});

const mode = ref('');
const rows = ref([]);
const selected = ref([]);
const loading = ref(false);
const saving = ref(false);
const error = ref('');
const submitError = ref('');

const title = computed(() => mode.value === 'prevu-to-realise'
    ? 'Prévisionnel vers réalisé'
    : 'Année précédente vers prévisionnel');

const allTransferableSelected = computed(() => {
    const eligible = rows.value.filter(row => row.transferable);
    return eligible.length > 0 && eligible.every(row => selected.value.includes(row.id));
});

function onDocumentClick(event) {
    const button = event.target.closest('.transfert-referentiels-show');
    if (!button) return;
    event.preventDefault();
    const modal = document.getElementById('transfert-referentiels-modal');
    if (!modal) {
        console.error('La modale de transfert des référentiels est absente.');
        return;
    }
    window.bootstrap.Modal.getOrCreateInstance(modal).show(button);
}

async function load(selectedMode) {
    mode.value = selectedMode;
    rows.value = [];
    selected.value = [];
    error.value = '';
    submitError.value = '';
    loading.value = true;
    try {
        const url = unicaenVue.url('referentiel/transfert-donnees') + '?mode=' + encodeURIComponent(selectedMode)
            + '&intervenant=' + encodeURIComponent(props.intervenantId)
            + '&replaceExistingPrevisionnel=' + (props.replaceExistingPrevisionnel && selectedMode === 'prevu-to-prevu');
        const response = await fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
        const data = await response.json();
        if (!response.ok || data.message) throw new Error(data.message || 'Impossible de charger les référentiels.');
        rows.value = data.rows || [];
        selected.value = rows.value.filter(row => row.transferable && !row.warning).map(row => row.id);
    } catch (e) {
        error.value = e.message || 'Impossible de charger les référentiels.';
    } finally {
        loading.value = false;
    }
}

function onShow(event) {
    if (event.relatedTarget) load(event.relatedTarget.dataset.mode);
}

function toggleAll(event) {
    selected.value = event.target.checked
        ? rows.value.filter(row => row.transferable).map(row => row.id)
        : [];
    clearSubmitError();
}

function clearSubmitError() {
    submitError.value = '';
}

function formatHours(hours) {
    return new Intl.NumberFormat('fr-FR', {maximumFractionDigits: 2}).format(hours || 0);
}

function getWarningMessage(row) {
    const messages = [];
    if (row.existingHours > 0) {
        const hours = formatHours(row.existingHours);
        messages.push(`Remplacera les ${hours} heure${row.existingHours > 1 ? 's' : ''} déjà saisie${row.existingHours > 1 ? 's' : ''}.`);
    }
    if (row.warningMessage) messages.push(row.warningMessage);
    return messages.join(' ') || 'Vérifiez les heures qui seront reprises.';
}

async function submit() {
    saving.value = true;
    submitError.value = '';
    try {
        const response = await fetch(unicaenVue.url('referentiel/transfert-executer'), {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            body: JSON.stringify({
                mode: mode.value,
                intervenant: props.intervenantId,
                ids: selected.value,
                replaceExistingPrevisionnel: props.replaceExistingPrevisionnel && mode.value === 'prevu-to-prevu',
            }),
        });
        const body = await response.text();
        let data;
        try {
            data = JSON.parse(body);
        } catch {
            console.error('Réponse inattendue du serveur :', body);
            throw new Error('Le serveur a renvoyé une réponse inattendue. Consultez la console.');
        }
        if (!response.ok || !data.success) throw new Error(data.message || 'Le transfert a échoué.');
        await hideModal();
        window.location.reload();
    } catch (e) {
        submitError.value = e.message || 'Le transfert a échoué.';
    } finally {
        saving.value = false;
    }
}

function hideModal() {
    const modalElement = document.getElementById('transfert-referentiels-modal');
    if (!modalElement) return Promise.resolve();

    return new Promise(resolve => {
        modalElement.addEventListener('hidden.bs.modal', resolve, {once: true});
        window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
    });
}

onMounted(() => {
    document.addEventListener('click', onDocumentClick);
    document.getElementById('transfert-referentiels-modal')?.addEventListener('show.bs.modal', onShow);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', onDocumentClick);
    document.getElementById('transfert-referentiels-modal')?.removeEventListener('show.bs.modal', onShow);
});
</script>
