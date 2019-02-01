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
        jQuery('#lxhm-room-container').append(response);
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
        elem.parents('.lxhm-card').find('.lxhm-article-container').append(response);
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
    lxhmChangeElemState(elem);
    
    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: { 
        action: 'lxhm_add_to_cart',
        products: window.lxhmProducts
      },
      success: function(response) {
        console.log(response);
        lxhmChangeElemState(elem, false);
      },
      error: function() {
        lxhmChangeElemState(elem, false);
      }
    });
  });
});

function lxhmGetArticleOptions(elem) {
  
  // temporarely disable option select
  var optionsSelect = jQuery(elem).parents('.lxhm-article').find('select[name="lxhm-article-option"]');
  lxhmChangeElemState(optionsSelect);
  
  jQuery.ajax({
    url: ajax_object.ajaxurl,
    type: 'POST',
    data: {
      action: 'lxhm_get_options',
      article: jQuery(elem).val()
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
      var parsedResponse = JSON.parse(response);
      
      // temporarely save to window obj
      console.log(parsedResponse.products);
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