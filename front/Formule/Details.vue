<template>
    Coucou {{ intervenant }}

    {{ data }}
</template>
<script>

export default {
    name: 'Details',
    components: {},
    props: {
        intervenant: {type: Number},
        data: {type: Object},
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