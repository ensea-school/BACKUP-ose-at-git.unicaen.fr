<template>
    <td style="text-align: center"><abbr :title="histoTooltip()"><i class="fa-regular fa-user"></i></abbr></td>
    <td v-if="visibilite.horaires"></td>
    <td v-if="visibilite.horaires"></td>
    <td></td>
    <td v-for="(param,pi) in vhr.params" :key="pi">{{ param }}</td>
    <td v-if="visibilite.motifsNonPaiement">{{ motifNonPaiement() }}</td>
    <td>Référentiel</td>
    <td v-if="visibilite.servicesStatutaire">
        <i v-if="vh.serviceStatutaire" class="fa fa-check text-success"></i>
        <i v-else class="fa fa-xmark text-danger"></i>
    </td>
    <td v-if="visibilite.majorations">{{ floatToString(vhr.ponderationServiceDu) }}</td>
    <td v-if="visibilite.majorations">{{ floatToString(vhr.ponderationServiceCompl) }}</td>
    <td><u-heures :valeur="vhr.heures"></u-heures></td>
    <td>&nbsp;</td>
</template>
<script>

export default {
    name: 'DetailsVolumeHoraireReferentiel',
    components: {},
    props: {
        vhr: {type: Object},
        visibilite: {type: Object},
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
            } else if (this.vhr.nonPayable) {
                return 'Non payable';
            }else {
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