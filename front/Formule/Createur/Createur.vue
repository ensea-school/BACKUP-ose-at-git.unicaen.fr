<template>
    <h1>Créateur de formule de calcul</h1>

    <!-- Début du formulaire de création de formule -->
    <div class="row mb-3">

        <div class="form-group col-md-4">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" aria-label="Code" id="code" name="code" :value="this.formule.code">
        </div>

        <div class="form-group col-md-8">
            <label for="libelle" class="form-label">Libellé</label>
            <input type="text" class="form-control" aria-label="Libellé" id="libelle" name="libelle"
                   :value="this.formule.libelle">
        </div>

    </div>
    <div class="row mb-3">
        <h5>Paramètres des intervenants</h5>
        <div class="form-group col-md-3">
            <label for="iParam1Libelle" class="form-label">Paramètre 1</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 1" id="iParam1Libelle" name="iParam1Libelle"
                   :value="this.formule.iParam1Libelle">
        </div>
        <div class="form-group col-md-3">
            <label for="iParam2Libelle" class="form-label">Paramètre 2</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 2" id="iParam2Libelle" name="iParam2Libelle"
                   :value="this.formule.iParam2Libelle">
        </div>
        <div class="form-group col-md-2">
            <label for="iParam3Libelle" class="form-label">Paramètre 3</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 3" id="iParam3Libelle" name="iParam3Libelle"
                   :value="this.formule.iParam3Libelle">
        </div>
        <div class="form-group col-md-2">
            <label for="iParam4Libelle" class="form-label">Paramètre 4</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 4" id="iParam4Libelle" name="iParam4Libelle"
                   :value="this.formule.iParam4Libelle">
        </div>
        <div class="form-group col-md-2">
            <label for="iParam5Libelle" class="form-label">Paramètre 5</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 5" id="iParam5Libelle" name="iParam5Libelle"
                   :value="this.formule.iParam5Libelle">
        </div>

    </div>
    <div class="row mb-3">
        <h5>Paramètres des volumes horaires</h5>
        <div class="form-group col-md-3">
            <label for="vhParam1Libelle" class="form-label">Paramètre 1</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 1" id="vhParam1Libelle" name="vhParam1Libelle"
                   :value="this.formule.vhParam1Libelle">
        </div>
        <div class="form-group col-md-3">
            <label for="vhParam2Libelle" class="form-label">Paramètre 2</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 2" id="vhParam2Libelle" name="vhParam2Libelle"
                   :value="this.formule.vhParam2Libelle">
        </div>
        <div class="form-group col-md-2">
            <label for="vhParam3Libelle" class="form-label">Paramètre 3</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 3" id="vhParam3Libelle" name="vhParam3Libelle"
                   :value="this.formule.vhParam3Libelle">
        </div>
        <div class="form-group col-md-2">
            <label for="vhParam4Libelle" class="form-label">Paramètre 4</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 4" id="vhParam4Libelle" name="vhParam4Libelle"
                   :value="this.formule.vhParam4Libelle">
        </div>
        <div class="form-group col-md-2">
            <label for="vhParam5Libelle" class="form-label">Paramètre 5</label>
            <input type="text" class="form-control" placeholder="non utilisé" aria-label="Paramètre 5" id="vhParam5Libelle" name="vhParam5Libelle"
                   :value="this.formule.vhParam5Libelle">
        </div>
    </div>
    <!-- Fin du formulaire de création de formule -->


    <!-- Edition des règles -->
    <div class="row">
        <h2>Règles de calcul</h2>

        <div class="col-md-7">
            <table class="table table-bordered table-condensed table-xs">
                <tr v-for="(r, name) in resultat">
                    <th style="vertical-align: top;">{{ name }}</th>
                    <td style="vertical-align: top;">=</td>
                    <td>
                        <textarea class="regle-def" name="" id="" cols="30" rows="1"></textarea>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class="col-md-5">

            <div class="card bg-info">
                <h5 class="card-header">Variables</h5>
                <div class="card-body">

                    <div v-for="(vs, ivh) in variables">

                        <h4>{{ ivh }}</h4>

                        <div v-for="(v, name) in vs">
                            <p class="variable-name mb-0">{{ name }}</p>
                            <p class="variable-desc mb-2">{{ v.description }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Fin d'édition des règles -->

</template>
<script>

import Regle from './Regle.vue';
import Variable from './Variable.vue';

export default {
    name: 'Createur',
    components: {
        Regle,
        Variable
    },
    props: {
        formule:{required: true, type: Object},
        variables: {required: true, type: Array},
        resultat: {required: true, type: Array},
    },
}

</script>
<style scoped>

.regle-def {
    resize: none;
    height: auto;
    overflow-y: hidden;
}

.variables {

}

p.variable-name {
    font-family: sans-serif;
    font-weight: bold;
    margin-bottom: 0px;
    font-size: 9pt;
}

p.variable-desc {
    color: gray;
    font-style: italic;
    font-size: 8pt;
}

</style>