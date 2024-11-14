<template>
    <h1 class="page-header">Liste des signatures électroniques de contrat</h1>
    <u-table-ajax ref="signatures" v-model="lines" :data-url="this.dataUrl">
        <thead>
        <tr>
            <th column="ID_SIGNATURE">Id</th><!-- l'attribut column doit être renseigné pour pouvoir la rendre triable -->
            <th column="NOM">Nom</th>
            <th column="PRENOM">Prénom</th>
            <th column="DATE_CREATION_SIGNATURE_ELECTRONIQUE">Date signature électronique</th>
            <th column="STATUT_SIGNATURE_ELECTRONIQUE">Statut</th>
            <th>&nbsp;</th>

        </tr>
        </thead>
        <tbody>
        <!-- On liste toute les lignes et on les affiche ici -->
        <tr v-for="(line,i) in lines" :key="i">
            <td>{{ line['ID_SIGNATURE'] }}</td>
            <td>{{ line['NOM'] }}</td>
            <td>{{ line['PRENOM'] }}</td>
            <td>
                <u-date :value="line['DATE_CREATION_SIGNATURE_ELECTRONIQUE']"/>
            </td>
            <td>{{ line['STATUT_SIGNATURE_ELECTRONIQUE'] }}</td>
            <td>
                <a :href="getDocumentUrl(line['ID_SIGNATURE'])" class="btn btn-info"><i class="fas fa-download"></i>
                </a>
                &nbsp;
                <a :href="contratUrl(line['ID_INTERVENANT'])" class="btn btn-info"><i class="fas fa-eye"></i>
                </a>
                &nbsp;
                <a :href="updateSignatureUrl(line['ID_SIGNATURE'])" class="btn btn-info"><i class="fas fa-rotate"></i>
                </a>
            </td>
        </tr>
        </tbody>
    </u-table-ajax>
</template>

<script>

export default {
    name: 'listeContrat',
    data()
    {
        return {
            dataUrl: unicaenVue.url('signature/data-contrat'),
            lines: [],
        };
    },
    methods: {

        contratUrl(id)
        {
            return unicaenVue.url('intervenant/:intervenant/contrat', {intervenant: id});
        },
        getDocumentUrl(id)
        {
            return unicaenVue.url('signature/:signature/get-document', {signature: id});
        },
        updateSignatureUrl(id)
        {
            return unicaenVue.url('signature/:signature/update-signature', {signature: id});
        },

    },
}

</script>
<style scoped>

</style>