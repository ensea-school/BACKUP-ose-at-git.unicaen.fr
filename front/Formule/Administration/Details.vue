<template>
    <h1>Code source de {{ formule.code }}</h1>

    <div v-html="phpClass" class="code"></div>

    <div class="mb-2">
        <a class="btn btn-secondary" :href="this.indexUrl()"><i class="fas fa-rotate-left"></i> Retour à
            l'administration des formules</a>
    </div>
</template>
<script>

export default {
    name: 'Details',
    components: {},
    props: {
        formule: {type: Object},
        phpClass: {type: String},
    },
    methods: {
        indexUrl()
        {
            return unicaenVue.url('formule/administration')
        },
    },
}

</script>
<style scoped>

</style>