jQuery(document).ready( function() {
  jQuery('body').on('click', '#lxhm-add-room', function() {

    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      dataType: 'html',
      data:{ 
        action: 'lxhm_add_room'
      },
      success: function(response){
        jQuery('#room-container').append(response);
      }
    });
  
  });
});