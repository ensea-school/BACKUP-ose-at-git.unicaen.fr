<template>
  {{ msg }}
  <a href="#">Mon lien</a>
</template>

<script>
export default {
  props: {
    msg: String
  }
}
</script>

<style scoped>
a {
  color: yellowgreen;
}
</style>
