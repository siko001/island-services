/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/BlankRows.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/BlankRows.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'BlankRows',
  props: {
    rows: Array,
    quantity: Number,
    columnCount: Number
  },
  computed: {
    blankRowsCount: function blankRowsCount() {
      var missing = this.quantity - (this.rows ? this.rows.length : 0);
      return missing > 0 ? missing : 0;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/CloseButton.vue?vue&type=script&lang=js":
/*!*****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/CloseButton.vue?vue&type=script&lang=js ***!
  \*****************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'CloseButton'
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/OrderLoop.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/OrderLoop.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'OrderLoop',
  props: {
    orders: {
      type: Array,
      required: true
    },
    getOrderDate: {
      type: Function,
      required: true
    },
    selectedOrderNumber: {
      type: String,
      "default": null
    },
    selectedOrderType: {
      type: String,
      "default": null
    },
    orderType: {
      type: String,
      required: true
    },
    getOrderNumber: {
      type: Function,
      required: true
    },
    formatDate: {
      type: Function,
      required: true
    },
    getAreaName: {
      type: Function,
      required: true
    },
    getLocationName: {
      type: Function,
      required: true
    }
  },
  methods: {
    handleSelect: function handleSelect(order) {
      this.$emit('select', {
        orderNumber: this.getOrderNumber(order),
        orderType: this.orderType
      });
    },
    isSelected: function isSelected(order) {
      return this.selectedOrderNumber === this.getOrderNumber(order) && this.selectedOrderType === this.orderType;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/TableHeader.vue?vue&type=script&lang=js":
/*!*****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/TableHeader.vue?vue&type=script&lang=js ***!
  \*****************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'TableHeader',
  props: {
    headers: {
      type: Array,
      required: true
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Tool.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Tool.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CloseButton_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CloseButton.vue */ "./resources/js/components/CloseButton.vue");
/* harmony import */ var _TableHeader_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TableHeader.vue */ "./resources/js/components/TableHeader.vue");
/* harmony import */ var _BlankRows_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./BlankRows.vue */ "./resources/js/components/BlankRows.vue");
/* harmony import */ var _OrderLoop_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./OrderLoop.vue */ "./resources/js/components/OrderLoop.vue");
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    OrderLoop: _OrderLoop_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    CloseButton: _CloseButton_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    TableHeader: _TableHeader_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    BlankRows: _BlankRows_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: ['resourceName', 'resourceId', 'panel'],
  data: function data() {
    return {
      info: null,
      deliveryNotes: null,
      prepaidOffers: null,
      clientDetails: null,
      prepaidOfferProducts: [],
      deliveryNoteProducts: [],
      selectedDeliveryNoteNumber: null,
      selectedPrepaidOfferNumber: null,
      orderId: null
    };
  },
  mounted: function mounted() {
    var _document$querySelect, _document$querySelect2;
    console.log('Tool mounted with props:', this.$props.panel);
    this.fetchData();
    document.addEventListener('keydown', this.onEscPress);
    document.addEventListener('mousedown', this.onClickOutside);

    //Create the Button to show the modal on close
    var deliveryNoteDetail = (_document$querySelect = document.querySelector('[dusk="delivery-notes-detail-component"]')) === null || _document$querySelect === void 0 || (_document$querySelect = _document$querySelect.children[0]) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.children[0];
    var directSaleDetail = (_document$querySelect2 = document.querySelector('[dusk="direct-sales-detail-component"]')) === null || _document$querySelect2 === void 0 || (_document$querySelect2 = _document$querySelect2.children[0]) === null || _document$querySelect2 === void 0 ? void 0 : _document$querySelect2.children[0];
    var element = deliveryNoteDetail || directSaleDetail;
    if (element) {
      // Resource Header
      element.classList.add('flex', 'justify-between', 'items-center', 'gap-6');

      // Containing Wrapper
      var wrapper = document.createElement('div');
      wrapper.classList.add('flex', 'items-center', 'gap-3');

      // Open Modal Button
      var openContainer = document.createElement('div');
      openContainer.classList.add('border', 'px-2', 'py-1', 'cursor-pointer', 'hidden', 'whitespace-nowrap', 'rounded-sm', 'hover-button');
      openContainer.id = 'open-order-info-button';
      openContainer.addEventListener('click', this.openModal);
      openContainer.innerText = "Open Order info";

      // // Edit Button
      // const editButton = document.createElement('a');
      // editButton.classList.add('border', 'px-2', 'py-1', 'cursor-pointer', 'whitespace-nowrap', 'rounded-sm', 'hover-button');
      // editButton.href = `/admin/resources/${this.resourceName}/${this.resourceId}/edit`;
      // editButton.innerText = "Edit"
      //
      // // Action Buttons
      // const actionButton = document.createElement('div');
      // actionButton.addEventListener('click', this.actionRequest);
      // actionButton.classList.add('border', 'px-2', 'py-1', 'cursor-pointer', 'whitespace-nowrap', 'rounded-sm', 'hover-button');
      // actionButton.innerText = "Actions"

      wrapper.appendChild(openContainer);
      // wrapper.appendChild(actionButton)
      // wrapper.appendChild(editButton)
      element.appendChild(wrapper);
    }
  },
  methods: {
    // Api call to fetch data DNs, POs, Client Info
    fetchData: function fetchData() {
      var _this = this;
      Nova.request().get("/nova-vendor/pending-order-info", {
        params: {
          id: this.resourceId,
          type: this.resourceName
        }
      }).then(function (response) {
        _this.info = response === null || response === void 0 ? void 0 : response.data.info;
        _this.deliveryNotes = response === null || response === void 0 ? void 0 : response.data.delivery_notes;
        _this.prepaidOffers = response === null || response === void 0 ? void 0 : response.data.prepaid_offers;
        _this.clientDetails = response === null || response === void 0 ? void 0 : response.data.client_info;
      });
    },
    // Api call to fetch products for selected order
    getProductDetails: function getProductDetails(orderNumber) {
      var _this2 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
        var response, _response$data, type, products, orderId, _t;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.p = _context.n) {
            case 0:
              _context.p = 0;
              _context.n = 1;
              return Nova.request().get("/nova-vendor/pending-order-info/get-custom-prods/".concat(orderNumber));
            case 1:
              response = _context.v;
              _response$data = response.data, type = _response$data.type, products = _response$data.products, orderId = _response$data.orderId;
              if (type === 'prepaid_offer') {
                _this2.prepaidOfferProducts = products || [];
                _this2.orderId = orderId || null;
              }
              if (type === 'delivery_note') {
                _this2.deliveryNoteProducts = products || [];
                _this2.orderId = orderId || null;
              }
              _context.n = 3;
              break;
            case 2:
              _context.p = 2;
              _t = _context.v;
              console.error('Failed to load product details', _t);
            case 3:
              return _context.a(2);
          }
        }, _callee, null, [[0, 2]]);
      }))();
    },
    // Modal and Button Handlers
    showButton: function showButton() {
      var _document$getElementB;
      (_document$getElementB = document.getElementById('open-order-info-button')) === null || _document$getElementB === void 0 || _document$getElementB.classList.remove('hidden');
    },
    hideButton: function hideButton() {
      var _document$getElementB2;
      (_document$getElementB2 = document.getElementById('open-order-info-button')) === null || _document$getElementB2 === void 0 || _document$getElementB2.classList.add('hidden');
    },
    openModal: function openModal() {
      var _document$getElementB3;
      (_document$getElementB3 = document.getElementById('custom-modal')) === null || _document$getElementB3 === void 0 || _document$getElementB3.classList.remove('hidden');
      document.addEventListener('keydown', this.onEscPress);
      document.addEventListener('mousedown', this.onClickOutside);
      this.hideButton();
    },
    closeModal: function closeModal() {
      var _document$getElementB4;
      (_document$getElementB4 = document.getElementById('custom-modal')) === null || _document$getElementB4 === void 0 || _document$getElementB4.classList.add('hidden');
      document.removeEventListener('keydown', this.onEscPress);
      document.removeEventListener('mousedown', this.onClickOutside);
      this.showButton();
    },
    onEscPress: function onEscPress(event) {
      if (event.key === "Escape" || event.key === "Esc") {
        this.closeModal();
      }
    },
    onClickOutside: function onClickOutside(event) {
      var _modal$querySelector;
      var modal = document.getElementById('custom-modal');
      if (modal && !((_modal$querySelector = modal.querySelector('div.border')) !== null && _modal$querySelector !== void 0 && _modal$querySelector.contains(event.target))) {
        this.closeModal();
      }
    },
    // Date formatting
    covertDate: function covertDate(dateString) {
      if (!dateString) return '';
      var date = new Date(dateString);
      var day = String(date.getDate()).padStart(2, '0');
      var month = String(date.getMonth() + 1).padStart(2, '0');
      var year = String(date.getFullYear()).slice(-2);
      return "".concat(day, "/").concat(month, "/").concat(year);
    },
    // Order Selection and Product Fetching
    selectOrder: function selectOrder(_ref) {
      var orderNumber = _ref.orderNumber,
        orderType = _ref.orderType;
      if (orderType === 'delivery_note') {
        this.selectedDeliveryNoteNumber = orderNumber;
        this.selectedOrderType = 'delivery_note';
        this.getProductDetails(orderNumber);
      } else if (orderType === 'prepaid_offer') {
        this.selectedPrepaidOfferNumber = orderNumber;
        this.selectedOrderType = 'prepaid_offer';
        this.getProductDetails(orderNumber);
      }
    },
    // Convert Prepaid Offer
    convertOffer: function convertOffer() {
      if (this.selectedOrderType === 'prepaid_offer' && this.selectedPrepaidOfferNumber) {
        window.location.href = "/admin/resources/prepaid-offers/".concat(this.orderId, "/edit?convert=true");
      }
    },
    actionRequest: function actionRequest() {
      Nova.request().post("/nova-vendor/pending-order-info/action-request", {
        id: this.resourceId,
        type: this.resourceName
      }).then(function (response) {
        if (response.data.success) {
          alert('Action request sent successfully.');
        } else {
          alert('Failed to send action request.');
        }
      })["catch"](function (error) {
        console.error('Error sending action request:', error);
        alert('An error occurred while sending the action request.');
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/BlankRows.vue?vue&type=template&id=1d26f8f2":
/*!*******************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/BlankRows.vue?vue&type=template&id=1d26f8f2 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);

function render(_ctx, _cache, $props, $setup, $data, $options) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($options.blankRowsCount, function (n) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("tr", {
      key: 'empty-' + n
    }, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.columnCount, function (c) {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("td", {
        key: 'empty-col-' + c + '-row-' + n,
        "class": "border px-2 py-1"
      }, " ");
    }), 128 /* KEYED_FRAGMENT */))]);
  }), 128 /* KEYED_FRAGMENT */);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/CloseButton.vue?vue&type=template&id=1e0edd2f":
/*!*********************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/CloseButton.vue?vue&type=template&id=1e0edd2f ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }

function render(_ctx, _cache, $props, $setup, $data, $options) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
    onClick: _cache[0] || (_cache[0] = function ($event) {
      return _ctx.$emit('close');
    }),
    "class": "text-red-500 cursor-pointer"
  }, _toConsumableArray(_cache[1] || (_cache[1] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    fill: "none",
    viewBox: "0 0 24 24",
    "stroke-width": "1.5",
    stroke: "currentColor",
    "class": "size-6"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
  })], -1 /* CACHED */)])));
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/OrderLoop.vue?vue&type=template&id=20d83c37":
/*!*******************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/OrderLoop.vue?vue&type=template&id=20d83c37 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);

var _hoisted_1 = ["onClick"];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.orders, function (order, index) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("tr", {
      "class": "order-parent",
      key: order.id || index,
      onClick: function onClick($event) {
        return $options.handleSelect(order);
      }
    }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
      "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["border px-2 py-1 cursor-pointer text-blue-600 underline element", {
        'selected-row': $options.isSelected(order)
      }])
    }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.getOrderNumber(order)), 3 /* TEXT, CLASS */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
      "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)([{
        'selected-row': $options.isSelected(order)
      }, "border px-2 py-1 element"])
    }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.formatDate($props.getOrderDate(order))), 3 /* TEXT, CLASS */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
      "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)([{
        'selected-row': $options.isSelected(order)
      }, "border px-2 py-1 element"])
    }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.getAreaName(order)), 3 /* TEXT, CLASS */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
      "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)([{
        'selected-row': $options.isSelected(order)
      }, "border px-2 py-1 element"])
    }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.getLocationName(order)), 3 /* TEXT, CLASS */)], 8 /* PROPS */, _hoisted_1);
  }), 128 /* KEYED_FRAGMENT */);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60":
/*!*********************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60 ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);

var _hoisted_1 = {
  "class": "bg-gray-100"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("thead", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.headers, function (title, index) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("th", {
      key: index,
      "class": "border px-2 py-1 text-left"
    }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(title), 1 /* TEXT */);
  }), 128 /* KEYED_FRAGMENT */))])]);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Tool.vue?vue&type=template&id=68ff5483":
/*!**************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Tool.vue?vue&type=template&id=68ff5483 ***!
  \**************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);

var _hoisted_1 = {
  key: 0
};
var _hoisted_2 = {
  key: 1,
  id: "custom-modal"
};
var _hoisted_3 = {
  "class": "border px-8 py-6 shadow-black shadow-2xl rounded-md overflow-scroll"
};
var _hoisted_4 = {
  "class": "flex gap-6 justify-between items-center mb-2"
};
var _hoisted_5 = {
  "class": "text-xl text-black"
};
var _hoisted_6 = {
  "class": "font-bold"
};
var _hoisted_7 = {
  "class": "grid md:grid-cols-2 md:gap-2"
};
var _hoisted_8 = {
  id: "delivery-note-container",
  "class": "grid-container text-black"
};
var _hoisted_9 = {
  "class": "table-container",
  style: {
    "overflow-x": "scroll"
  }
};
var _hoisted_10 = {
  style: {
    "width": "100%"
  },
  "class": "min-w-full border"
};
var _hoisted_11 = {
  id: "prepaid-offer-container",
  "class": "grid-container text-black"
};
var _hoisted_12 = {
  "class": "table-container",
  style: {
    "overflow-x": "scroll"
  }
};
var _hoisted_13 = {
  style: {
    "width": "100%"
  },
  "class": "min-w-full border"
};
var _hoisted_14 = {
  style: {
    "overflow": "scroll"
  },
  "class": "grid md:grid-cols-2 md:gap-2"
};
var _hoisted_15 = {
  "class": "grid-container text-black",
  id: "delivery-note-products"
};
var _hoisted_16 = {
  "class": "font-semibold order-heading mb-2"
};
var _hoisted_17 = {
  "class": "product-table-container overflow-x-scroll"
};
var _hoisted_18 = {
  style: {
    "width": "100%"
  },
  "class": "min-w-full border"
};
var _hoisted_19 = {
  "class": "border product-row"
};
var _hoisted_20 = {
  "class": "border product-row"
};
var _hoisted_21 = {
  "class": "border product-row"
};
var _hoisted_22 = {
  "class": "border product-row"
};
var _hoisted_23 = {
  "class": "border product-row"
};
var _hoisted_24 = {
  "class": "grid-container text-black",
  id: "prepaid-offer-products"
};
var _hoisted_25 = {
  "class": "font-semibold order-heading mb-2"
};
var _hoisted_26 = {
  "class": "product-table-container overflow-x-scroll"
};
var _hoisted_27 = {
  style: {
    "width": "100%"
  },
  "class": "min-w-full border"
};
var _hoisted_28 = {
  "class": "border product-row"
};
var _hoisted_29 = {
  "class": "border product-row"
};
var _hoisted_30 = {
  "class": "border product-row"
};
var _hoisted_31 = {
  "class": "border product-row"
};
var _hoisted_32 = {
  "class": "border product-row"
};
var _hoisted_33 = {
  "class": "flex items-center gap-4 mt-4 flex-wrap text-black ml-2"
};
var _hoisted_34 = ["href"];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_CloseButton = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("CloseButton");
  var _component_TableHeader = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TableHeader");
  var _component_OrderLoop = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("OrderLoop");
  var _component_BlankRows = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("BlankRows");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [$props.panel ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.panel.name), 1 /* TEXT */)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $data.deliveryNotes && $data.deliveryNotes.length || $data.prepaidOffers && $data.prepaidOffers.length ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Start Header "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", _hoisted_5, [_cache[2] || (_cache[2] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Order info for client : ", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", _hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.clientDetails.client), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_CloseButton, {
    onClose: $options.closeModal
  }, null, 8 /* PROPS */, ["onClose"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" End Header "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("Start Order Grid"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("Start Delivery Notes "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [_cache[3] || (_cache[3] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", {
    "class": "font-semibold order-heading mb-2"
  }, " Pending Delivery Notes ", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("table", _hoisted_10, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TableHeader, {
    headers: ['Delivery Note no.', 'Delivery Date', 'Area', 'Location']
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tbody", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_OrderLoop, {
    orders: $data.deliveryNotes,
    selectedOrderNumber: $data.selectedDeliveryNoteNumber,
    selectedOrderType: 'delivery_note',
    orderType: "delivery_note",
    getOrderNumber: function getOrderNumber(order) {
      return order.delivery_note_number;
    },
    getOrderDate: function getOrderDate(order) {
      return order.delivery_date;
    },
    formatDate: $options.covertDate,
    getAreaName: function getAreaName(order) {
      return order.area ? order.area.name : 'Unknown Area';
    },
    getLocationName: function getLocationName(order) {
      return order.location ? order.location.name : 'Unknown Location';
    },
    onSelect: $options.selectOrder
  }, null, 8 /* PROPS */, ["orders", "selectedOrderNumber", "getOrderNumber", "getOrderDate", "formatDate", "getAreaName", "getLocationName", "onSelect"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_BlankRows, {
    rows: $data.deliveryNotes,
    quantity: 3,
    columnCount: 4
  }, null, 8 /* PROPS */, ["rows"])])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("End Delivery Notes "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("Start Prepaid Offers"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [_cache[4] || (_cache[4] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", {
    "class": "font-semibold order-heading mb-2"
  }, "Client Prepaid Offer", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("table", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TableHeader, {
    headers: ['Prepaid Offer no.', 'Order Date', 'Area', 'Location']
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tbody", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_OrderLoop, {
    orders: $data.prepaidOffers,
    selectedOrderNumber: $data.selectedPrepaidOfferNumber,
    selectedOrderType: 'prepaid_offer',
    orderType: "prepaid_offer",
    getOrderNumber: function getOrderNumber(order) {
      return order.prepaid_offer_number;
    },
    getOrderDate: function getOrderDate(order) {
      return order.order_date;
    },
    formatDate: $options.covertDate,
    getAreaName: function getAreaName(order) {
      return order.area ? order.area.name : 'Unknown Area';
    },
    getLocationName: function getLocationName(order) {
      return order.location ? order.location.name : 'Unknown Location';
    },
    onSelect: $options.selectOrder
  }, null, 8 /* PROPS */, ["orders", "selectedOrderNumber", "getOrderNumber", "getOrderDate", "formatDate", "getAreaName", "getLocationName", "onSelect"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_BlankRows, {
    rows: $data.prepaidOffers,
    quantity: 3,
    columnCount: 4
  }, null, 8 /* PROPS */, ["rows"])])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("End Prepaid Offers")]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("End Order Grid"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("Start Product Grid"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Start Delivery Note Products "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", _hoisted_16, " Delivery Note Products " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.selectedDeliveryNoteNumber ? $data.selectedDeliveryNoteNumber : ''), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("table", _hoisted_18, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TableHeader, {
    headers: ['Product', 'Price Type', 'Quantity', 'Price', 'Deposit']
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tbody", null, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($data.deliveryNoteProducts, function (product, index) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("tr", {
      key: product.id || index
    }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_19, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.product_name), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_20, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.price_type_name), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_21, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.quantity), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_22, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.unit_price), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_23, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.deposit_price), 1 /* TEXT */)]);
  }), 128 /* KEYED_FRAGMENT */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_BlankRows, {
    rows: $data.deliveryNoteProducts,
    quantity: 5,
    columnCount: 5
  }, null, 8 /* PROPS */, ["rows"])])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" End Delivery Note Products "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("  Start Prepaid Offer Products   "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_24, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", _hoisted_25, " Prepaid Offer Products " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.selectedPrepaidOfferNumber ? $data.selectedPrepaidOfferNumber : ''), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_26, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("table", _hoisted_27, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TableHeader, {
    headers: ['Product', 'Price Type', 'Remaining', 'Taken', 'Price']
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tbody", null, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($data.prepaidOfferProducts, function (product, index) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("tr", {
      "class": "overflow-scroll",
      key: product.id || index
    }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_28, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.product_name), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_29, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.price_type_name), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_30, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.total_remaining), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_31, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.total_taken), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_32, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(product.price), 1 /* TEXT */)]);
  }), 128 /* KEYED_FRAGMENT */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_BlankRows, {
    rows: $data.prepaidOfferProducts,
    quantity: 5,
    columnCount: 5
  }, null, 8 /* PROPS */, ["rows"])])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" End Prepaid Offer Products   ")]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("End Product Grid"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("   Start button container   "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_33, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("Amend Button"), _ctx.selectedOrderType === 'delivery_note' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("a", {
    key: 0,
    href: "/admin/resources/delivery-notes/".concat($data.orderId, "/edit"),
    "class": "px-2 py-1 cursor-pointer amend-button rounded-md"
  }, " Amend Delivery Note " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.selectedDeliveryNoteNumber), 9 /* TEXT, PROPS */, _hoisted_34)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Convert Button "), _ctx.selectedOrderType === 'prepaid_offer' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", {
    key: 1,
    onClick: _cache[0] || (_cache[0] = function () {
      return $options.convertOffer && $options.convertOffer.apply($options, arguments);
    }),
    "class": "px-2 py-1 cursor-pointer covert-button rounded-md"
  }, "Convert Offer " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.selectedPrepaidOfferNumber), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Close Button "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "px-2 py-1 cursor-pointer close-button rounded-md",
    onClick: _cache[1] || (_cache[1] = function () {
      return $options.closeModal && $options.closeModal.apply($options, arguments);
    })
  }, "Cancel")]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("   End button container   ")])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)], 64 /* STABLE_FRAGMENT */);
}

/***/ }),

/***/ "./node_modules/vue-loader/dist/exportHelper.js":
/*!******************************************************!*\
  !*** ./node_modules/vue-loader/dist/exportHelper.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, exports) => {


Object.defineProperty(exports, "__esModule", ({ value: true }));
// runtime helper for setting properties on components
// in a tree-shakable way
exports["default"] = (sfc, props) => {
    const target = sfc.__vccOpts || sfc;
    for (const [key, val] of props) {
        target[key] = val;
    }
    return target;
};


/***/ }),

/***/ "./resources/css/tool.css":
/*!********************************!*\
  !*** ./resources/css/tool.css ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/js/components/BlankRows.vue":
/*!***********************************************!*\
  !*** ./resources/js/components/BlankRows.vue ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _BlankRows_vue_vue_type_template_id_1d26f8f2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./BlankRows.vue?vue&type=template&id=1d26f8f2 */ "./resources/js/components/BlankRows.vue?vue&type=template&id=1d26f8f2");
/* harmony import */ var _BlankRows_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./BlankRows.vue?vue&type=script&lang=js */ "./resources/js/components/BlankRows.vue?vue&type=script&lang=js");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_BlankRows_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_BlankRows_vue_vue_type_template_id_1d26f8f2__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/components/BlankRows.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/BlankRows.vue?vue&type=script&lang=js":
/*!***********************************************************************!*\
  !*** ./resources/js/components/BlankRows.vue?vue&type=script&lang=js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BlankRows_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BlankRows_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./BlankRows.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/BlankRows.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/BlankRows.vue?vue&type=template&id=1d26f8f2":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/BlankRows.vue?vue&type=template&id=1d26f8f2 ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BlankRows_vue_vue_type_template_id_1d26f8f2__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BlankRows_vue_vue_type_template_id_1d26f8f2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./BlankRows.vue?vue&type=template&id=1d26f8f2 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/BlankRows.vue?vue&type=template&id=1d26f8f2");


/***/ }),

/***/ "./resources/js/components/CloseButton.vue":
/*!*************************************************!*\
  !*** ./resources/js/components/CloseButton.vue ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CloseButton_vue_vue_type_template_id_1e0edd2f__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CloseButton.vue?vue&type=template&id=1e0edd2f */ "./resources/js/components/CloseButton.vue?vue&type=template&id=1e0edd2f");
/* harmony import */ var _CloseButton_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CloseButton.vue?vue&type=script&lang=js */ "./resources/js/components/CloseButton.vue?vue&type=script&lang=js");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_CloseButton_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_CloseButton_vue_vue_type_template_id_1e0edd2f__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/components/CloseButton.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/CloseButton.vue?vue&type=script&lang=js":
/*!*************************************************************************!*\
  !*** ./resources/js/components/CloseButton.vue?vue&type=script&lang=js ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_CloseButton_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_CloseButton_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./CloseButton.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/CloseButton.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/CloseButton.vue?vue&type=template&id=1e0edd2f":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/CloseButton.vue?vue&type=template&id=1e0edd2f ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_CloseButton_vue_vue_type_template_id_1e0edd2f__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_CloseButton_vue_vue_type_template_id_1e0edd2f__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./CloseButton.vue?vue&type=template&id=1e0edd2f */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/CloseButton.vue?vue&type=template&id=1e0edd2f");


/***/ }),

/***/ "./resources/js/components/OrderLoop.vue":
/*!***********************************************!*\
  !*** ./resources/js/components/OrderLoop.vue ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _OrderLoop_vue_vue_type_template_id_20d83c37__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./OrderLoop.vue?vue&type=template&id=20d83c37 */ "./resources/js/components/OrderLoop.vue?vue&type=template&id=20d83c37");
/* harmony import */ var _OrderLoop_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./OrderLoop.vue?vue&type=script&lang=js */ "./resources/js/components/OrderLoop.vue?vue&type=script&lang=js");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_OrderLoop_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_OrderLoop_vue_vue_type_template_id_20d83c37__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/components/OrderLoop.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/OrderLoop.vue?vue&type=script&lang=js":
/*!***********************************************************************!*\
  !*** ./resources/js/components/OrderLoop.vue?vue&type=script&lang=js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_OrderLoop_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_OrderLoop_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./OrderLoop.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/OrderLoop.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/OrderLoop.vue?vue&type=template&id=20d83c37":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/OrderLoop.vue?vue&type=template&id=20d83c37 ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_OrderLoop_vue_vue_type_template_id_20d83c37__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_OrderLoop_vue_vue_type_template_id_20d83c37__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./OrderLoop.vue?vue&type=template&id=20d83c37 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/OrderLoop.vue?vue&type=template&id=20d83c37");


/***/ }),

/***/ "./resources/js/components/TableHeader.vue":
/*!*************************************************!*\
  !*** ./resources/js/components/TableHeader.vue ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TableHeader_vue_vue_type_template_id_6f15fd60__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TableHeader.vue?vue&type=template&id=6f15fd60 */ "./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60");
/* harmony import */ var _TableHeader_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TableHeader.vue?vue&type=script&lang=js */ "./resources/js/components/TableHeader.vue?vue&type=script&lang=js");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_TableHeader_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_TableHeader_vue_vue_type_template_id_6f15fd60__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/components/TableHeader.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/TableHeader.vue?vue&type=script&lang=js":
/*!*************************************************************************!*\
  !*** ./resources/js/components/TableHeader.vue?vue&type=script&lang=js ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TableHeader_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TableHeader_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TableHeader.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/TableHeader.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60 ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TableHeader_vue_vue_type_template_id_6f15fd60__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TableHeader_vue_vue_type_template_id_6f15fd60__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TableHeader.vue?vue&type=template&id=6f15fd60 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/TableHeader.vue?vue&type=template&id=6f15fd60");


/***/ }),

/***/ "./resources/js/components/Tool.vue":
/*!******************************************!*\
  !*** ./resources/js/components/Tool.vue ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Tool_vue_vue_type_template_id_68ff5483__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Tool.vue?vue&type=template&id=68ff5483 */ "./resources/js/components/Tool.vue?vue&type=template&id=68ff5483");
/* harmony import */ var _Tool_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Tool.vue?vue&type=script&lang=js */ "./resources/js/components/Tool.vue?vue&type=script&lang=js");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Tool_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Tool_vue_vue_type_template_id_68ff5483__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/components/Tool.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/Tool.vue?vue&type=script&lang=js":
/*!******************************************************************!*\
  !*** ./resources/js/components/Tool.vue?vue&type=script&lang=js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Tool.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Tool.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/Tool.vue?vue&type=template&id=68ff5483":
/*!************************************************************************!*\
  !*** ./resources/js/components/Tool.vue?vue&type=template&id=68ff5483 ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_template_id_68ff5483__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_template_id_68ff5483__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Tool.vue?vue&type=template&id=68ff5483 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/Tool.vue?vue&type=template&id=68ff5483");


/***/ }),

/***/ "./resources/js/tool.js":
/*!******************************!*\
  !*** ./resources/js/tool.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_Tool__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/Tool */ "./resources/js/components/Tool.vue");

Nova.booting(function (app, store) {
  app.component('pending-order-info', _components_Tool__WEBPACK_IMPORTED_MODULE_0__["default"]);
});

/***/ }),

/***/ "vue":
/*!**********************!*\
  !*** external "Vue" ***!
  \**********************/
/***/ ((module) => {

module.exports = Vue;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/tool": 0,
/******/ 			"css/tool": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkisland_services_pending_order_info"] = self["webpackChunkisland_services_pending_order_info"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/tool"], () => (__webpack_require__("./resources/js/tool.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/tool"], () => (__webpack_require__("./resources/css/tool.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;