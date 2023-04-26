<template>
    <span class="heures" v-html="affichage"></span>
</template>

<script>
export default {
    props: {
        valeur: {required: true, type: Number},
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