<template>
    <h1>Formules de calcul</h1>


    {{canEdit}}
<pre>{{formules}}</pre>
    <table class="table table-bordered table-sort">
        <thead>
        <tr>
            <th>Libellé</th>
            <th>Nom</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="formule in formules" :key="id">
            <td>{{formule.libelle}}</td>
            <td>{{formule.code}}</td>
        </tr>
        </tbody>

    </table>
</template>
<script>

export default {
    name: 'Index',
    components: {
    },
    props: {
        formules:{required: true, type: Array},
        canEdit: {required: true, type: Boolean},
    },
}

</script>
<style scoped>



</style>