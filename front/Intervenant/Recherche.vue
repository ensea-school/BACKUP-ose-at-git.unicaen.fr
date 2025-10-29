<template>
    <h3>Saisissez le nom suivi éventuellement du prénom (2 lettres minimum)</h3>

    <div class="intervenant-recherche">
        <div class="critere">
            <div>
                <input id="term" autofocus class="form-control input" placeholder="votre recherche..." type="text"
                       v-on:keyup="rechercher"/><br/>
            </div>
            <div>
                <span class="fw-bold">Types d'intervenant : </span>
                <input v-model="checkedTypes" checked="checked" name="type[]" type="checkbox" value="permanent" v-on:change="reload()">
                Permanent
                <input v-model="checkedTypes" checked="checked" name="type[]" type="checkbox" value="vacataire" v-on:change="reload()"> Vacataire
                <input v-model="checkedTypes" checked="checked" name="type[]" type="checkbox" value="etudiant" v-on:change="reload()"> Etudiant

            </div>
            <br/>
        </div>
    </div>


    <table v-if="intervenants.length > 0" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th v-if="canView" style="width:90px"></th>
            <th>Civilité</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Structure</th>
            <th>Statut</th>
            <th>Date de naissance</th>
            <th>N° Personnel</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(intervenant,code) in intervenants" :class="{'bg-danger': intervenant.destruction!==null}"
            :title="(intervenant.destruction!==null) ? 'Fiche historisé' : ''">
            <td v-if="intervenant.canView">
                <a :href="urlFiche(intervenant['code'])"><i class="fas fa-eye"></i> Fiche</a>
            </td>
            <td>{{ intervenant.civilite }}</td>
            <td>{{ intervenant.nom }}</td>
            <td>{{ intervenant.prenom }}</td>
            <td>{{ intervenant.structure }}</td>
            <td>{{ intervenant.statut }}</td>
            <td>
                <u-date :value="intervenant['date-naissance']"/>
            </td>
            <td>{{ intervenant['numero-personnel'] }}</td>
        </tr>

        </tbody>
    </table>

    <table v-if="intervenants.length == 0 && noResult == 1" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th style="width:90px"></th>
            <th>Civilité</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Structure</th>
            <th>Statut</th>
            <th>Date de naissance</th>
            <th>N° Personnel</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="8" style="text-align:center">Aucun intervenant trouvé</td>
        </tr>

        </tbody>
    </table>
</template>


<script>


export default {
    name: 'Recherche',

    data()
    {
        return {
            searchTerm: '',
            noResult: 0,
            canView: false,
            intervenants: [],
            checkedTypes: ['vacataire', 'permanent', 'etudiant'],
        };
    },
    mixins: [Util],
    methods: {
        rechercher: function (event)
        {
            this.searchTerm = event.currentTarget.value;
            if (this.searchTerm == '') {
                this.noResult = 0;
            }
            if (this.searchTerm != '') {
                this.reload();
            }
        },

        urlFiche(code)
        {
            return unicaenVue.url('intervenant/code:' + code + '/voir');
        },

        reload()
        {
            var inputRecherche = document.getElementById('term');
            inputRecherche.focus();

            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {

                unicaenVue.axios.post(
                    unicaenVue.url("intervenant/recherche-json"), {
                        term: this.searchTerm
                    }
                )
                    .then(response => {
                        let datas = response.data;
                        let datasFiltered = [];

                        this.canView = false;
                        for (const intervenant in datas) {
                            if (datas[intervenant].canView) {
                                this.canView = true;
                            }
                            if (datas[intervenant].typeIntervenantCode == 'E' && this.checkedTypes.includes('vacataire')) {
                                datasFiltered.push(datas[intervenant]);
                                continue;
                            }
                            if (datas[intervenant].typeIntervenantCode == 'P' && this.checkedTypes.includes('permanent')) {
                                datasFiltered.push(datas[intervenant]);
                                continue;
                            }
                            if (datas[intervenant].typeIntervenantCode == 'S' && this.checkedTypes.includes('etudiant')) {
                                datasFiltered.push(datas[intervenant]);
                                continue;
                            }

                        }
                        this.intervenants = datasFiltered;

                        if (this.intervenants.length == 0) {
                            this.noResult = 1;
                        } else {
                            this.noResult = 0;
                        }



                    })
                    .catch(response => {
                        console.log(response.message);
                    });
            }, 800);

        }



    }


}
</script>

<style scoped>

</style>