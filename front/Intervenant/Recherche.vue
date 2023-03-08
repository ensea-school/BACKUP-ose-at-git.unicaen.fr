<template>
    <h3>Saisissez le nom suivi éventuellement du prénom (2 lettres minimum)</h3>

    <div class="intervenant-recherche">
        <div class="critere">
            <div>
                <input id="term" v-on:keyup="rechercher" class="form-control input" type="text"
                       placeholder="votre recherche..."/><br/>
            </div>
            <div>
                <span class="fw-bold">Types d'intervenant : </span>
                <input v-on:change="reload()" type="checkbox" name="type[]" value="permanent" checked="checked" v-model="checkedTypes"> Permanent
                <input v-on:change="reload()" type="checkbox" name="type[]" value="vacataire" checked="checked" v-model="checkedTypes"> Vacataire
                <input v-on:change="reload()" type="checkbox" name="type[]" value="etudiant" checked="checked" v-model="checkedTypes"> Etudiant
                
            </div>
            <br/>
        </div>
    </div>


    <table v-if="intervenants.length > 0" class="table table-bordered table-hover">
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
        <tr :class="{'bg-danger': intervenant.destruction!==null}" :title="(intervenant.destruction!==null) ? 'Fiche historisé' : ''"
            v-for="(intervenant,code) in intervenants">
            <td style="">
                <a :href="urlFiche(intervenant['code'])"><i class="fas fa-eye"></i> Fiche</a>
            </td>
            <td>{{ intervenant['civilite'] }}</td>
            <td>{{ intervenant['nom'] }}</td>
            <td>{{ intervenant['prenom'] }}</td>
            <td>{{ intervenant['structure'] }}</td>
            <td>{{ intervenant['statut'] }}</td>
            <td>{{ intervenant['date-naissance'] }}</td>
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
            <td style="text-align:center" colspan="8">Aucun intervenant trouvé</td>
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
            intervenants: [],
            checkedTypes: ['vacataire', 'permanent', 'etudiant'],
        };
    },

    methods: {
        rechercher: function (event)
        {
            this.searchTerm = event.target.value;
            if (this.searchTerm == '') {
                this.noResult = 0;
            }
            if (this.searchTerm != '') {
                this.reload();
            }
        },

        urlFiche(code)
        {
            return '/intervenant/code:'+code+'/voir';
        },

        reload()
        {

            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {

                axios.post(
                    Util.url("intervenant/recherche-json"), {
                        term: this.searchTerm
                    }
                )
                    .then(response => {
                        let datas = response.data;
                        let datasFiltered = [];

                        for (const intervenant in datas) {
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