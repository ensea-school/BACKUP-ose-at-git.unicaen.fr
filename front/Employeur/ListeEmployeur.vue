<template>
    <h1 class="page-header">Gestion des employeurs</h1>
    <u-table-ajax ref="employeurs" v-model="lines" :data-url="this.dataUrl">
        <thead>
        <tr>
            <th column="ID">Id</th><!-- l'attribut column doit être renseigné pour pouvoir la rendre triable -->
            <th column="RAISON_SOCIALE">Raison sociale</th>
            <th column="NOM_COMMERCIAL">Nom commercial</th>
            <th>Siren</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <!-- On liste toute les lignes et on les affiche ici -->
        <tr v-for="(line,i) in lines" :key="i">
            <td>{{ line['ID'] }}</td>
            <td>{{ line['RAISON_SOCIALE'] }}</td>
            <td>{{ line['NOM_COMMERCIAL'] }}</td>
            <td>{{ line['SIREN'] }}</td>
            <td v-if="line['IMPORTABLE'] == 0">
                <a v-if="line['IMPORTABLE'] == 0" :href="editUrl(line['ID'])" @click.prevent="saisie">Modifier</a>
            </td>
            <td v-if="line['IMPORTABLE'] == 1">
                NON MODIFIABLE
            </td>
        </tr>
        </tbody>
    </u-table-ajax>
</template>

<script>

export default {
    name: 'listeEmployeur',
    data()
    {
        return {
            dataUrl: unicaenVue.url('employeur/get-data'),
            lines: [],
        };
    },
    methods: {
        saisie(event)
        {
            modAjax(event.currentTarget, (widget) => {
                this.$refs.employeurs.getData();
            });
        },
        editUrl(id)
        {
            return unicaenVue.url('employeur/saisie/:id', {id: id});
        },

    },
}

</script>
<style scoped>

</style>
