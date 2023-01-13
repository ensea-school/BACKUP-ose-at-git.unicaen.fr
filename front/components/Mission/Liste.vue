<template>
  Liste des missions !
  {{ intervenant }}
  {{ canAddMission }}
</template>

<script>
export default {
  props: {
    intervenant: {},
    canAddMission: {}
  },
  mounted()
  {
    $.ajax({
      url: Util.url("mission/liste/:intervenant", {intervenant: this.intervenant}),
      success: (response) => {

        //console.log(response);
      }
    });
  }

}
</script>

<style scoped>

</style>
