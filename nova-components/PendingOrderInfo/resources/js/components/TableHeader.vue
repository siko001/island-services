<template>
  <thead class="bg-gray-100">
  <tr>
    <th v-for="(title, index) in headers" :key="index" class="border px-2 py-1 text-left">
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
