<template>
    <td style="text-align: center"><abbr :title="histoTooltip()"><i class="fa-regular fa-user"></i></abbr></td>
    <td>{{ vh.horaireDebut }}</td>
    <td>{{ vh.horaireFin }}</td>
    <td style="text-align: center">{{ vh.periode.libelle }}</td>
    <td v-for="(param,pi) in vh.params" :key="pi">{{ param }}</td>
    <td>{{ motifNonPaiement() }}</td>
    <td><abbr :title="typeInterventionTooltip()">{{ vh.typeIntervention.code }}</abbr></td>
    <td>{{ floatToString(vh.ponderationServiceDu) }}</td>
    <td>{{ floatToString(vh.ponderationServiceCompl) }}</td>
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
        vh: {type: Object}
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