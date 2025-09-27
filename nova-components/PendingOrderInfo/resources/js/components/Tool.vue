<template>
  <div v-if="(deliveryNotes && deliveryNotes.length) || (prepaidOffers && prepaidOffers.length)" id="custom-modal">
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
      <div class="grid md:grid-cols-2 md:gap-2 ">

        <!-- Start Delivery Note Products -->
        <div class="grid-container text-black" id="delivery-note-products">
          <h2 class="font-semibold order-heading mb-2">
            Delivery Note Products {{ selectedDeliveryNoteNumber ? selectedDeliveryNoteNumber : '' }} </h2>
          <div class="product-table-container overflow-x-scroll">
            <table style="width:100%;" class="min-w-full border">
              <TableHeader :headers="['Product', 'Price Type', 'Quantity', 'Price', 'Deposit']"/>
              <tbody>
              <tr v-for="(product, index) in deliveryNoteProducts" :key="product.id || index">
                <td class="border px-2 py-1">{{ product.product_name }}</td>
                <td class="border px-2 py-1">{{ product.price_type_name }}</td>
                <td class="border px-2 py-1">{{ product.quantity }}</td>
                <td class="border px-2 py-1">{{ product.unit_price }}</td>
                <td class="border px-2 py-1">{{ product.deposit_price }}</td>
              </tr>
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
              <tr v-for="(product, index) in prepaidOfferProducts" :key="product.id || index">
                <td class="border px-2 py-1">{{ product.product_name }}</td>
                <td class="border px-2 py-1">{{ product.price_type_name }}</td>
                <td class="border px-2 py-1">{{ product.total_remaining }}</td>
                <td class="border px-2 py-1">{{ product.total_taken }}</td>
                <td class="border px-2 py-1">{{ product.price }}</td>
              </tr>
              <BlankRows :rows="prepaidOfferProducts" :quantity="5" :columnCount="5"/>
              </tbody>
            </table>
          </div>
        </div>
        <!-- End Prepaid Offer Products   -->

      </div>
      <!--End Product Grid-->

      <!--   Start button container   -->
      <div class="flex items-center gap-4 mt-4 flex-wrap text-black">
        <div v-if="selectedOrderType === 'delivery_note'">Amend Delivery Note {{ selectedDeliveryNoteNumber }}</div>
        <div v-if="selectedOrderType === 'prepaid_offer'">Convert Offer {{ selectedPrepaidOfferNumber }}</div>
        <div class="px-3 py-1.5 border close-button rounded-md" @click="closeModal">Cancel</div>
      </div>
      <!--   End button container   -->

    </div>

  </div>
</template>

<script>

import CloseButton from "./CloseButton.vue";
import TableHeader from './TableHeader.vue'
import BlankRows from './BlankRows.vue'
import OrderLoop from "./OrderLoop.vue";

export default {
  components: {
    OrderLoop,
    CloseButton,
    TableHeader,
    BlankRows,
  },

  props: ['resourceName', 'resourceId', 'panel'],

  data() {
    return {
      info: null,
      deliveryNotes: null,
      prepaidOffers: null,
      clientDetails: null,

      prepaidOfferProducts: [],
      deliveryNoteProducts: [],
      selectedDeliveryNoteNumber: null,
      selectedPrepaidOfferNumber: null,


    };
  },

  mounted() {
    this.fetchData();
    document.addEventListener('keydown', this.onEscPress);
    document.addEventListener('mousedown', this.onClickOutside);


    //Create the Button to show the modal on close
    const element = document.querySelector('[dusk="delivery-notes-detail-component"]')?.children[0]?.children[0];
    if(element) {
      element.classList.add('flex', 'justify-between', 'items-center', 'gap-6')
      const openContainer = document.createElement('div');
      openContainer.classList.add('border', 'px-2', 'py-1', 'cursor-pointer', 'hidden', 'whitespace-nowrap', 'rounded-sm', 'hover-button');
      openContainer.id = 'open-order-info-button';
      openContainer.addEventListener('click', this.openModal)
      openContainer.innerText = "Open Order info"
      element.appendChild(openContainer);
    }
  },


  methods: {

    // Api call to fetch data DNs, POs, Client Info
    fetchData() {
      Nova.request().get(`/nova-vendor/pending-order-info`, {
        params: {
          id: this.resourceId
        }
      }).then(response => {
        this.info = response?.data.info;
        this.deliveryNotes = response?.data.delivery_notes;
        this.prepaidOffers = response?.data.prepaid_offers;
        this.clientDetails = response?.data.client_info;
      });
    },

    // Api call to fetch products for selected order
    async getProductDetails(orderNumber) {
      try {
        const response = await Nova.request().get(`/nova-vendor/pending-order-info/get-custom-prods/${orderNumber}`);
        const {type, products} = response.data;


        if(type === 'prepaid_offer') {
          this.prepaidOfferProducts = products || [];
        }

        if(type === 'delivery_note') {
          this.deliveryNoteProducts = products || [];
        }

      } catch(error) {
        console.error('Failed to load product details', error);
      }
    },


    // Modal and Button Handlers
    showButton() {
      document.getElementById('open-order-info-button')?.classList.remove('hidden');
    },

    hideButton() {
      document.getElementById('open-order-info-button')?.classList.add('hidden');
    },

    openModal() {
      document.getElementById('custom-modal')?.classList.remove('hidden');
      document.addEventListener('keydown', this.onEscPress);
      document.addEventListener('mousedown', this.onClickOutside);
      this.hideButton();
    },

    closeModal() {
      document.getElementById('custom-modal')?.classList.add('hidden');
      document.removeEventListener('keydown', this.onEscPress);
      document.removeEventListener('mousedown', this.onClickOutside);
      this.showButton();
    },

    onEscPress(event) {
      if(event.key === "Escape" || event.key === "Esc") {
        this.closeModal();
      }
    },

    onClickOutside(event) {
      const modal = document.getElementById('custom-modal');
      if(modal && !modal.querySelector('div.border')?.contains(event.target)) {
        this.closeModal();
      }
    },

    // Date formatting
    covertDate(dateString) {
      if(!dateString) return '';
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = String(date.getFullYear()).slice(-2);
      return `${day}/${month}/${year}`;
    },


    // Order Selection and Product Fetching
    selectOrder({orderNumber, orderType}) {
      if(orderType === 'delivery_note') {
        this.selectedDeliveryNoteNumber = orderNumber;
        this.selectedOrderType = 'delivery_note';
        this.getProductDetails(orderNumber);
      } else
        if(orderType === 'prepaid_offer') {
          this.selectedPrepaidOfferNumber = orderNumber;
          this.selectedOrderType = 'prepaid_offer';
          this.getProductDetails(orderNumber);
        }
    },

  }
}
</script>
