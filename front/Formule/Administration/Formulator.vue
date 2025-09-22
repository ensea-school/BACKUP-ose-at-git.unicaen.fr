<template>
    <h1>Formulator</h1>

    <div class="mb-2">
        <a class="btn btn-primary" :href="testUrl(test)">Affichage du jeu de test</a>
        &nbsp;
        <a class="btn btn-secondary" :href="this.indexUrl()"><i class="fas fa-rotate-left"></i> Retour à l'administration des formules</a>
    </div>

    <div v-if="error">
        <pre class="alert alert-danger">{{ error }}</pre>
    </div>
    <div v-else>
        <div class="alert alert-success">Formule bien intégrée</div>
    </div>

    <div v-if="phpClass">
        <h3>Code généré :</h3>
        <div v-html="phpClass"></div>
    </div>

</template>
<script>

export default {
    name: 'Formulator',
    components: {},
    props: {
        phpClass: {type: String},
        error: {type: String},
        test: {type: Number},
    },
    methods: {
        indexUrl()
        {
            return unicaenVue.url('formule/administration')
        },
        testUrl(id)
        {
            return unicaenVue.url('formule-test/saisir/:id', {id: id});
        },
    },
}

</script>
<style scoped>

</style>