<template>
    <div class="card" :class="{ 'ms-5':taux.tauxRemu}">
        <div class="card-header">
            <h3 style="display:inline">{{ taux.libelle }} ({{ taux.code }})</h3>
            <div class="float-end">
                <a v-if="taux.canEdit"
                   :href="saisieUrl"
                   class="btn btn-primary"
                   @click.prevent="saisie">
                    <u-icon name="pen-to-square"/>
                    Modifier</a>
                &nbsp
                <a v-if="taux.canDelete"
                   :href="supprimerUrl"
                   class="btn btn-danger"
                   @click.prevent="supprimer">
                    <u-icon name="trash-can"/>
                    Supprimer</a>
            </div>
        </div>
        <div class="card-body">
            <!--            Pour les taux qui ne dépende pas d'un autre taux -->
            <div v-if=!taux.tauxRemu>
                Modification :<br/>
                <ul>
                    <div v-for="tauxValeur in taux.tauxRemuValeurs" :key="tauxValeur.id">
                        <li class="">
                            <div class="row align-items-start">
                                <div class="col-md-6">
                                    <u-heures :valeur="tauxValeur.valeur"/>€/h à partir du
                                    <u-date :value="tauxValeur.dateEffet"/>
                                </div>
                                <div class="col">
                                    <a v-if="taux.canEdit"
                                       class="text-primary"
                                       @click.prevent="saisieValeur"
                                       :data-id="tauxValeur.id">
                                        <u-icon name="pen-to-square"/>
                                    </a>
                                    &nbsp
                                    <a v-if="taux.canEdit"
                                       class="text-primary"
                                       @click.prevent="supprimerValeur"
                                       :data-id="tauxValeur.id">
                                        <u-icon name="trash-can"/>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </div>
                </ul>
                <a v-if="taux.canEdit"
                   :href="ajoutValeurUrl"
                   class="btn btn-primary btn-sm"
                   @click.prevent="ajoutValeur">
                    <u-icon name="plus"/>
                    Ajouter une valeur
                </a>
            </div>

            <!--            Pour les taux qui dépende d'un autre taux -->
            <div v-if=taux.tauxRemu class="row">
                <div class="col-md-7">
                    Modification :<br/>
                    <ul>
                        <div v-for="tauxValeur in taux.tauxRemuValeurs">
                            <li>
                                <div class="row align-items-start">
                                    <div class="col-md-8">
                                        Coéfficient de <u-heures :valeur="tauxValeur.valeur"/> à partir du
                                        <u-date :value="tauxValeur.dateEffet"/>
                                    </div>
                                    <div class="col-md-auto">

                                        <a v-if="taux.canEdit"
                                           class="text-primary"
                                           @click.prevent="saisieValeur"
                                           :data-id="tauxValeur.id">
                                            <u-icon name="pen-to-square"/>
                                        </a>
                                        &nbsp
                                        <a v-if="taux.canEdit"
                                           class="text-primary"
                                           @click.prevent="supprimerValeur"
                                           :data-id="tauxValeur.id">
                                            <u-icon name="trash-can"/>
                                        </a>
                                    </div>
                                </div>

                            </li>
                        </div>
                    </ul>
                    <a v-if="taux.canEdit"
                       :href="ajoutValeurUrl"
                       class="btn btn-primary btn-sm"
                       @click.prevent="ajoutValeur">
                        <u-icon name="plus"/>
                    </a>
                </div>
                <div class="col">
                    Valeurs calculées (indexées sur le taux {{ taux.tauxRemu.libelle }}) :
                    <ul>
                        <div v-for="indexResult in taux.tauxRemuValeursIndex">
                            <li><u-heures :valeur="indexResult.valeur"/>€/h à partir du
                                <u-date :value="indexResult.date"/>
                            </li>
                        </div>
                    </ul>
                    <br>
                </div>

            </div>


        </div>
    </div>

    <div v-if=!taux.tauxRemu>
        <div v-for="item in listeTaux" :key="item">
            <div v-if="item.tauxRemu && item.tauxRemu.id === taux.id">
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
            saisieUrl: unicaenVue.url('taux/saisir/:tauxRemu', {tauxRemu: this.taux.id}),
            supprimerUrl: unicaenVue.url("taux/supprimer/:tauxRemu", {tauxRemu: this.taux.id}),
            ajoutValeurUrl: unicaenVue.url("taux/saisir-valeur/:tauxRemu", {tauxRemu: this.taux.id}),
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
            event.currentTarget.href = unicaenVue.url("taux/saisir-valeur/:tauxRemu/:tauxRemuValeur",
                {tauxRemu: this.taux.id, tauxRemuValeur: event.currentTarget.dataset.id});
            modAjax(event.currentTarget, (response) => {
                this.$emit('refreshListe');
            });
        },
        refreshListe(event)
        {
            this.$emit('refreshListe');
        },
        supprimer(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('refreshListe');
            });
        },
        supprimerValeur(event)
        {
            event.currentTarget.href = unicaenVue.url("taux/supprimer-valeur/:tauxRemuValeur",
                {tauxRemuValeur: event.currentTarget.dataset.id});
            popConfirm(event.currentTarget, (response) => {
                this.$emit('refreshListe');
            });
        },
        refresh(taux)
        {
            unicaenVue.axios.get(
                unicaenVue.url("taux/get/:tauxRemu", {tauxRemu: taux.id})
            ).then(response => {
                this.$emit('refresh', response.data);
            });
        },
    }
}
</script>

<style scoped>

</style>