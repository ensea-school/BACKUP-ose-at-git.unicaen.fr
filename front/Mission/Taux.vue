<template>

    <div class="card" :class="{ 'ms-5':taux.missionTauxRemu}">
        <div class="card-header">
            {{ taux.libelle }} ({{ taux.code }})
            <div class="float-end">
                <a :href="saisieUrl"
                   class="btn btn-primary"
                   @click.prevent="saisie">
                    <u-icon name="pen-to-square"/>
                    Modifier</a>
                &nbsp;
                <a :href="supprimerUrl"
                   class="btn btn-danger"
                   @click.prevent="supprimer">
                    <u-icon name="trash-can"/>
                    Supprimer</a>
            </div>
        </div>
        <div class="card-body">
            <!--            Pour les taux qui ne dépende pas d'un autre taux -->
            <div v-if=!taux.missionTauxRemu>
                Modification :<br/>
                <ul>
                    <div v-for="tauxValeur in taux.tauxRemuValeurs">
                        <li>{{ tauxValeur.valeur }}€/h à partir du {{ tauxValeur.dateEffet }}</li>
                    </div>
                </ul>
            </div>


            <!--            Pour les taux qui dépende d'un autre taux -->
            <div v-if=taux.missionTauxRemu class="row">
                <div class="col">
                    Valeurs calculées (indexées sur le taux {{ taux.missionTauxRemu.libelle }}) :
                    <ul>
                        <div v-for="indexResult in taux.tauxRemuValeursIndex">
                            <li>{{ indexResult.valeur }}€/h à partir du {{ indexResult.date }}</li>
                        </div>
                    </ul>
                    <br>
                </div>
                <div class="col order-1">
                    Modification :<br/>
                    <ul>
                        <div v-for="tauxValeur in taux.tauxRemuValeurs">
                            <li>Coéfficient de {{ tauxValeur.valeur }} à partir du {{ tauxValeur.dateEffet }}</li>
                        </div>
                    </ul>
                </div>
            </div>


        </div>
    </div>

    <div v-if=!taux.missionTauxRemu>
        <div v-for="item in listeTaux" :key="item">
            <div v-if="item.missionTauxRemu && item.missionTauxRemu.id === taux.id">
                <taux @refresh="refresh" :key="taux.id" :taux="item" :listeTaux="listeTaux"></taux>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Taux",
    props: {
        taux: {required: true},
        listeTaux: {required: true},
    },
    data()
    {
        return {
            saisieUrl: Util.url('missions-taux/saisir/:missionTauxRemu', {missionTauxRemu: this.taux.id}),
            supprimerUrl: Util.url("missions-taux/supprimer/:missionTauxRemu", {missionTauxRemu: this.taux.id}),
        };
    },
    methods: {
        saisie(event)
        {
            modAjax(event.target, (widget) => {
                this.refresh();
            });
        },
        supprimer(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('supprimer', this.missionTauxRemu);
            });
        },
    }
}
</script>

<style scoped>

</style>