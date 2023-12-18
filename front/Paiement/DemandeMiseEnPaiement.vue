<template>

    <div v-for="(structure, code) in datasServiceAPayer ">
        <div class="accordion-item">
            <h2 id="dmep-vacataires-heading" class="accordion-header">
                <button aria-controls="dmep-vacataires-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-vacataires-collapse"
                        data-bs-toggle="collapse"
                        type="button">
                    {{ code + ' - ' + structure.libelle }}
                </button>

            </h2>


            <div id="dmep-vacataires-collapse" aria-labelledby="dmep-vacataires-heading" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div v-for="(etape, code) in structure.etapes">
                        <h4>{{ code + ' - ' + etape.libelle }}</h4>
                        <div v-for="(enseignement,code) in etape.enseignements">
                            <h5>{{ code + ' - ' + enseignement.libelle }}</h5>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Type d'heures</th>
                                    <th>Heures</th>
                                    <th>Centre de cout</th>
                                    <th>Statut</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(typeHeure, code) in enseignement.typeHeure">
                                    <tr v-for="heure in typeHeure.heures">
                                        <td>{{ typeHeure.libelle }}</td>
                                        <td>{{ heure.heuresAPayer }}</td>
                                        <td>{{ heure.centreCout.libelle }}</td>
                                        <td>{{ heuresStatut(heure) }}</td>
                                    </tr>
                                </template>

                                </tbody>
                            </table>
                           
                        </div>

                    </div>

                </div>
            </div>
        </div>


    </div>
</template>

<script>


import UnicaenVue from "unicaen-vue/js/Client/unicaenVue";

export default {
    name: "DemandeMiseEnPaiement.vue",
    props: {
        intervenant: {required: false},
    },
    data()
    {
        console.log(this.intervenant);
        return {
            datasServiceAPayer: null,
            urlListeServiceAPayer: unicaenVue.url('intervenant/:intervenant/mise-en-paiement/liste-service-a-payer', {intervenant: this.intervenant}),
        }
    },
    computed:
        {},
    methods: {
        findServiceAPayer()
        {
            unicaenVue.axios.get(this.urlListeServiceAPayer)
                .then(response => {
                    this.datasServiceAPayer = response.data;
                })
                .catch(error => {
                    console.error(error);
                })
        },
        heuresStatut(heures)
        {
            if (heures.heuresAPayer == heures.heuresPayer) {
                return 'Heures payées';
            }
            if (heures.heuresAPayer == heures.heuresDemander) {
                return 'Paiement demandé';
            }
            if (heures.heuresDemander == 0) {
                return 'Heures à demander';
            }
            return 'indetermine';
        }
    },
    mounted()
    {
        this.findServiceAPayer();
    }
}
</script>