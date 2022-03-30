/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************!*\
  !*** ./resources/js/delete-form.js ***!
  \*************************************/
var deleteForms = document.querySelectorAll('.delete-form');
deleteForms.forEach(function (form) {
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var accept = confirm("Are you sure you want to delete this post?");
    if (accept) e.target.submit();
  });
});
/******/ })()
;