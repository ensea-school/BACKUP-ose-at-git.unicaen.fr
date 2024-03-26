<template>
    <div>qsdqsd</div>
    <div>qsdqsd</div>
    <b-alert :model-value="true" variant="danger">coucou tout le monde!!!</b-alert>

    <b-alert :model-value="true">Default Alert</b-alert>

    <b-alert variant="success" :model-value="true">Success Alert</b-alert>

    <BAlert v-model="showDismissibleAlert" variant="danger" dismissible> Dismissible Alert! </BAlert>

    <div>qsdqsd</div>

    <b-pagination v-model="this.page" total-rows="1000" per-page="10"/>
    <b-table-simple bordered>
        <b-thead>
            <b-tr>
                <b-th sticky-column>Sticky Column Header</b-th>
                <b-th>Heading 1</b-th>
                <b-th>Heading 2</b-th>
                <b-th>Heading 3</b-th>
                <b-th>Heading 4</b-th>
            </b-tr>
        </b-thead>
        <b-tbody>
            <b-tr>
                <b-th>Sticky Column Row Header</b-th>
                <b-td>Cell</b-td>
                <b-td>Cell</b-td>
                <b-td>Cell</b-td>
                <b-td>Cell</b-td>
            </b-tr>
            <b-tr>
                <b-th>Sticky Column Row Header</b-th>
                <b-td>Cell</b-td>
                <b-td>Cell</b-td>
                <b-td>Cell</b-td>
                <b-td>Cell</b-td>
            </b-tr>
        </b-tbody>
    </b-table-simple>

    <data-table :data="this.tdata" class="table table-bordered">
        <thead>
        <tr>
            <th>A</th>
            <th>B</th>
        </tr>
        </thead>
    </data-table>

</template>

<script>

export default {
    name: 'Test',
    data()
    {
        return {
            page: 1,
            tdata: [
                [1, 'un'],
                [2, 'deux'],
                [3, 'trois'],
                [4, 'quatre'],
                [5, 'cinq'],
                [6, 'six'],
                [7, 'sept'],
                [8, 'huit'],
                [9, 'neuf'],
                [10, 'dix'],
                [11, 'trois'],
                [12, 'douze'],
                [13, 'treize'],
                [14, 'quatorze'],
            ],
            preventableModal: false,
        };
    },
    methods: {
        detailsUrl(formule)
        {
            return unicaenVue.url('formule/administration/details/' + formule)
        }
    },
}

</script>
<style scoped>

</style>