/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/wp-content/plugins/woo-solo-api/assets/public/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/dev/application.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/dev/application.js":
/*!***********************************!*\
  !*** ./assets/dev/application.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_application_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/application.scss */ "./assets/dev/styles/application.scss");
/* harmony import */ var _styles_application_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_styles_application_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _scripts__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./scripts */ "./assets/dev/scripts/index.js");
/* harmony import */ var _scripts__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_scripts__WEBPACK_IMPORTED_MODULE_1__);
// Load Styles
 // Load Scripts



/***/ }),

/***/ "./assets/dev/scripts/index.js":
/*!*************************************!*\
  !*** ./assets/dev/scripts/index.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* eslint-disable import/no-extraneous-dependencies */
// $(function() {
// ---------------------------------------------------------
// OPTIONS PAGE SAVING.
// if ($('.js-create-apigee-token').length) {
//   import('./save-options').then(({ApigeeCall}) => {
//     const options = {
//       adminEmail: '.js-get-apigee-mail',
//       adminPass: '.js-get-apigee-pass',
//       adminNotice: '.js-apigee-notice',
//       adminNoticeText: '.js-apigee-notice-text',
//       nonce: '#apigee-authorization-settings_nonce',
//       actionMethod: 'set_admin_credentials',
//     };
//
//     const apigeeCall = new ApigeeCall(options);
//
//     $('.js-create-apigee-token').on('click', () => {
//       apigeeCall.createToken();
//     });
//   });
// }
// ---------------------------------------------------------
// TEST API
// if ($('.js-solo-api-send').length) {
//   import('./api-test').then(({DragOrder}) => {
//     const documentationElementDrag = new DragOrder({
//       dragWrapper: '.js-sortable',
//       dragElement: '.js-sortable-element',
//       nonce: 'documentation-order_nonce',
//       toasterElement: '.js-toaster',
//       toasterText: '.js-toaster-text',
//       ajaxAction: 'save_documentation_order_meta',
//     });
//
//     documentationElementDrag.init();
//   });
// }
// });

/***/ }),

/***/ "./assets/dev/styles/application.scss":
/*!********************************************!*\
  !*** ./assets/dev/styles/application.scss ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ })

/******/ });