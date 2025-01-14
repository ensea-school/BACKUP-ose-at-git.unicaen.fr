<template>
    <h6>
        Référentiel
        <b-badge>{{ referentiel.structure.libelle }}</b-badge>
    </h6>
    <div class="cartridge">
        <span>{{ referentiel.fonction.libelle }}</span>
        <span v-if="referentiel.description">{{ referentiel.description }}</span>
    </div>
</template>
<script>

export default {
    name: 'DetailsServiceEnseignement',
    components: {},
    props: {
        referentiel: {type: Object}
    },
}

</script>
<style scoped>

</style>