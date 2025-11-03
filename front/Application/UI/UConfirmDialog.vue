<template>
    <div
        class="modal fade"
        tabindex="-1"
        ref="modalEl"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">{{ message }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" @click="confirmAction">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from "bootstrap";

export default {

    data() {
        return {
            modal: null,      // Instance Bootstrap Modal
            resolveFn: null,  // Fonction pour résoudre la promesse
            message: "",      // Message affiché
            title: "Confirmation", // Titre modal
        };
    },
    mounted() {
        // Crée l'instance Bootstrap Modal
        this.modal = new Modal(this.$refs.modalEl, {
            backdrop: "static",
            keyboard: true,
        });

        // Si la modal est fermée manuellement → annuler la promesse
        this.$refs.modalEl.addEventListener("hidden.bs.modal", () => {
            if (this.resolveFn) {
                this.resolveFn(false);
                this.resolveFn = null;
            }
        });
    },
    methods: {
        /**
         * Ouvre la modal et retourne une Promise
         */
        open(message, title = "Confirmation") {
            this.message = message;
            this.title = title;
            this.modal.show();

            return new Promise((resolve) => {
                this.resolveFn = resolve;
            });
        },

        /**
         * Confirme l'action et résout la Promise
         */
        confirmAction() {
            if (this.resolveFn) this.resolveFn(true);
            this.resolveFn = null;
            this.modal.hide();
        },
    },
};
</script>
