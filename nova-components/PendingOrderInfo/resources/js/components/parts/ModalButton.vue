<template>
  <!-- Button will be mounted programmatically -->
</template>

<script>
export default {
  name: 'ModalButton',
  props: {
    resourceName: {
      type: String,
      required: true
    },
    resourceId: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      buttonRef: null
    }
  },
  mounted() {
    this.mountButton();
  },
  methods: {
    mountButton() {
      //Find the element to mount the button to
      const deliveryNoteDetail = document.querySelector('[dusk="delivery-notes-detail-component"]')?.children[0]?.children[0];
      const directSaleDetail = document.querySelector('[dusk="direct-sales-detail-component"]')?.children[0]?.children[0];
      const element = deliveryNoteDetail || directSaleDetail;

      if(element) {
        // Resource Header
        element.classList.add('flex', 'justify-between', 'items-center', 'gap-6')

        // Create wrapper container
        const wrapper = document.createElement('div');
        wrapper.classList.add('flex', 'items-center', 'gap-1');

        // Create button element
        const button = document.createElement('div');
        button.id = 'open-order-info-button';
        button.classList.add('border', 'px-2', 'py-1', 'cursor-pointer', 'whitespace-nowrap', 'rounded-sm', 'hover-button');
        button.textContent = 'Open Order info';
        button.addEventListener('click', this.openModal);

        // Initially hide the button
        button.classList.add('hidden');

        // Append elements
        wrapper.appendChild(button);
        element.appendChild(wrapper);

        // Store reference to the button
        this.buttonRef = button;
      }
    },
    openModal() {
      this.$emit('open');
    },
    show() {
      document.getElementById('open-order-info-button')?.classList.remove('hidden');
    },
    hide() {
      document.getElementById('open-order-info-button')?.classList.add('hidden');
    }
  }
}
</script>
