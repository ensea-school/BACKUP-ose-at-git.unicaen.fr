<template>
    <td style="text-align: center"><abbr :title="histoTooltip()"><i class="fa-regular fa-user"></i></abbr></td>
    <td v-if="visibilite.horaires">{{ vh.horaireDebut }}</td>
    <td v-if="visibilite.horaires">{{ vh.horaireFin }}</td>
    <td style="text-align: center">{{ vh.periode.libelle }}</td>
    <td v-for="(param,pi) in vh.params" :key="pi">{{ param }}</td>
    <td v-if="visibilite.motifsNonPaiement">{{ motifNonPaiement() }}</td>
    <td><abbr :title="typeInterventionTooltip()">{{ vh.typeIntervention.code }}</abbr></td>
    <td v-if="visibilite.servicesStatutaire">
        <i v-if="vh.serviceStatutaire" class="fa fa-check text-success"></i>
        <i v-else class="fa fa-xmark text-danger"></i>
    </td>
    <td v-if="visibilite.majorations">{{ floatToString(vh.ponderationServiceDu) }}</td>
    <td v-if="visibilite.majorations">{{ floatToString(vh.ponderationServiceCompl) }}</td>
    <td>
        <u-heures :valeur="vh.heures"></u-heures>
    </td>
    <td>&nbsp;</td>
</template>
<script>

export default {
    name: 'DetailsVolumeHoraireEnseignement',
    components: {},
    props: {
        vh: {type: Object},
        visibilite: {type: Object},
    },
    methods: {
        histoTooltip()
        {
            return "Créé le " + Util.dateToString(this.vh.histo.creation)
                + " par " + this.vh.histo.createur.libelle + "\n"
                + "Modifié le " + Util.dateToString(this.vh.histo.modification)
                + " par " + this.vh.histo.modificateur.libelle + "\n";
        },
        typeInterventionTooltip()
        {
            return "Taux en service : " + Util.floatToString(this.vh.tauxServiceDu) + "\n"
                + "Taux en HC : " + Util.floatToString(this.vh.tauxServiceCompl) + "\n";
        },
        motifNonPaiement()
        {
            if (this.vh.motifNonPaiement) {
                return this.vh.motifNonPaiement.libelle;
            } else if (this.vh.nonPayable) {
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