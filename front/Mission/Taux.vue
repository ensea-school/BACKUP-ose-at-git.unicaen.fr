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
                    <div v-for="tauxValeur in taux.tauxRemuValeurs" :key="tauxValeur.id">
                        <li>
                            <div class="row align-items-start">
                                <div class="col-md-4">
                                    {{ tauxValeur.valeur }}€/h à partir du {{ tauxValeur.dateEffet }}
                                </div>
                                <div class="col">
                                    <a class="btn btn-primary btn-sm"
                                       @click.prevent="saisieValeur"
                                       :data-id="tauxValeur.id">
                                        <i class="fas fa-pen-to-square" :data-id="tauxValeur.id"/>
                                    </a>
                                    <a class="btn btn-danger btn-sm"
                                       @click.prevent="supprimerValeur"
                                       :data-id="tauxValeur.id">
                                        <i class="fas fa-trash-can" :data-id="tauxValeur.id"/>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </div>
                </ul>
                <a :href="ajoutValeurUrl"
                   class="btn btn-primary btn-sm"
                   @click.prevent="ajoutValeur">
                    <u-icon name="plus"/>
                </a>
            </div>

            <!--            Pour les taux qui dépende d'un autre taux -->
            <div v-if=taux.missionTauxRemu class="row">
                <div class="col-md-7">
                    Modification :<br/>
                    <ul>
                        <div v-for="tauxValeur in taux.tauxRemuValeurs">
                            <li>
                                <div class="row align-items-start">
                                    <div class="col-md-8">
                                        Coéfficient de {{ tauxValeur.valeur }} à partir du {{ tauxValeur.dateEffet }}
                                    </div>
                                    <div class="col-md-auto">

                                        <a class="btn btn-primary btn-sm"
                                           @click.prevent="saisieValeur"
                                           :data-id="tauxValeur.id">
                                            <i class=" fas fa-pen-to-square" :data-id="tauxValeur.id"/>
                                        </a>
                                        <a class="btn btn-sm btn-danger"
                                           @click.prevent="supprimerValeur"
                                           :data-id="tauxValeur.id">
                                            <i class=" fas fa-trash-can" :data-id="tauxValeur.id"/>
                                        </a>
                                    </div>
                                </div>

                            </li>
                        </div>
                    </ul>
                    <a :href="ajoutValeurUrl"
                       class="btn btn-primary btn-sm"
                       @click.prevent="ajoutValeur">
                        <u-icon name="plus"/>
                    </a>
                </div>
                <div class="col">
                    Valeurs calculées (indexées sur le taux {{ taux.missionTauxRemu.libelle }}) :
                    <ul>
                        <div v-for="indexResult in taux.tauxRemuValeursIndex">
                            <li>{{ indexResult.valeur }}€/h à partir du {{ indexResult.date }}</li>
                        </div>
                    </ul>
                    <br>
                </div>

            </div>


        </div>
    </div>

    <div v-if=!taux.missionTauxRemu>
        <div v-for="item in listeTaux" :key="item">
            <div v-if="item.missionTauxRemu && item.missionTauxRemu.id === taux.id">
                <taux @supprimer="supprimer" @refreshListe="refreshListe" :key="taux.id" :taux="item" :listeTaux="listeTaux"></taux>
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
            ajoutValeurUrl: Util.url("missions-taux/saisir-valeur/:missionTauxRemu", {missionTauxRemu: this.taux.id}),
        };
    },
    methods: {
        saisie(event)
        {
            modAjax(event.target, (response) => {
                this.$emit('refreshListe');
            });
        },
        ajoutValeur(event)
        {
            modAjax(event.target, (response) => {
                this.$emit('refreshListe');
            });
        },
        saisieValeur(event)
        {
            event.target.href = Util.url("missions-taux/saisir-valeur/:missionTauxRemu/:missionTauxRemuValeur",
                {missionTauxRemu: this.taux.id, missionTauxRemuValeur: event.target.dataset.id});
            modAjax(event.target, (response) => {
                this.$emit('refreshListe');
            });
        },
        refreshListe(event)
        {
            this.$emit('refreshListe');
        },
        supprimer(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('refreshListe');
            });
        },
        supprimerValeur(event)
        {
            event.target.href = Util.url("missions-taux/supprimer-valeur/:missionTauxRemuValeur",
                {missionTauxRemuValeur: event.target.dataset.id});
            popConfirm(event.target, (response) => {
                this.$emit('refreshListe');
            });
        },
        refresh(taux)
        {
            axios.get(
                Util.url("missions-taux/get/:missionTauxRemu", {missionTauxRemu: taux.id})
            ).then(response => {
                this.$emit('refresh', response.data);
            });
        },
    }
}
</script>

<style scoped>

</style>