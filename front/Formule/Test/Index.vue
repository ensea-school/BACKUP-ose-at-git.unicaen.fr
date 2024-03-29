<template>
    <u-table-ajax :data-url="this.dataUrl" @data="maj" ref="testsFormules">
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
</template>

<script>

export default {
    name: 'Test',
    data()
    {
        return {
            dataUrl: unicaenVue.url('formule-test/data'),
            lines: [],
        };
    },
    methods: {
        maj(lines)
        {
            this.lines = lines;
        },
        editUrl(id)
        {
            return unicaenVue.url('formule-test/saisir/:id', {id: id});
        },
        deleteUrl(id)
        {
            return unicaenVue.url('formule-test/supprimer/:id', {id: id});
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