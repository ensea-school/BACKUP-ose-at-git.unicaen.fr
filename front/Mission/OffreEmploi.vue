<template>
    <div class="col" v-if="isPublic">
        <div class="card h-100">
            <div class="card-header">
                <h4> {{ offre.titre }}</h4>
                <span v-if="offre.validation" class="badge rounded-pill bg-success">Valider le <u-date
                    :value="offre.validation.histoCreation"/> par {{ offre.validation.histoCreateur.displayName }}&nbsp;</span>
                <span v-if="!offre.validation" class="badge rounded-pill bg-warning">En attente de validation par la DRH</span>&nbsp;
                <span class="badge rounded-pill bg-info">{{ offre.nombreHeures }} heure(s)</span>&nbsp;
                <span class="badge rounded-pill bg-info">{{ offre.nombrePostes }} poste(s)</span>

            </div>

            <div class="card-body">
                <p class="bg-light" style="padding:5px;">
                    <b>Crée le : </b>
                    <u-date :value="offre.histoCreation"/>
                    par {{ offre.histoCreateur.displayName }}<br/>
                    <b>Période à pourvoir : </b>du
                    <u-date :value="offre.dateDebut"/>
                    au
                    <u-date :value="offre.dateFin"/>
                    <br/>
                    <b>Demandé par la composante :</b> {{ offre.structure.libelle }}

                </p>
                {{ offre.description }}

            </div>
            <div class="card-footer">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a v-if="offre.validation"
                       :href="devaliderUrl"
                       class="btn btn-danger"
                       @click.prevent="devalider">Devalider</a>
                    <a v-if="!offre.validation"
                       :href="validerUrl"
                       class="btn btn-success"
                       @click.prevent="valider">Valider</a>
                    <a :href="saisirUrl"
                       class="btn btn-primary"
                       @click.prevent="saisir">Modifier</a>
                    <a :href="supprimerUrl"
                       class="btn btn-danger"
                       data-title="Suppression de l'offre"
                       data-content="Êtes-vous sur de vouloir supprimer l'offre ?"
                       @click.prevent="supprimer">Supprimer</a>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "OffreEmploi.vue",
    props: {
        offre: {required: true},
        public: {type: Boolean, required: true}
    },
    data()
    {
        console.log(this.offre.histoCreation);
        console.log(this.public);
        return {

            saisirUrl: Util.url('offre-emploi/saisir/:offre', {offre: this.offre.id}),
            supprimerUrl: Util.url("offre-emploi/supprimer/:offre", {offre: this.offre.id}),
            validerUrl: Util.url('offre-emploi/valider/:offre', {offre: this.offre.id}),
            devaliderUrl: Util.url('offre-emploi/devalider/:offre', {offre: this.offre.id}),

        };
    },
    computed: {
        isPublic: function () {
            if (this.public === false) {
                return true;
            } else if (this.public === true && this.offre.validation != null) {
                return true;
            } else {
                return false;
            }
        }
    },

    methods: {
        saisir(event)
        {
            console.log(this.saisirUrl);
            modAjax(event.target, (widget) => {
                this.refresh();
            });
        },
        refresh()
        {
            axios.get(
                Util.url("offre-emploi/get/:offreEmploi", {offreEmploi: this.offre.id})
            ).then(response => {
                console.log(response.data);
                this.$emit('refresh', response.data);
            });
        },
        supprimer(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('supprimer', this.offre);
            });
        },
        valider(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('refresh', response.data);
            });
        },
        devalider(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('refresh', response.data);
            });
        },

    },

}

</script>

<style scoped>

</style>