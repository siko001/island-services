<template>
  <div v-if="(deliveryNotes && deliveryNotes.length) || (prepaidOffers && prepaidOffers.length)" class="overlay"></div>
  <ConversionModal :client-details="clientDetails" :currentOrderId="this.resourceIdd" :selected-prepaid-offer-number="selectedPrepaidOfferNumber" :prepaid-offer-products="prepaidOfferProducts" :order-id="orderId" @close="closeConversionModal" @submit="sendConversionRequest" @back="handleBackFromConversion"/>

  <OrderInfoModal :client-details="clientDetails" :delivery-notes="deliveryNotes" :prepaid-offers="prepaidOffers" :selected-delivery-note-number="selectedDeliveryNoteNumber" :selected-prepaid-offer-number="selectedPrepaidOfferNumber" :selected-order-type="selectedOrderType" :delivery-note-products="deliveryNoteProducts" :prepaid-offer-products="prepaidOfferProducts" :order-id="orderId" @close="closeModal" @select-order="selectOrder" @convert-offer="convertOffer"/>

  <ModalButton v-if="(deliveryNotes && deliveryNotes.length) || (prepaidOffers && prepaidOffers.length)" :resource-name="resourceName" :resource-id="resourceId" ref="modalButton" @open="openModal"/>
</template>

<script>

import ConversionModal from "./ConversionModal.vue";
import OrderInfoModal from "./OrderInfoModal.vue";
import ModalButton from "./parts/ModalButton.vue";

export default {
  components: {
    ConversionModal,
    OrderInfoModal,
    ModalButton,
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
      orderId: null,
      selectedOrderType: null,
      modalButtonRef: null,
      resourceIdd: null,
    };
  },

  mounted() {
    this.fetchData();
    document.addEventListener('keydown', this.onEscPress);

    // Set modalButtonRef to use the ModalButton component's methods
    this.modalButtonRef = {
      show: () => this.$refs.modalButton?.show(),
      hide: () => this.$refs.modalButton?.hide()
    };

    // Check if we're returning from a redirect (e.g., after amending a delivery note)
    // and show the button if needed
    this.$nextTick(() => {
      this.showButton();
    });
  },


  methods: {

    // Api call to fetch data DNs, POs, Client Info
    fetchData() {
      Nova.request().get(`/nova-vendor/pending-order-info`, {
        params: {
          id: this.resourceId,
          type: this.resourceName,
        }
      }).then(response => {
        this.info = response?.data.info;
        this.deliveryNotes = response?.data.delivery_notes;
        this.prepaidOffers = response?.data.prepaid_offers;
        this.clientDetails = response?.data.client_info;
        this.resourceIdd = this.resourceId;
      }).catch(error => {
        console.error('Failed to load initial data', error);
      });
    },

    // Api call to fetch products for selected order
    async getProductDetails(orderNumber) {
      try {
        const response = await Nova.request().get(`/nova-vendor/pending-order-info/get-custom-prods/${orderNumber}`);
        const {type, products, orderId} = response.data;


        if(type === 'prepaid_offer') {
          this.prepaidOfferProducts = products || [];
          this.orderId = orderId || null;
        }

        if(type === 'delivery_note') {
          this.deliveryNoteProducts = products || [];
          this.orderId = orderId || null;
        }

      } catch(error) {
        console.error('Failed to load product details', error);
      }
    },


    // Modal and Button Handlers
    showButton() {
      this.modalButtonRef?.show();
    },

    hideButton() {
      this.modalButtonRef?.hide();
      this.closeOverlay();
    },

    openModal() {
      document.getElementById('custom-modal')?.classList.remove('hidden');
      document.getElementById('conversion-modal')?.classList.add('hidden');
      document.addEventListener('keydown', this.onEscPress);
      this.hideButton();
      this.openOverlay();
    },

    closeModal() {
      document.getElementById('custom-modal')?.classList.add('hidden');
      document.removeEventListener('keydown', this.onEscPress);
      this.closeOverlay();
      // Add a small delay before showing the button to ensure DOM is updated
      // This is especially important after page navigation
      setTimeout(() => {
        this.showButton();
      }, 100);
    },

    onEscPress(event) {
      if(event.key === "Escape" || event.key === "Esc") {
        this.closeModal()
        this.closeConversionModal();
      }
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


    //  Prepaid Offer conversion methods -------------------------------------------------------
    checkConvertEligibility() {
      return !!(this.selectedOrderType === 'prepaid_offer' && this.selectedPrepaidOfferNumber);
    },

    convertOffer() {
      this.checkConvertEligibility() && this.openConversionModal();
    },


    closeConversionModal() {
      document.getElementById('conversion-modal')?.classList.add('hidden');
      document.removeEventListener('keydown', this.onEscPress);
      this.closeOverlay();
      setTimeout(() => {
        this.showButton();
      }, 100);
    },

    openConversionModal() {
      document.getElementById('conversion-modal')?.classList.remove('hidden');
      document.getElementById('custom-modal')?.classList.add('hidden');
    },


    closeEverything() {
      this.closeConversionModal();
      this.closeModal();
      this.closeOverlay();
    },

    sendConversionRequest(payload) {
      Nova.request().post(`/nova-vendor/pending-order-info/convert-offer/${payload.prepaidOfferId}`, payload).then(response => {

        if(response.data.result === 'success') {
          this.$inertia.reload({
            preserveScroll: true,
            preserveState: false,
          })
          Nova.$emit('refresh-resource-fields')
          Nova.$emit('refresh-resources')
          this.fetchData();
          this.closeEverything();

          Nova.success('✅ Conversion successful!')
        } else {
          Nova.error('❌ Conversion failed. Please try again.')
        }

        setTimeout(() => {
          this.showButton();
        }, 200);
      }).catch(error => {

        console.error('Conversion failed:', error)
        alert('❌ Conversion failed. Please try again.')
      })
    },

    handleBackFromConversion() {
      document.getElementById('conversion-modal')?.classList.add('hidden');
      document.getElementById('custom-modal')?.classList.remove('hidden');
      this.openOverlay();
    },

    closeOverlay() {
      document.querySelectorAll('.overlay').forEach(el => el.classList.add('hidden'));
    },

    openOverlay() {
      document.querySelectorAll('.overlay').forEach(el => el.classList.remove('hidden'));
    },


  }
}
</script>
