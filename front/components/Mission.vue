<template>
  <div>{{ mission }}</div>
</template>

<script>
export default {
  props: {
    mission: {
      type: String,
      required: true
    }
  }

}
</script>

<style scoped>

</style>