jQuery(document).ready(function($){
  'use strict';

  var $tab = $('.solo-api-options .options-wrapper__tabs .tab');
  var $content = $('.solo-api-options .options-wrapper__tabs .tab-content');

  function toggleTab( $currentTab ) {
    if(!$currentTab.hasClass('active')) {
      $currentTab.parents('.options-wrapper__tabs').find('.tab').removeClass('active');
      $currentTab.addClass('active');
      $('.tab-content.active').removeClass('active');
      $($currentTab.attr('href')).addClass('active');
    }
  }

  $tab.on('click', function(){
    toggleTab($(this));
  });

});
