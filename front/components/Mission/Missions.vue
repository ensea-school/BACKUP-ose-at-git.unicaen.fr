<template>
  Liste des missions !
  {{ intervenant}}
  {{canAddMission}}
</template>

<script>
export default {
  props: {
    intervenant: {},
    canAddMission: {}
  }
}
</script>

<style scoped>

</style>
