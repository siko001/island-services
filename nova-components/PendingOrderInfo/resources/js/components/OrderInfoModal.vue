<template>
  <div id="custom-modal" v-if="(deliveryNotes && deliveryNotes.length) || (prepaidOffers && prepaidOffers.length)">
    <div class="border px-8 py-6 shadow-black shadow-2xl rounded-md overflow-scroll">

      <!-- Start Header -->
      <div class="flex gap-6 justify-between items-center mb-2">
        <h2 class="text-xl text-black">Order info for client : <span class="font-bold">{{ clientDetails.client }}</span></h2>
        <CloseButton @close="closeModal"/>
      </div>
      <!-- End Header -->

      <!--Start Order Grid-->
      <div class="grid md:grid-cols-2 md:gap-2 ">

        <!--Start Delivery Notes -->
        <div id="delivery-note-container" class="grid-container text-black ">
          <h2 class="font-semibold order-heading mb-2"> Pending Delivery Notes </h2>
          <div class="table-container" style="overflow-x:scroll;">
            <table style="width:100%;" class="min-w-full border">
              <TableHeader :headers="['Delivery Note no.', 'Delivery Date', 'Area', 'Location']"/>
              <tbody>
              <OrderLoop :orders="deliveryNotes" :selectedOrderNumber="selectedDeliveryNoteNumber" :selectedOrderType="'delivery_note'" orderType="delivery_note" :getOrderNumber="order => order.delivery_note_number" :getOrderDate="order => order.delivery_date" :formatDate="covertDate" :getAreaName="order => order.area ? order.area.name : 'Unknown Area'" :getLocationName="order => order.location ? order.location.name : 'Unknown Location'" @select="selectOrder"/>
              <BlankRows :rows="deliveryNotes" :quantity="3" :columnCount="4"/>
              </tbody>
            </table>
          </div>
        </div>
        <!--End Delivery Notes -->

        <!--Start Prepaid Offers-->
        <div id="prepaid-offer-container" class="grid-container text-black ">
          <h2 class="font-semibold order-heading  mb-2">Client Prepaid Offer</h2>
          <div class="table-container" style="overflow-x:scroll">
            <table style="width:100%;" class="min-w-full border">
              <TableHeader :headers="['Prepaid Offer no.', 'Order Date', 'Area', 'Location']"/>
              <tbody>
              <OrderLoop :orders="prepaidOffers" :selectedOrderNumber="selectedPrepaidOfferNumber" :selectedOrderType="'prepaid_offer'" orderType="prepaid_offer" :getOrderNumber="order => order.prepaid_offer_number" :getOrderDate="order => order.order_date" :formatDate="covertDate" :getAreaName="order => order.area ? order.area.name : 'Unknown Area'" :getLocationName="order => order.location ? order.location.name : 'Unknown Location'" @select="selectOrder"/>
              <BlankRows :rows="prepaidOffers" :quantity="3" :columnCount="4"/>
              </tbody>
            </table>
          </div>
        </div>
        <!--End Prepaid Offers-->

      </div>
      <!--End Order Grid-->

      <!--Start Product Grid-->
      <div style="overflow:scroll" class="grid md:grid-cols-2 md:gap-2 ">

        <!-- Start Delivery Note Products -->
        <div class="grid-container text-black" id="delivery-note-products">
          <h2 class="font-semibold order-heading mb-2">
            Delivery Note Products {{ selectedDeliveryNoteNumber ? selectedDeliveryNoteNumber : '' }} </h2>
          <div class="product-table-container overflow-x-scroll">
            <table style="width:100%;" class="min-w-full border">
              <TableHeader :headers="['Product', 'Price Type', 'Quantity', 'Price', 'Deposit']"/>
              <tbody>
              <ProductInfo :products="deliveryNoteProducts" productType="delivery"/>
              <BlankRows :rows="deliveryNoteProducts" :quantity="5" :columnCount="5"/>
              </tbody>
            </table>
          </div>
        </div>
        <!-- End Delivery Note Products -->

        <!--  Start Prepaid Offer Products   -->
        <div class="grid-container text-black" id="prepaid-offer-products">
          <h2 class="font-semibold order-heading mb-2">
            Prepaid Offer Products {{ selectedPrepaidOfferNumber ? selectedPrepaidOfferNumber : '' }} </h2>
          <div class="product-table-container overflow-x-scroll">
            <table style="width:100%" class="min-w-full border">
              <TableHeader :headers="['Product', 'Price Type', 'Remaining', 'Taken', 'Price']"/>
              <tbody>
              <ProductInfo :products="prepaidOfferProducts" productType="prepaid"/>
              <BlankRows :rows="prepaidOfferProducts" :quantity="5" :columnCount="5"/>
              </tbody>
            </table>
          </div>
        </div>
        <!-- End Prepaid Offer Products   -->

      </div>
      <!--End Product Grid-->

      <!--   Start button container   -->
      <div class="flex items-center gap-4 mt-4 flex-wrap text-black ml-2">

        <!--Amend Button-->
        <a :href="`/admin/resources/delivery-notes/${orderId}/`" class="px-2 py-1 cursor-pointer amend-button rounded-md" v-if="selectedOrderType ===
        'delivery_note'"> Amend Delivery Note {{ selectedDeliveryNoteNumber }} </a>

        <!-- Convert Button -->
        <button @click="convertOffer" class="px-2 py-1 cursor-pointer covert-button rounded-md" v-if="selectedOrderType === 'prepaid_offer'">Convert Offer {{
            selectedPrepaidOfferNumber
          }}
        </button>

        <!-- Close Button -->
        <div class="px-2 py-1 cursor-pointer close-button rounded-md" @click="closeModal">Cancel</div>
      </div>
      <!--   End button container   -->

    </div>
  </div>
</template>

<script>
import CloseButton from "./parts/CloseButton.vue";
import TableHeader from './parts/TableHeader.vue';
import BlankRows from './parts/BlankRows.vue';
import OrderLoop from "./parts/OrderLoop.vue";
import ProductInfo from "./parts/ProductInfo.vue";

export default {
  name: 'OrderInfoModal',
  components: {
    OrderLoop,
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
    deliveryNotes: {
      type: Array,
      default: () => []
    },
    prepaidOffers: {
      type: Array,
      default: () => []
    },
    selectedDeliveryNoteNumber: {
      type: String,
      default: null
    },
    selectedPrepaidOfferNumber: {
      type: String,
      default: null
    },
    selectedOrderType: {
      type: String,
      default: null
    },
    deliveryNoteProducts: {
      type: Array,
      default: () => []
    },
    prepaidOfferProducts: {
      type: Array,
      default: () => []
    },
    orderId: {
      type: [String, Number],
      default: null
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    selectOrder(orderData) {
      this.$emit('select-order', orderData);
    },
    convertOffer() {
      this.$emit('convert-offer');
    },
    covertDate(dateString) {
      if(!dateString) return '';
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = String(date.getFullYear()).slice(-2);
      return `${day}/${month}/${year}`;
    }
  }
}
</script>
