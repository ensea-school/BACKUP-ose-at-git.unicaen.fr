<template>
    <h6>
        Enseignement
        <span v-if="!enseignement.elementPedagogique"> extérieur</span>
        <span v-if="enseignement.elementPedagogique">
            <b-badge>{{ enseignement.structure.libelle }}</b-badge>
            <b-badge variant="light" v-if="enseignement.tauxFi > 0">{{ tauxFi }}</b-badge>
            <b-badge variant="light" v-if="enseignement.tauxFa > 0">{{ tauxFa }}</b-badge>
            <b-badge variant="light" v-if="enseignement.tauxFc > 0">{{ tauxFc }}</b-badge>
        </span>
    </h6>
    <div v-if="enseignement.elementPedagogique" class="cartridge">
        <span><span class="code">{{ enseignement.etape.code }}</span> {{ enseignement.etape.libelle }}</span>
        <span><span class="code">{{ enseignement.elementPedagogique.code }}</span> {{ enseignement.elementPedagogique.libelle }}</span>
    </div>
    <div v-else class="cartridge gray">
        <span>{{ enseignement.etablissement.libelle }}</span>
        <span v-if="enseignement.description">{{ enseignement.description }}</span>
    </div>
</template>
<script>

export default {
    name: 'DetailsServiceEnseignement',
    components: {},
    props: {
        enseignement: {type: Object}
    },
    computed: {
        tauxFi()
        {
            return Util.floatToString(this.enseignement.tauxFi * 100) + '% FI';
        },
        tauxFa()
        {
            return Util.floatToString(this.enseignement.tauxFa * 100) + '% FA';
        },
        tauxFc()
        {
            return Util.floatToString(this.enseignement.tauxFc * 100) + '% FC';
        },
    }
}

</script>
<style scoped>

.code {
    font-weight: 400;
    font-size: 7pt;
}

</style>