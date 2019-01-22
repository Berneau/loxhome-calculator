jQuery(document).ready( function() {
  
  jQuery('body').on('click', '#lxhm-add-room', function() {
    
    let elem = jQuery(this);
    if (elem.is(':disabled')) return;
    elem.attr('disabled', true);
    
    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      dataType: 'html',
      data:{ 
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
    
    let elem = jQuery(this);
    if (elem.is(':disabled')) return;
    elem.attr('disabled', true);
    
    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      dataType: 'html',
      data:{ 
        action: 'lxhm_add_article'
      },
      success: function(response) {
        console.log(this, response, elem);
        elem.parents('.lxhm-card').find('.lxhm-article-container').append(response);
        elem.attr('disabled', false);
      },
      error: function() {
        elem.attr('disabled', false);
      }
    });
  });

});