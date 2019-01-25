jQuery(document).ready( function() {
  
  jQuery('body').on('click', '#lxhm-add-room', function() {
    
    var elem = jQuery(this);
    if (elem.is(':disabled')) return;
    elem.attr('disabled', true);
    
    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: { 
        action: 'lxhm_add_room'
      },
      success: function(response) {
        jQuery('#lxhm-room-container').append(response);
        elem.attr('disabled', false);
      },
      error: function() {
        elem.attr('disabled', false);
      }
    });
  });
  
  jQuery('body').on('click', '.lxhm-add-article', function() {
    
    var elem = jQuery(this);
    if (elem.is(':disabled')) return;
    elem.attr('disabled', true);
    
    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: { 
        action: 'lxhm_add_article'
      },
      success: function(response) {
        elem.parents('.lxhm-card').find('.lxhm-article-container').append(response);
        elem.attr('disabled', false);
      },
      error: function() {
        elem.attr('disabled', false);
      }
    });
  });

});

function lxhmGetArticleOptions(elem) {
  
  // temporarely disable option select
  var optionsSelect = jQuery(elem).parents('.lxhm-article').find('select[name="lxhm-article-option"]');
  optionsSelect.attr('disabled', true);
  
  jQuery.ajax({
    url: ajax_object.ajaxurl,
    type: 'POST',
    data: {
      action: 'lxhm_get_options',
      article: jQuery(elem).val()
    },
    success: function(response) {
      optionsSelect.html(response);
      optionsSelect.attr('disabled', false);
    },
    error: function(err) {
      console.log('error', err);
      optionsSelect.attr('disabled', false);
    }
  });
}


function lxhmGetProducts(formData) {
  // console.log(JSON.stringify(formData));
  
  jQuery.ajax({
    url: ajax_object.ajaxurl,
    type: 'POST',
    data: {
      action: 'lxhm_calculate_rooms',
      formData: JSON.stringify(formData)
    },
    success: function(response) {
      console.log(response);
    },
    error: function(err) {
      console.log('error', err);
    }
  });
}