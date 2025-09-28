<template>
  <tr class="order-parent" v-for="(order, index) in orders" :key="order.id || index" @click="handleSelect(order)">
    <td class="border px-2 py-1 cursor-pointer text-blue-600 underline element" :class="{ 'selected-row': isSelected(order) }">
      {{ getOrderNumber(order) }}
    </td>
    <td :class="{ 'selected-row': isSelected(order) }" class="border px-2 py-1 element">
      {{ formatDate(getOrderDate(order)) }}
    </td>
    <td :class="{ 'selected-row': isSelected(order) }" class="border px-2 py-1 element">
      {{ getAreaName(order) }}
    </td>
    <td :class="{ 'selected-row': isSelected(order) }" class="border px-2 py-1 element">
      {{ getLocationName(order) }}
    </td>
  </tr>
</template>

<script>
export default {
  name: 'OrderLoop',
  props: {
    orders: {
      type: Array,
      required: true,
    },
    getOrderDate: {
      type: Function,
      required: true
    },
    selectedOrderNumber: {
      type: String,
      default: null,
    },
    selectedOrderType: {
      type: String,
      default: null,
    },
    orderType: {
      type: String,
      required: true,
    },
    getOrderNumber: {
      type: Function,
      required: true,
    },
    formatDate: {
      type: Function,
      required: true,
    },
    getAreaName: {
      type: Function,
      required: true,
    },
    getLocationName: {
      type: Function,
      required: true,
    },
  },

  methods: {
    handleSelect(order) {
      this.$emit('select', {
        orderNumber: this.getOrderNumber(order),
        orderType: this.orderType
      });
    },
    isSelected(order) {
      return this.selectedOrderNumber === this.getOrderNumber(order)
          && this.selectedOrderType === this.orderType;
    }
  },

};
</script>
