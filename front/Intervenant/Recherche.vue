<template>
  <h1>Recherche Intervenant</h1>

  <div class="intervenant-recherche">
    <div class="critere">
      Saisissez le nom suivi éventuellement du prénom (2 lettres au moins) :<br/>
      <input id="term" v-on:keydown="rechercher" class="form-control input" type="text"/>
    </div>
  </div>


  <table>
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
  data() {
    return {
      searchTerm: [],
      intervenants: [],
    };
  },
  mounted() {
    this.reload();
  },
  methods: {
    rechercher: function (event) {
      this.searchTerm = event.target.value;
      this.reload();
    },
    reload() {
      console.log(this.searchTerm);

      axios.post(
          Util.url("intervenant/recherche-json"), {
            term: 'dupo'
          })
          .then(function (response) {
            console.log(response.data);
          })
          .catch(function (error) {
            console.log(error);
          });

      ;
    },
  }
}
</script>

<style scoped>

</style>