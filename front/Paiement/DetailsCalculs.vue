<template>
    <h2>Paramètres généraux ou bien selon le statut</h2>
    <table class="table table-bordered table-condensed table-extra-condensed">
        <tr>
            <th>Paramètre général</th>
            <th>Valeur</th>
        </tr>
        <tr v-for="(valeur, nom) in parametres" :key="nom">
            <th>{{ nom }}</th>
            <td>{{ valeur }}</td>
        </tr>
    </table>

    <h2>Services à payer</h2>

    <div v-for="(sap,ksap) in servicesAPayer" :key="ksap">
        <div class="card">
            <div class="card-header">
                <span class="badge bg-primary">{{ sap.type }}</span> {{ sap.libelle }}
            </div>
            <div class="card-body">
                <ul>
                    <li v-for="(v,k) in sap.parametres" :key="k">
                        {{ k }} : {{ v }}
                    </li>
                </ul>


                <div v-for="lap in sap.laps" class="row lap">
                    <div class="col-md-6">
                        <!-- Heures à payer -->
                        <h6>Heures à payer</h6>
                        <table class="table table-bordered table-xs">
                            <thead>
                            <tr>
                                <th rowspan="2" v-if="sap.type!='Référentiel'">Volume horaire</th>
                                <th rowspan="2" v-else>Volumes horaires</th>
                                <th colspan="2">Taux de rému.</th>
                                <th colspan="3">Heures</th>
                            </tr>
                            <tr>
                                <th>Nom</th>
                                <th>Valeur</th>
                                <th>Total</th>
                                <th>AA</th>
                                <th>AC</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><abbr class="badge bg-secondary"
                                                                         :title="lap.volumeHoraireHisto">{{
                                        lap.volumeHoraireId
                                    }}</abbr> {{
                                        lap.volumeHoraire
                                    }}
                                </td>
                                <td>{{ lap.tauxRemu }}</td>
                                <td>{{ lap.tauxValeur }}€</td>
                                <td>{{ lap.heures }}</td>
                                <td>{{ lap.heuresAA }}</td>
                                <td>{{ lap.heuresAC }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <!-- Paiements -->
                        <h6><abbr
                            title="Les demandes de mise en paiement n'ont pas de période ni de date de paiement associées, contrairement aux mises en paiement">(Demandes
                            de) Mises en paiement</abbr> correspondantes</h6>
                        <table class="table table-bordered table-xs">
                            <thead>
                            <tr>
                                <th rowspan="2">Id</th>
                                <th rowspan="2">Centre de coûts</th>
                                <th colspan="2">Période</th>
                                <th colspan="3">Heures</th>
                            </tr>
                            <tr>
                                <th>Mois</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>AA</th>
                                <th>AC</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="mep in lap.misesEnPaiement" :key="mep.id">
                                <td><abbr class="badge bg-secondary"
                                          :title="'Nombre d\'heures total concernées : '+mep.heuresTotal+
                                  '\nDomaine fonctionnel : '+mep.domaineFonctionnel
                                  +'\n'+mep.historique"
                                >{{ mep.id }}</abbr></td>
                                <td><abbr :title="mep.centreCoutLibelle">{{ mep.centreCoutCode }}</abbr></td>
                                <td>{{ mep.periodePaiement }}</td>
                                <td>{{ mep.date }}</td>
                                <td>{{ mep.heures }}</td>
                                <td>{{ mep.heuresAA }}</td>
                                <td>{{ mep.heuresAC }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-if="lap.heuresRestantes != '0,00'">
                            <span
                                class="badge bg-info">{{ lap.heuresRestantes }}h n'ont pas été demandées en paiement</span>
                        </div>
                    </div>
                </div>

                <div v-if="sap.misesEnPaiement && sap.misesEnPaiement.length > 0" class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <h6><abbr
                            title="Les demandes de mise en paiement n'ont pas de période ni de date de paiement associées, contrairement aux mises en paiement">(Demandes
                            de) Mises en paiement</abbr> en trop</h6>
                        <table class="table table-bordered table-xs">
                            <thead class="bg-danger">
                            <tr>
                                <th rowspan="2">Id</th>
                                <th rowspan="2">Centre de coûts</th>
                                <th colspan="2">Période</th>
                                <th rowspan="2">Heures</th>
                            </tr>
                            <tr>
                                <th>Mois</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="mep in sap.misesEnPaiement" :key="mep.id">
                                <td><abbr class="badge bg-danger"
                                          :title="'Domaine fonctionnel : '+mep.domaineFonctionnel
                                  +'\n'+mep.historique"
                                >{{ mep.id }}</abbr></td>
                                <td><abbr :title="mep.centreCoutLibelle">{{ mep.centreCoutCode }}</abbr></td>
                                <td>{{ mep.periodePaiement }}</td>
                                <td>{{ mep.date }}</td>
                                <td>{{ mep.heures }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    components: {},
    props: {
        parametres: {type: Array, required: true},
        servicesAPayer: {type: Array, required: true},
    },
}
</script>
<style scoped>

.table {
    margin-bottom:1px;
}

.lap {
    padding-top: .5em;
    padding-bottom: .5em;
}

.lap:hover {
    background-color:#fff9de;
}

</style>