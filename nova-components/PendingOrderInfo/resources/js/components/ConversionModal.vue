<template>
  <div class="hidden" id="conversion-modal">
    <div class=" border-color border px-8 py-6  rounded-md overflow-scroll">

      <!-- Start Header -->
      <div class="flex gap-6 justify-between items-start mb-2">
        <div class="mb-2">
          <h2 class="text-xl ttext-color">Client : <span class="font-bold">{{ clientDetails?.client ?? "" }}</span></h2>
          <h2 class="text-xl ttext-color">Converting : <span class="font-bold">{{ selectedPrepaidOfferNumber ?? "" }}</span></h2>
        </div>
        <CloseButton width="1.8" height="1.8" @close="closeConversionModal"/>
      </div>
      <!-- End Header -->

      <form @submit.prevent="submitConversion" class="product-conversion-table-container overflow-x-scroll text-black">

        <table style="width:100%" class="min-w-full border">
          <TableHeader :headers="['Product', 'Price Type', 'Remaining', 'Taken', 'Price', 'To Convert']"/>
          <tbody>
          <ProductInfo :products="prepaidOfferProducts" productType="prepaid" :showConvertInput="true"/>
          <BlankRows :rows="prepaidOfferProducts" :quantity="6" :columnCount="6"/>
          </tbody>
        </table>
      </form>

      <div class="flex items-center gap-4 mt-6 mb-6 flex-wrap text-black">

        <!-- Convert Button -->
        <button type="button" @click="submitConversion" class="px-2 py-1 cursor-pointer covert-button rounded-md">
          Convert Offer {{ selectedPrepaidOfferNumber }}
        </button>

        <!-- Close Button -->
        <div class="px-2 py-1 cursor-pointer close-button rounded-md" @click="closeConversionModal">Close</div>

        <!-- Back Button -->
        <div class="px-2 py-1 cursor-pointer close-button rounded-md" @click="back">Back</div>
      </div>

    </div>
  </div>
</template>

<script>
import CloseButton from "./parts/CloseButton.vue";
import TableHeader from './parts/TableHeader.vue'
import BlankRows from './parts/BlankRows.vue'
import ProductInfo from './parts/ProductInfo.vue'

export default {
  name: 'ConversionModal',
  components: {
    CloseButton,
    TableHeader,
    BlankRows,
    ProductInfo,
  },
  props: {
    clientDetails: {
      type: Object,
      default: () => ({})
    },
    selectedPrepaidOfferNumber: {
      type: String,
      default: null
    },
    prepaidOfferProducts: {
      type: Array,
      default: () => []
    },
    orderId: {
      type: [String, Number],
      default: null
    },
    currentOrderId: {
      type: [String, Number],
      default: null
    },

    orderType: {
      type: String,
      default: null
    },
  },
  methods: {

    back() {
      this.$emit('back');
    },

    closeConversionModal() {
      this.$emit('close');
    },
    submitConversion() {
      let errors = []
      const productsToSubmit = this.prepaidOfferProducts.filter(p => (p.to_convert ?? 0));

      if(productsToSubmit.length === 0) {
        alert('Please enter at least one product to convert.')
        return
      }

      productsToSubmit.forEach(product => {
        const value = product.to_convert ?? 0
        const min = 0
        const max = product.total_remaining

        if(value < min || value > max) {
          errors.push(`❌${product.product_name}: Allowed range is ${min}–${max}`)
        }
      })

      if(errors.length > 0) {
        alert(errors.join('\n'))
        return
      }
      this.$emit('submit', {
        prepaidOfferId: this.orderId,
        order_number: this.selectedPrepaidOfferNumber,
        orderId: this.currentOrderId,
        products: productsToSubmit,
        orderType: this.orderType,
      });


    }
  }
}
</script>
