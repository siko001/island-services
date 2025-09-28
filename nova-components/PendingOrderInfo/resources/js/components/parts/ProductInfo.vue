<template>
  <tr v-for="(product, index) in products" :key="product.id || index">
    <!-- Common columns for both product types -->
    <td class="border border-color row-bg text-color product-row">{{ product.product_name }}</td>
    <td class="border border-color row-bg text-color product-row">{{ product.price_type_name }}</td>

    <!-- Conditional columns based on product type -->
    <template v-if="productType === 'prepaid'">
      <td class="border border-color row-bg text-color product-row">{{ product.total_remaining }}</td>
      <td class="border border-color row-bg text-color product-row">{{ product.total_taken }}</td>
      <td class="border border-color row-bg text-color product-row">{{ product.price }}</td>
    </template>

    <template v-else-if="productType === 'delivery'">
      <td class="border border-color row-bg text-color product-row">{{ product.quantity }}</td>
      <td class="border border-color row-bg text-color product-row">{{ product.unit_price }}</td>
      <td class="border border-color row-bg text-color product-row">{{ product.deposit_price }}</td>
    </template>

    <!-- Optional "To Convert" input field -->
    <td v-if="showConvertInput" class="border border-color row-bg text-color product-row-input">
      <input min="0" :max="product.total_remaining" placeholder="0" type="number" v-model.number="product.to_convert"/>
    </td>
  </tr>
</template>

<script>
export default {
  name: 'ProductInfo',
  props: {
    products: {
      type: Array,
      default: () => []
    },
    productType: {
      type: String,
      required: true,
      validator: (value) => ['prepaid', 'delivery'].includes(value)
    },
    showConvertInput: {
      type: Boolean,
      default: false
    }
  }
}
</script>
