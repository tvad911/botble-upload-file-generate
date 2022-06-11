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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 42);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./platform/plugins/lease-contract/resources/assets/js/lease-contract-file-admin.js":
/*!******************************************************************************************!*\
  !*** ./platform/plugins/lease-contract/resources/assets/js/lease-contract-file-admin.js ***!
  \******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$(document).ready(function () {
  $('.btn_select_file').on('click', function (e) {
    e.preventDefault();
    $("#contract_files:hidden").trigger('click');
  });
  $('input#contract_files').change(function () {
    var files = $(this)[0].files;
    $('.count-contract-file').text("(".concat(files.length, " file select)"));
  });

  var initSortable = function initSortable() {
    var el = document.getElementById('list-files-items');
    Sortable.create(el, {
      group: 'contract_files',
      // or { name: "...", pull: [true, false, clone], put: [true, false, array] }
      sort: true,
      // sorting inside list
      delay: 0,
      // time in milliseconds to define when the sorting should start
      disabled: false,
      // Disables the sortable if set to true.
      store: null,
      // @see Store
      animation: 150,
      // ms, animation speed moving items when sorting, `0` — without animation
      handle: '.file-contract-item',
      ghostClass: 'sortable-ghost',
      // Class name for the drop placeholder
      chosenClass: 'sortable-chosen',
      // Class name for the chosen item
      dataIdAttr: 'data-id',
      forceFallback: false,
      // ignore the HTML5 DnD behaviour and force the fallback to kick in
      fallbackClass: 'sortable-fallback',
      // Class name for the cloned DOM Element when using forceFallback
      fallbackOnBody: false,
      // Appends the cloned DOM Element into the Document's Body
      scroll: true,
      // or HTMLElement
      scrollSensitivity: 30,
      // px, how near the mouse must be to an edge to start scrolling.
      scrollSpeed: 10,
      // px
      // dragging ended
      onEnd: function onEnd() {
        updateItems();
      }
    });
  };

  initSortable();

  var updateItems = function updateItems() {
    var items = [];
    $.each($('.file-contract-item'), function (index, widget) {
      //$(widget).data('id', index);
      items.push({
        id: $(widget).data('id'),
        name: $(widget).data('name'),
        folder: $(widget).data('folder'),
        mime_type: $(widget).data('mimetype'),
        url: $(widget).data('url'),
        options: $(widget).data('options')
      });
    });
    $('#lease_contract_file-data').val(JSON.stringify(items));
  };

  var list_file_contract = $('.list-files-contract');
  var edit_contract_modal = $('#edit-file-item');
  $('.reset-file').on('click', function (event) {
    event.preventDefault();
    $('.list-files-contract .file-contract-item').remove();
    updateItems();
    $(this).addClass('hidden');
  });
  list_file_contract.on('click', '.file-contract-item', function () {
    var id = $(this).data('id');
    var folder = $(this).data('folder');
    var url = $(this).data('url');
    $('#delete-file-item').data('id', id);
    $('#update-file-item').data('id', id);
    $('#file-item-description').val($(this).data('options'));
    $('#download-file-item').data('link', "".concat(folder, "/").concat(url));
    edit_contract_modal.modal('show');
  });
  edit_contract_modal.on('click', '#delete-file-item', function (event) {
    event.preventDefault();
    edit_contract_modal.modal('hide');
    list_file_contract.find('.file-contract-item[data-id=' + $(this).data('id') + ']').remove();
    updateItems();

    if (list_file_contract.find('.file-contract-item').length === 0) {
      $('.reset-file').addClass('hidden');
    }
  });
  edit_contract_modal.on('click', '#update-file-item', function (event) {
    event.preventDefault();
    edit_contract_modal.modal('hide');
    list_file_contract.find('.file-contract-item[data-id=' + $(this).data('id') + ']').data('options', $('#file-item-description').val());
    updateItems();
  });
  edit_contract_modal.on('click', '#download-file-item', function (event) {
    event.preventDefault();
    var base = $(this).data('base');
    var link = $(this).data('link');
    var valFileDownloadPath = "".concat(base, "/storage/").concat(link);
    window.open(valFileDownloadPath, '_blank');
  });
});

/***/ }),

/***/ 42:
/*!************************************************************************************************!*\
  !*** multi ./platform/plugins/lease-contract/resources/assets/js/lease-contract-file-admin.js ***!
  \************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\OpenServer\domains\flexhome.doc\platform\plugins\lease-contract\resources\assets\js\lease-contract-file-admin.js */"./platform/plugins/lease-contract/resources/assets/js/lease-contract-file-admin.js");


/***/ })

/******/ });