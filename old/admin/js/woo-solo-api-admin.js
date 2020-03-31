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

  function selectFiscalization() {
    var $select = $(this);
    console.log($select.val());
    if ($select.val() === 'racun') {
      $select.parents('.fields__single').find('input[type="checkbox"]').prop('disabled', false);
    } else {
      $select.parents('.fields__single').find('input[type="checkbox"]').prop('checked', false);
      $select.parents('.fields__single').find('input[type="checkbox"]').prop('disabled', true);
    }
  }

  $('select[name*=solo_api_bill_offer-]').on('change', selectFiscalization);
  $('select[name*=solo_api_bill_offer-]').each(selectFiscalization);

});
