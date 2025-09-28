<template>
  <ConversionModal :client-details="clientDetails" :selected-prepaid-offer-number="selectedPrepaidOfferNumber" :prepaid-offer-products="prepaidOfferProducts" :order-id="orderId" @close="closeConversionModal" @submit="sendConversionRequest"/>

  <OrderInfoModal :client-details="clientDetails" :delivery-notes="deliveryNotes" :prepaid-offers="prepaidOffers" :selected-delivery-note-number="selectedDeliveryNoteNumber" :selected-prepaid-offer-number="selectedPrepaidOfferNumber" :selected-order-type="selectedOrderType" :delivery-note-products="deliveryNoteProducts" :prepaid-offer-products="prepaidOfferProducts" :order-id="orderId" @close="closeModal" @select-order="selectOrder" @convert-offer="convertOffer"/>

  <ModalButton :resource-name="resourceName" :resource-id="resourceId" ref="modalButton" @open="openModal"/>
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
    };
  },

  mounted() {
    this.fetchData();
    document.addEventListener('keydown', this.onEscPress);
    document.addEventListener('mousedown', this.onClickOutside);

    // Set modalButtonRef to use the ModalButton component's methods
    this.modalButtonRef = {
      show: () => this.$refs.modalButton?.show(),
      hide: () => this.$refs.modalButton?.hide()
    };
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
    },

    openModal() {
      document.getElementById('custom-modal')?.classList.remove('hidden');
      document.getElementById('conversion-modal')?.classList.add('hidden');
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
        this.closeModal()
        this.closeConversionModal();
      }
    },

    onClickOutside(event) {
      const modal = document.getElementById('custom-modal');
      if((modal) && !modal.querySelector('div.border')?.contains(event.target)) {
        this.closeModal();
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
      document.removeEventListener('mousedown', this.onClickOutside);
      this.showButton();
    },

    openConversionModal() {
      document.getElementById('conversion-modal')?.classList.remove('hidden');
      document.getElementById('custom-modal')?.classList.add('hidden');
    },


    sendConversionRequest(payload) {
      console.log('Submitting conversion for ', payload.id, ': ', payload);
      Nova.request().post(`/nova-vendor/pending-order-info/convert-offer/${payload.id}`, payload).then(response => {
        this.closeConversionModal()
      }).catch(error => {
        console.error('Conversion failed:', error)
        alert('❌ Conversion failed. Please try again.')
      })
    },


  }
}
</script>
