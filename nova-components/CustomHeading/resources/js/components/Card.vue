<template>
  <div class="card">
    <h2 class="text-2xl ">
      {{ card.title }} </h2>
  </div>
</template>

<script>
export default {
  props: {
    card: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>
.card {
  padding: 0 !important;
  margin: 0 !important;
  min-height: 0 !important;
  background: transparent !important;
  box-shadow: none !important;
  border: none !important;
  max-height: 1.2rem !important;
}</style>
