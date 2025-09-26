<template>
  <div v-if="(deliveryNotes && deliveryNotes.length) || (prepaidOffers && prepaidOffers.length)" id="custom-modal">
    <div class="border px-8 py-6 shadow-black shadow-2xl rounded-md overflow-scroll">

      <div class="flex gap-6 justify-between items-center mb-2">

        <h2 class="text-xl text-black">Order info for client : <span class="font-bold">{{ clientDetails.client }}</span></h2>

        <div @click="closeModal" class="text-red-500 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
          </svg>
        </div>

      </div>

      <div class="grid md:grid-cols-2 md:gap-2 ">

        <div id="delivery-note-container" class="grid-container text-black ">
          <h2 class="font-semibold order-heading mb-2"> Pending Delivery Notes </h2>
          <div class="table-container" style="overflow-x:scroll;">
            <table style="width:100%;" class="min-w-full border">
              <thead class="bg-gray-100">
              <tr>
                <th class="border px-2 py-1 text-left">Delivery Note no.</th>
                <th class="border px-2 py-1 text-left">Delivery Date</th>
                <th class="border px-2 py-1 text-left">Area</th>
                <th class="border px-2 py-1 text-left">Location</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(offer, index) in deliveryNotes" :key="offer.id || index">
                <td class="border px-2 py-1 cursor-pointer text-blue-600 underline" @click="getProductDetails(offer.delivery_note_number)">
                  {{ offer.delivery_note_number }}
                </td>
                <td class="border px-2 py-1">{{ covertDate(offer.delivery_date) }}</td>
                <td class="border px-2 py-1">{{ mapAreaToName(offer.customer_area) }}</td>
                <td class="border px-2 py-1">{{ mapLocationToName(offer.customer_location) }}</td>
              </tr>
              <!-- Pad with empty rows if less than 5 -->
              <tr v-for="n in (3 - deliveryNotes.length)" v-if="deliveryNotes && deliveryNotes.length < 3" :key="'empty-'+n">
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
              </tr>
              </tbody>

            </table>
          </div>

        </div>

        <div id="prepaid-offer-container" class="grid-container text-black ">
          <h2 class="font-semibold order-heading  mb-2">Client Prepaid Offer</h2>
          <div class="table-container" style="overflow-x:scroll">
            <table style="width:100%;" class="min-w-full border">
              <thead class="bg-gray-100">
              <tr>
                <th class="border px-2 py-1 text-left">Prepaid Offer no.</th>
                <th class="border px-2 py-1 text-left">Order Date</th>
                <th class="border px-2 py-1 text-left">Area</th>
                <th class="border px-2 py-1 text-left">Location</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(offer, index) in prepaidOffers" :key="offer.id || index">
                <td class="border px-2 py-1 cursor-pointer text-blue-600 underline" @click="getProductDetails(offer.prepaid_offer_number)">
                  {{ offer.prepaid_offer_number }}
                </td>
                <td class="border px-2 py-1">{{ covertDate(offer.order_date) }}</td>
                <td class="border px-2 py-1">{{ mapAreaToName(offer.customer_area) }}</td>
                <td class="border px-2 py-1">{{ mapLocationToName(offer.customer_location) }}</td>
              </tr>

              <!-- Pad with empty rows if less than 5 -->
              <tr v-for="n in (3 - prepaidOffers.length)" v-if="prepaidOffers && prepaidOffers.length < 3" :key="'prepaid-empty-'+n">
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
              </tr>
              </tbody>
            </table>
          </div>

        </div>

      </div>

      <!--Product Grid-->
      <div class="grid md:grid-cols-2 md:gap-2 ">

        <!-- Delivery Note Products -->
        <div class="grid-container text-black" id="delivery-note-products">
          <h2 class="font-semibold order-heading mb-2">Delivery Note Products</h2>
          <div class="product-table-container overflow-x-scroll">
            <table style="width:100%;" class="min-w-full border">
              <thead class="bg-gray-100">
              <tr>
                <th class="border px-2 py-1 text-left">Product</th>
                <th class="border px-2 py-1 text-left">Price Type</th>
                <th class="border px-2 py-1 text-left">Quantity</th>
                <th class="border px-2 py-1 text-left">Price</th>
                <th class="border px-2 py-1 text-left">Deposit</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(product, index) in deliveryNoteProducts" :key="product.id || index">
                <td class="border px-2 py-1">{{ product.product_name }}</td>
                <td class="border px-2 py-1">{{ product.price_type_name }}</td>
                <td class="border px-2 py-1">{{ product.quantity }}</td>
                <td class="border px-2 py-1">{{ product.unit_price }}</td>
                <td class="border px-2 py-1">{{ product.deposit_price }}</td>
              </tr>
              <!-- Pad with empty rows if less than 5 -->
              <tr v-for="n in (5 - (deliveryNoteProducts ? deliveryNoteProducts.length : 0))" v-if="deliveryNoteProducts && deliveryNoteProducts.length < 5" :key="'empty-'+n">
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!--   Prepaid Offer Products   -->
        <div class="grid-container text-black" id="prepaid-offer-products">
          <h2 class="font-semibold order-heading mb-2">Prepaid Offer Products</h2>
          <div class="product-table-container overflow-x-scroll">
            <table style="width:100%" class="min-w-full border">
              <thead class="bg-gray-100">
              <tr>
                <th class="border px-2 py-1 text-left">Product</th>
                <th class="border px-2 py-1 text-left">Price Type</th>
                <th class="border px-2 py-1 text-left">Remaining</th>
                <th class="border px-2 py-1 text-left">Taken</th>
                <th class="border px-2 py-1 text-left">Price</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(product, index) in prepaidOfferProducts" :key="product.id || index">
                <td class="border px-2 py-1">{{ product.product_name }}</td>
                <td class="border px-2 py-1">{{ product.price_type_name }}</td>
                <td class="border px-2 py-1">{{ product.total_remaining }}</td>
                <td class="border px-2 py-1">{{ product.total_taken }}</td>
                <td class="border px-2 py-1">{{ product.price }}</td>
              </tr>

              <!-- Pad with empty rows if less than 5 -->
              <tr v-for="n in (5 - prepaidOfferProducts.length)" v-if="prepaidOfferProducts && prepaidOfferProducts.length < 5" :key="'empty-'+n">
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
                <td class="border px-2 py-1">&nbsp;</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
      <div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>

  </div>
</template>

<script>

export default {
  props: ['resourceName', 'resourceId', 'panel'],

  data() {
    return {
      info: null,
      deliveryNotes: null,
      prepaidOffers: null,
      clientDetails: null,
      areaLocation: {
        areas: [],
        locations: [],
      },
      prepaidOfferProducts: [],
      deliveryNoteProducts: [],
      isMobile: window.innerWidth < 768,
    };
  },

  mounted() {
    this.fetchData();
    document.addEventListener('keydown', this.onEscPress);
    document.addEventListener('mousedown', this.onClickOutside);

    const element = document.querySelector('[dusk="delivery-notes-detail-component"]')?.children[0]?.children[0];
    if(element) { //Create the Button to show the modal on close
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
        this.areaLocation.areas = response?.data.area_location.areas;
        this.areaLocation.locations = response?.data.area_location.locations;

      });
    },


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


    mapAreaToName(id) {
      return this.areaLocation.areas[id] || 'Unknown Area';
    },

    mapLocationToName(id) {
      return this.areaLocation.locations[id] || 'Unknown Location';
    },

    covertDate(dateString) {
      if(!dateString) return '';
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = String(date.getFullYear()).slice(-2);
      return `${day}/${month}/${year}`;
    },


    async getProductDetails(orderNumber) {
      try {
        const response = await Nova.request().get(`/nova-vendor/pending-order-info/get-custom-prods/${orderNumber}`);
        const {type, products} = response.data;

        console.log(response.data)

        if(type === 'prepaid_offer') {
          this.prepaidOfferProducts = products || [];
        }

        if(type === 'delivery_note') {
          this.deliveryNoteProducts = products || [];
        }

      } catch(error) {
        console.error('Failed to load product details', error);
      }
    }


  }
}
</script>
