<template>
    <b-row v-if="data.types.length > 0">
        <b-col cols="6">
            <h2>Totaux en heures équivalent TD</h2>
            <table class="table table-bordered">
                <tr>
                    <th>&nbsp;</th>
                    <th v-for="type in data.types" :key="type">{{ tradType(type) }}</th>
                </tr>
                <tr v-for="(types,categorie) in data.heures" :key="categorie">
                    <th>{{ tradCategorie(categorie) }}</th>
                    <template v-if="categorie !== 'primes'">
                        <td class="nombre" v-for="(heures,type) in types" :key="type">
                            <u-heures :valeur="heures"/>
                        </td>
                    </template>
                    <template v-else>
                        <td class="nombre" :colspan="types.length">
                            <u-heures :valeur="data.heures.primes.total"/>
                        </td>
                    </template>
                </tr>
            </table>
            {{ serviceLigne }}
        </b-col>
    </b-row>
    <br />
</template>
<script>

export default {
    name: 'TotauxHetd',
    props: {
        intervenant: {type: Number},
        typeVolumeHoraire: {type: Number},
    },
    data()
    {
        return {
            data: {types: []},
            serviceLigne: "",
        };
    },
    mounted()
    {
        this.load();

        // écoute d'un événement pour pouvoir être chargé depuis l'extérieur
        window.addEventListener("Formule/TotauxHetd.refresh", (event) => {
            this.load();
        });
    },
    methods: {
        load()
        {
            unicaenVue.axios.get(
                unicaenVue.url("intervenant/formule-totaux-hetd/:intervenant/:typeVolumeHoraire",{intervenant: this.intervenant,typeVolumeHoraire: this.typeVolumeHoraire}),
            ).then(response => {
                this.data = response.data.data;
                if (this.data.serviceStatutaire > 0){

                    if (this.data.serviceDu != this.data.serviceStatutaire){
                        this.serviceLigne = "* " + this.data.serviceDu + "h de service dû en tenant compte des modifications de service";
                    }else{
                        this.serviceLigne = "* " + this.data.serviceStatutaire+ "h de service statutaire";
                    }
                }
            });
        },
        tradType(type)
        {
            switch (type) {
                case 'fi':
                    return 'FI';
                case 'fa':
                    return 'FA';
                case 'fc':
                    return 'FC';
                case 'enseignement':
                    return 'Enseignement';
                case 'referentiel':
                    return 'Référentiel';
                case 'total':
                    return 'Total';
                default:
                    return type;
            }
        },
        tradCategorie(categorie)
        {
            switch (categorie) {
                case 'service':
                    return 'Service*';
                case 'compl':
                    return 'Heures complémentaires';
                case 'payable':
                    return 'Heures';
                case 'non-payable':
                    return 'Non payable';
                case 'primes':
                    return 'Primes';
                case 'total':
                    return 'Total';
                default:
                    return categorie;
            }
        }
    },
}

</script>
<style scoped>

table {
    margin-bottom:0px;
}

td.nombre {
    text-align: right;
}

table.table-bordered * {
    border-width: 1px;
}

</style>