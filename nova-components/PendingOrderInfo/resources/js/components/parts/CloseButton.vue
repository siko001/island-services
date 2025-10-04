<template>
  <div @click="$emit('close')" class="text-red-500 cursor-pointer">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :height="computedHeight" :width="computedWidth">
      <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>
  </div>
</template>

<script>
export default {
  name: 'CloseButton',
  props: {
    width: {
      type: [String, Number],
      default: '1.5',
    },
    height: {
      type: [String, Number],
      default: '1.5',
    },
  },
  computed: {
    computedWidth() {
      return isNaN(this.width) ? this.width : `${this.width}rem`;
    },
    computedHeight() {
      return isNaN(this.height) ? this.height : `${this.height}rem`;
    },
  },
};
</script>
