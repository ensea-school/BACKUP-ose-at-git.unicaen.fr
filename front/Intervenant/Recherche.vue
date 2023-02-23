<template>
    <h1>Recherche Intervenant</h1>

    <div class="intervenant-recherche">
        <div class="critere">
            Saisissez le nom suivi éventuellement du prénom (2 lettres au moins) :<br/>
            <input id="term" v-on:keyup="rechercher" class="form-control input" type="text"/>
        </div>
    </div>


    <table class="table table-bordered table-sm">
        <thead>
        <th>Nom</th>
        <th>Prenom</th>
        </thead>
        <tbody>
        <tr v-for="intervenant in intervenants" :intervenant="intervenant">
            <td>{{ intervenant.nom }}</td>
            <td>{{ intervenant.prenom }}</td>
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
        };
    },
    mounted()
    {
        this.reload();
    },
    methods: {
        rechercher: function (event) {
            this.searchTerm = event.target.value;
            this.reload();
        },
        reload()
        {
            console.log(this.intervenants.length)
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
                        this.intervenants = response.data;
                    })
                    .catch(response => {
                        console.log(response.message);
                    });
            }, 800);

        }



    },

}
</script>

<style scoped>

</style>