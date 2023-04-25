<template>
    <span class="heures" v-html="affichage"></span>
</template>

<script>
export default {
    props: {
        valeur: {required: true, type: Float64Array},
    },
    computed: {
        affichage: function () {
            return Util.formattedHeures(this.valeur, true)
        }
    }
}
</script>

<style scoped>

</style>