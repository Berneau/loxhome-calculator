jQuery(document).ready( function() {

  jQuery('body').on('click', '.lxhm-delete-room', function() {
    jQuery(this).parents('.lxhm-card').remove();
  });

});