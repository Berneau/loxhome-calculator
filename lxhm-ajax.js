jQuery(document).ready( function() {
  
  jQuery('body').on('click', '#lxhm-add-room', function() {
    
    var elem = jQuery(this);
    if (elem.is(':disabled')) return;
    lxhmChangeElemState(elem);
    
    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: { 
        action: 'lxhm_add_room'
      },
      success: function(response) {
        jQuery('#lxhm-room-container').prepend(response);
        lxhmChangeElemState(elem, false);
      },
      error: function() {
        lxhmChangeElemState(elem, false);
      }
    });
  });
  
  jQuery('body').on('click', '.lxhm-add-article', function() {
    
    var elem = jQuery(this);
    if (elem.is(':disabled')) return;
    lxhmChangeElemState(elem);
    
    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: { 
        action: 'lxhm_add_article'
      },
      success: function(response) {
        elem.parents('.lxhm-card').find('.lxhm-article-container').prepend(response);
        lxhmChangeElemState(elem, false);
      },
      error: function() {
        lxhmChangeElemState(elem, false);
      }
    });
  });

  jQuery('body').on('click', '#lxhm-add-to-cart', function() {
    
    var elem = jQuery(this);
    if (elem.is(':disabled')) return;
    
    if (!window.lxhmProducts) {
      lxhmToast('error', 'Bitte kalkulieren Sie zuerst Ihre Auswahl');
      return false;
    }
    
    lxhmChangeElemState(elem);
    
    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: { 
        action: 'lxhm_add_to_cart',
        products: window.lxhmProducts
      },
      success: function() {
        lxhmChangeElemState(elem, false);
        lxhmToast('success', 'Artikel zum Warenkorb hinzugefügt');
        jQuery('#lxhm-cart-link').addClass('visible');
      },
      error: function() {
        lxhmChangeElemState(elem, false);
        lxhmToast('error', 'Fehler beim Verarbeiten der Artikel');
      }
    });
  });
});

function lxhmGetArticleOptions(elem) {
  
  // temporarely disable option select
  var optionsSelect = jQuery(elem).parents('.lxhm-article').find('select[name="lxhm-article-option"]');
  lxhmChangeElemState(optionsSelect);
  
  var serverType = jQuery('select[name="lxhm-server-type"]').val();
  if (!serverType) return;
  
  jQuery.ajax({
    url: ajax_object.ajaxurl,
    type: 'POST',
    data: {
      action: 'lxhm_get_options',
      article: jQuery(elem).val(),
      serverType: serverType
    },
    success: function(response) {
      optionsSelect.html(response);
      lxhmChangeElemState(optionsSelect, false);
    },
    error: function(err) {
      console.log('error', err);
      lxhmChangeElemState(optionsSelect, false);
    }
  });
}


function lxhmGetProducts(formData) {
  
  jQuery.ajax({
    url: ajax_object.ajaxurl,
    type: 'POST',
    data: {
      action: 'lxhm_calculate_rooms',
      formData: JSON.stringify(formData)
    },
    success: function(response) {
      // console.log(response);
      var parsedResponse = JSON.parse(response);
      
      // temporarely save to window obj
      window.lxhmProducts = parsedResponse.products;
      jQuery('#lxhm-product-container').html(parsedResponse.html);
    },
    error: function(err) {
      console.log('error', err);
    }
  });
}

function lxhmChangeElemState(elem, disable = true) {
  elem.attr('disabled', disable);
}