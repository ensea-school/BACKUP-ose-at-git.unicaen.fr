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
        <h5><span class="badge bg-secondary">{{ sap.type }}</span> {{ sap.libelle }}</h5>
        <ul>
            <li v-for="(v,k) in sap.parametres" :key="k">
                {{ k }} : {{ v }}
            </li>
        </ul>

        <div v-for="lap in sap.laps" class="row">
            <div class="col-md-6">
                <!-- Heures à payer -->
                <table class="table table-bordered table-condensed table-extra-condensed">
                    <thead>
                    <tr>
                        <th rowspan="2">Volume horaire</th>
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
                        <td>{{ lap.volumeHoraire }}</td>
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
                <table class="table table-bordered table-condensed table-extra-condensed">
                    <thead>
                    <tr>
                        <th rowspan="2">Id</th>
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
                        <td><span class="badge bg-secondary"
                                  :title="'Centre de couts : '+mep.centreCout+'\nDomaine fonctionnel : '+mep.domaineFonctionnel"
                        >{{ mep.id }}</span></td>
                        <td>{{ mep.periodePaiement }}</td>
                        <td>{{ mep.date }}</td>
                        <td>{{ mep.heures }}</td>
                        <td>{{ mep.heuresAA }}</td>
                        <td>{{ mep.heuresAC }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="!sap.misesEnPaiement && sap.misesEnPaiement.length > 0">
                <h6>Autres mises en paiement en trop</h6>

            <table class="table table-bordered table-condensed table-extra-condensed">
                <thead>
                <tr>
                    <th rowspan="2">Id</th>
                    <th rowspan="2">Centre de couts</th>
                    <th rowspan="2">Domaine fonctionnel</th>
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
                <tr v-for="mep in sap.misesEnPaiement" :key="mep.id">
                    <td>{{ mep.id }}</td>
                    <td>{{mep.centreCout}}</td>
                    <td>{{mep.domaineFonctionnel}}</td>
                    <td>{{ mep.periodePaiement }}</td>
                    <td>{{ mep.date }}</td>
                    <td>{{ mep.heures }}</td>
                    <td>{{ mep.heuresAA }}</td>
                    <td>{{ mep.heuresAC }}</td>
                </tr>
                </tbody>
            </table>
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

</style>