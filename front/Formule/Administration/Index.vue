<template>
    <h1>Formules de calcul</h1>

    <table class="table table-bordered table-hover table-sort">
        <thead>
        <tr>
            <th>Libellé</th>
            <th>Paramètres</th>
            <th>Règle de délégation</th>
            <th>Ressources</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="formule in formules" :key="id" :class="{ 'desactivee': !formule.active }" :title="!formule.active ? 'Cette formule est désactivée' : ''">
            <td>{{ formule.libelle }}<br /><i>{{ formule.code }}</i></td>
            <td>
                <div v-if="formule.iParam1Libelle" class="cartridge">
                    <span>intervenant</span><span>param1</span> {{ formule.iParam1Libelle }}
                </div>
                <div v-if="formule.iParam2Libelle" class="cartridge">
                    <span>intervenant</span><span>param2</span> {{ formule.iParam2Libelle }}
                </div>
                <div v-if="formule.iParam3Libelle" class="cartridge">
                    <span>intervenant</span><span>param3</span> {{ formule.iParam3Libelle }}
                </div>
                <div v-if="formule.iParam4Libelle" class="cartridge">
                    <span>intervenant</span><span>param4</span> {{ formule.iParam4Libelle }}
                </div>
                <div v-if="formule.iParam5Libelle" class="cartridge">
                    <span>intervenant</span><span>param5</span> {{ formule.iParam5Libelle }}
                </div>

                <div v-if="formule.vhParam1Libelle" class="cartridge">
                    <span>volume horaire</span><span>param1</span> {{ formule.vhParam1Libelle }}
                </div>
                <div v-if="formule.vhParam2Libelle" class="cartridge">
                    <span>volume horaire</span><span>param2</span> {{ formule.vhParam2Libelle }}
                </div>
                <div v-if="formule.vhParam3Libelle" class="cartridge">
                    <span>volume horaire</span><span>param3</span> {{ formule.vhParam3Libelle }}
                </div>
                <div v-if="formule.vhParam4Libelle" class="cartridge">
                    <span>volume horaire</span><span>param4</span> {{ formule.vhParam4Libelle }}
                </div>
                <div v-if="formule.vhParam5Libelle" class="cartridge">
                    <span>volume horaire</span><span>param5</span> {{ formule.vhParam5Libelle }}
                </div>
            </td>
            <td v-if="formule.delegationAnnee">Avant {{ formule.delegationAnnee }}/{{ formule.delegationAnnee+1 }}, utilise <i>{{ formule.delegationFormule }}</i></td>
            <td v-else></td>
            <td>
                <a :href="this.telechargementUrl(formule.id)"><i class="fas fa-table-cells"></i> tableur</a><br />
                <a v-if="this.canEdit" :href="this.detailsUrl(formule.id)"><i class="fas fa-table-cells"></i> code PHP</a>
            </td>
        </tr>
        </tbody>

    </table>
    <br/>
    <div v-if="this.canEdit" class="card bg-warning">
        <div class="card-header">
            <h3>Création/Modification d'une formule à partir d'un tableur</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <span class="icon iconly icon-attention"></span>
                <strong>Attention :</strong> cette opération peut avoir un impact déterminant sur le calcul
                de vos heures complémentaires et par extension sur tous vos paiements.
                En outre, le fichier doit être bâti selon un modèle bien précis.
                Vous êtes invités à ne téléverser que des tableurs préparés ou validés par l'équipe OSE.
            </div>
            <form method="post" enctype="multipart/form-data" :action="this.televersementUrl()">
                <div class="form-group mb-3">
                    <label for="formule-name">Feuille de calcul (format Excel ou Calc)</label>
                    <input class="form-control" id="formule-fichier" type="file" name="fichier"/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Ajout/Modification à partir d'un tableur</button>
                </div>
            </form>
        </div>
    </div>

</template>
<script>

export default {
    name: 'Index',
    components: {},
    props: {
        formules: {required: true, type: Array},
        canEdit: {required: true, type: Boolean},
    },
    methods: {
        telechargementUrl(formule)
        {
            return unicaenVue.url('formule/administration/telecharger-tableur/' + formule)
        },
        televersementUrl()
        {
            return unicaenVue.url('formule/administration/televerser-tableur')
        },
        detailsUrl(formule)
        {
            return unicaenVue.url('formule/administration/details/' + formule)
        }
    },
}

</script>
<style scoped>

.cartridge {
    white-space: nowrap;
}

.icon-attention {
    font-size: 50pt;
    float: left;
    margin-right: 20pt;
    margin-top: 0em;
    line-height: 42pt;
}

.desactivee {
    background-color: #fdfdfd;
    font-style: italic;
    color: #bbb;
}

</style>