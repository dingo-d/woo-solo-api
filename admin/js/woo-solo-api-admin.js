jQuery(document).ready(function($) {
  'use strict';

  var $tab = $('.solo-api-options .options-wrapper__tabs .tab');
  var $content = $('.solo-api-options .tab-content');
  var $wrapperContent = $('.solo-api-options .options-wrapper__content');
  var $apiRequestContent = $('.js-solo-api-request');

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
    if ($select.val() === 'racun') {
      $select.parents('.fields__single').find('input[type="checkbox"]').prop('disabled', false);
    } else {
      $select.parents('.fields__single').find('input[type="checkbox"]').prop('checked', false);
      $select.parents('.fields__single').find('input[type="checkbox"]').prop('disabled', true);
    }
  }

  $('select[name*=solo_api_bill_offer-]').on('change', selectFiscalization);
  $('select[name*=solo_api_bill_offer-]').each(selectFiscalization);

  var maxHeight = 0;

  $content.each(function() {
    var height = $(this).outerHeight(true);
    if (height > maxHeight) {
      maxHeight = height;
    }
  });

  $wrapperContent.css('height', maxHeight);

  function makeRequest() {
    if (!$wrapperContent.hasClass('sending')) {
      $.ajax({
        type: 'POST',
        dataType: 'html',
        url: ajaxurl,
        data: {
          'action': 'get_solo_data',
          '_wpnonce': $('#_wpnonce').val(),
        },
        success: function(data) {
          $apiRequestContent.html(data);
        },
        error : function(jqXHR, textStatus, errorThrown) {
          $apiRequestContent.html( jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown );
        },
        complete : function() {
          $wrapperContent.removeClass('sending');
        }
      });
    }
  }

  $('.js-solo-api-send').on('click', function(event) {
    event.preventDefault();
    if (true) {}
    makeRequest();
  });

});
