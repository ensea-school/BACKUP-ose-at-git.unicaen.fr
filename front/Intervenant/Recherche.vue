<template>
    <h1>Recherche Intervenant</h1>

    <div class="intervenant-recherche">
        <div class="critere">
            <div>
                <input id="term" v-on:keyup="rechercher" class="form-control input" type="text"
                       placeholder="Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"/><br/>
            </div>
            <div>
                Types d'intervenant :
                <input v-on:change="reload()" type="checkbox" name="type[]" value="permanent" checked="checked" v-model="checkedTypes"> Permanent
                <input v-on:change="reload()" type="checkbox" name="type[]" value="vacataire" checked="checked" v-model="checkedTypes"> Vacataire
                <input v-on:change="reload()" type="checkbox" name="type[]" value="etudiant" checked="checked" v-model="checkedTypes"> Etudiant
            </div>
            <br/>
        </div>
    </div>


    <table class="table table-bordered table-sm">
        <thead>
        <tr>
            <th></th>
            <th>Civilité</th>
            <th>Prenom</th>
            <th>Nom</th>
            <th>Structure</th>
            <th>Statut</th>
            <th>Type</th>
            <th>Date de naissance</th>
            <th>N° Personnel</th>
        </tr>
        </thead>
        <tbody>
        <tr :class="{'bg-danger': intervenant.destruction!==null}" :title="(intervenant.destruction!==null) ? 'Fiche historisé' : ''"
            v-for="(intervenant,code) in intervenants">
            <td>
                <a :href="urlFiche(code)">Fiche</a>
            </td>
            <td>{{ intervenant.civilite }}</td>
            <td>{{ intervenant.prenom }}</td>
            <td>{{ intervenant.nom }}</td>
            <td>{{ intervenant.structure }}</td>
            <td>{{ intervenant.statut }}</td>
            <td>{{ intervenant.typeIntervenantLibelle }}</td>
            <td>{{ intervenant.dateNaissance.toLocaleString() }}</td>
            <td>{{ intervenant.numeroPersonnel }}</td>
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
            searchTerm: [],
            intervenants: [],
            checkedTypes: ['vacataire', 'permanent', 'etudiant'],
        };
    },
    mounted()
    {
        this.reload();
    },
    methods: {
        rechercher: function (event)
        {
            this.searchTerm = event.target.value;

            this.reload();
        },
        filtrer: function ()
        {

        },
        urlFiche(code)
        {
            return '/intervenant/code:' + code + '/voir';
        },
        reload()
        {
            console.log(this.checkedTypes);

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
                        for (const intervenant in datas) {
                            if (datas[intervenant].typeIntervenantCode == 'E' && !this.checkedTypes.includes('vacataire')) {
                                delete datas[intervenant]
                                continue;
                            }
                            if (datas[intervenant].typeIntervenantCode == 'P' && !this.checkedTypes.includes('permanent')) {
                                delete datas[intervenant]
                                continue;
                            }
                            if (datas[intervenant].typeIntervenantCode == 'S' && !this.checkedTypes.includes('etudiant')) {
                                delete datas[intervenant]
                                continue;
                            }

                        }
                        this.intervenants = datas;

                    })
                    .catch(response => {
                        console.log(response.message);
                    });
            }, 800);

        }



    }
    ,

}
</script>

<style scoped>

</style>