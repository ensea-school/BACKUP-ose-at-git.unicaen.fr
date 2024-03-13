<template>
    <h1>Formulator</h1>


    <a class="btn btn-secondary" :href="this.indexUrl()"><i class="fas fa-rotate-left"></i> Retour à l'administration des formules</a>
</template>
<script>

export default {
    name: 'Formulator',
    components: {},
    props: {

    },
    methods: {
        indexUrl()
        {
            return unicaenVue.url('formule/administration')
        }
    },
}

</script>
<style scoped>

</style>