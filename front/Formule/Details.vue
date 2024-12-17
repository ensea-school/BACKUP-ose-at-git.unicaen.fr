<template>
    <div v-if="countChoix()>1">
        <div class="d-flex justify-content-center align-items-center">
            <div>
                <table class="table table-borderless table-xs">
                    <tr v-for="(tvhLib, tvhId) in listeTypes" :key="tvhId">
                        <td v-for="(evhLib, evhId) in listeEtats" :key="evhId">
                            <div v-if="typesVolumesHoraires[tvhId].etats[evhId]">
                                <a :class="{ 'btn btn-outline-secondary btn-choixtevh': true, 'active': !(tvhId==typeVolumeHoraire && evhId==etatVolumeHoraire) }"
                                   @click="load(tvhId,evhId)">{{ tvhLib }} {{ evhLib }}</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div v-if="!typeVolumeHoraire" class="alert alert-info">Veuillez cliquer sur un des boutons ci-dessus pour afficher de détail de calcul des HETD</div>
    </div>
    <div v-else>
        <h2>{{ listeTypes[typeVolumeHoraire] }} {{ listeEtats[etatVolumeHoraire] }}</h2>
    </div>
    <div v-if="Object.keys(this.data).length > 0">
<pre>{{ data }}</pre>
    </div>
</template>
<script>

export default {
    name: 'Details',
    components: {},
    props: {
        intervenant: {type: Number},
        typesVolumesHoraires: {type: Object},
    },
    data()
    {
        return {
            data: {},
            typeVolumeHoraire: null,
            etatVolumeHoraire: null,
        };
    },
    mounted()
    {
        if (1 == this.countChoix()) {
            for (let type in this.typesVolumesHoraires) {
                this.typeVolumeHoraire = type;
                for (let etat in this.typesVolumesHoraires[type].etats) {
                    this.etatVolumeHoraire = etat;
                }
            }
            this.load(this.typeVolumeHoraire, this.etatVolumeHoraire);
        }
    },
    methods: {
        load(tvhId, evhId)
        {
            this.typeVolumeHoraire = tvhId;
            this.etatVolumeHoraire = evhId;

            const params = {
                intervenant: this.intervenant,
                typeVolumeHoraire: this.typeVolumeHoraire,
                etatVolumeHoraire: this.etatVolumeHoraire,
            };

            unicaenVue.axios.get(
                unicaenVue.url('intervenant/:intervenant/formule/details-data/:typeVolumeHoraire/:etatVolumeHoraire', params)
            ).then(response => {
                this.data = response.data;
            });
        },
        dataUrl()
        {

        },
        countChoix()
        {
            let nb = 0;
            for (let type in this.typesVolumesHoraires) {
                for (let etat in this.typesVolumesHoraires[type].etats) {
                    nb++;
                }
            }
            return nb;
        },
    },
    computed: {
        listeTypes()
        {
            let types = {};
            for (let type in this.typesVolumesHoraires) {
                types[type] = this.typesVolumesHoraires[type].libelle;
            }
            return types;
        },
        listeEtats()
        {
            let etats = {};
            for (let type in this.typesVolumesHoraires) {
                for (let etat in this.typesVolumesHoraires[type].etats) {
                    etats[etat] = this.typesVolumesHoraires[type].etats[etat];
                }
            }

            const etatsTrie = Object.keys(etats) // Obtenir les clés
                                     .sort((a, b) => a - b) // Trier les clés numériquement
                                     .reduce((acc, key) => { // Reconstruire l'objet trié
                                         acc[key] = etats[key];
                                         return acc;
                                     }, {});

            return etatsTrie;
        },
    },
}

</script>
<style scoped>

.btn-choixtevh {
    width: 100%;
    margin-bottom: 6px;
}

</style>