<template>
    <div class="col">
        <div class="card h-100">
            <div class="card-header">
                <h4> {{ offre.titre }}</h4>
                <span class="badge bg-success">En cours</span>&nbsp;
                <span class="badge bg-info">{{ offre.nombreHeures }} heure(s)</span>&nbsp;
                <span class="badge bg-info">{{ offre.nombrePostes }} poste(s)</span>&nbsp;
            </div>

            <div class="card-body">

                {{ offre.description }}

            </div>
            <div class="card-footer">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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
    },
    data()
    {
        return {

            saisirUrl: Util.url('offre-emploi/saisir/:offre', {offre: this.offre.id}),
            supprimerUrl: Util.url("offre-emploi/supprimer/:offre", {offre: this.offre.id}),
            validerUrl: Util.url('mission/valider/:mission', {offre: this.offre.id}),
            devaliderUrl: Util.url('mission/devalider/:mission', {offre: this.offre.id}),
        };
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

    },

}

</script>

<style scoped>

</style>