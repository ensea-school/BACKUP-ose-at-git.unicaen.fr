<template>
    <td style="text-align: center"><abbr :title="histoTooltip()"><i class="fa-regular fa-user"></i></abbr></td>
    <td></td>
    <td></td>
    <td></td>
    <td v-for="(param,pi) in vhr.params" :key="pi">{{ param }}</td>
    <td>{{ motifNonPaiement() }}</td>
    <td>Référentiel</td>
    <td>{{ floatToString(vhr.ponderationServiceDu) }}</td>
    <td>{{ floatToString(vhr.ponderationServiceCompl) }}</td>
    <td><u-heures :valeur="vhr.heures"></u-heures></td>
    <td>&nbsp;</td>
</template>
<script>

export default {
    name: 'DetailsVolumeHoraireReferentiel',
    components: {},
    props: {
        vhr: {type: Object}
    },
    methods: {
        histoTooltip()
        {
            return "Créé le " + Util.dateToString(this.vhr.histo.creation)
                + " par " + this.vhr.histo.createur.libelle + "\n"
                + "Modifié le " + Util.dateToString(this.vhr.histo.modification)
                + " par " + this.vhr.histo.modificateur.libelle + "\n";
        },
        motifNonPaiement()
        {
            if (this.vhr.motifNonPaiement) {
                return this.vhr.motifNonPaiement.libelle;
            } else {
                return '';
            }
        },
        floatToString(value)
        {
            return Util.floatToString(value);
        }
    },
}

</script>
<style scoped>

</style>