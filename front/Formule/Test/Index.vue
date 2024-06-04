<template>
    <h1 class="page-header">Page de tests de formule de calcul des HETD</h1>

    <u-table-ajax :data-url="this.dataUrl" v-model="lines" ref="testsFormules">
        <thead>
        <tr>
            <th column="ID">Id</th>
            <th column="LIBELLE">Libellé</th>
            <th column="FORMULE">Formule</th>
            <th column="ANNEE">Année</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(line,i) in lines" :key="i">
            <td>{{ line.ID }}</td>
            <td>{{ line.LIBELLE }}</td>
            <td>{{ line.FORMULE }}</td>
            <td>{{ line.ANNEE }}</td>
            <td style="width:1%;white-space: nowrap">
                <a :href="editUrl(line.ID)"
                   title="Modification du test de formule"
                ><i class="fas fa-pencil"></i></a>
                &nbsp;
                <a :href="deleteUrl(line.ID)"
                   title="Suppression du test de formule"
                   data-content="Êtes-vous sur de vouloir supprimer ce test ?"
                   data-title="Suppression du test de formule"
                   @click.prevent="supprimerTest"><i class="fas fa-trash-can"></i></a>
            </td>
        </tr>
        </tbody>
    </u-table-ajax>
    <b-button variant="primary" :href="addUrl()">Ajout d'un nouveau test</b-button>

    <br/>
    <br/>
    <div class="card bg-warning">
        <div class="card-header">
            <h3>Import d'un nouveau test à partir d'un tableur</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" :action="this.importUrl()">
                <div class="form-group mb-3">
                    <label for="formule-name">Feuille de calcul (format Excel ou Calc)</label>
                    <input class="form-control" id="formule-fichier" type="file" name="fichier"/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Import d'un test à partir d'un tableur</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

export default {
    data()
    {
        return {
            dataUrl: unicaenVue.url('formule-test/data'),
            lines: [],
        };
    },
    methods: {
        addUrl()
        {
            return unicaenVue.url('formule-test/saisir');
        },
        editUrl(id)
        {
            return unicaenVue.url('formule-test/saisir/:id', {id: id});
        },
        deleteUrl(id)
        {
            return unicaenVue.url('formule-test/supprimer/:id', {id: id});
        },
        importUrl()
        {
            return unicaenVue.url('formule-test/import');
        },
        supprimerTest(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$refs.testsFormules.getData();
            });
            return false;
        },
    },
}

</script>
<style scoped>

</style>