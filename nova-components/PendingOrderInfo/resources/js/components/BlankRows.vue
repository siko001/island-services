<template>
  <tr v-for="n in blankRowsCount" :key="'empty-'+n">
    <td v-for="c in columnCount" :key="'empty-col-'+c+'-row-'+n" class="border px-2 py-1">&nbsp;</td>
  </tr>
</template>

<script>
export default {
  name: 'BlankRows',
  props: {
    rows: Array,
    quantity: Number,
    columnCount: Number
  },
  computed: {
    blankRowsCount() {
      const missing = this.quantity - (this.rows ? this.rows.length : 0);
      return missing > 0 ? missing : 0;
    }
  }
}
</script>
