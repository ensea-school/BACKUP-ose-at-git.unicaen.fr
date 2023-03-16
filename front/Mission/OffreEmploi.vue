<template>
    <div class="card">
        <div class="card-header">
            <h3 style="display:inline">Titre</h3>
            <div class="float-end">

            </div>
        </div>
        <div class="card-body">
            <!-- BODY -->
        </div>
    </div>
</template>

<script>

export default {
    name: "OffreEmploi.vue",
    props: {
        offre: {required: true},
        listeTaux: {required: true},
    },
    data()
    {
        return {
            saisieUrl: Util.url('offre-emploi/saisir/:offre', {offre: this.offre.id}),
            supprimerUrl: Util.url("offre-emploi/supprimer/:offre", {offre: this.offre.id}),
        };
    },
}

</script>

<style scoped>

</style>