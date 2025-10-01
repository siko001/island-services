<template>
  <thead class=" table-row-header">
  <tr>
    <th v-for="(title, index) in headers" :key="index" class="border border-color px-2 py-1 text-left whitespace-nowrap">
      {{ title }}
    </th>
  </tr>
  </thead>
</template>

<script>
export default {
  name: 'TableHeader',
  props: {
    headers: {
      type: Array,
      required: true,
    },
  },
};
</script>
