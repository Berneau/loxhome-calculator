jQuery(document).ready( function() {

  jQuery('body').on('click', '.lxhm-delete-room', function() {
    jQuery(this).parents('.lxhm-card').remove();
  });
  
  jQuery('body').on('click', '.lxhm-delete-article', function() {
    jQuery(this).parents('.lxhm-article').remove();
  });
  
  jQuery('body').on('click', '#lxhm-calculate', function() {
    lxhmGetFormInfo();
  });
  
  jQuery('body').on('focusout', 'select[required]', function() {
    lxhmInputValid(this);
  });
  
  jQuery('body').on('change', 'select[required]', function() {
    lxhmInputValid(this);
  });
  
  jQuery('body').on('change', 'select[name="lxhm-article-type"]', function() {
    lxhmGetArticleOptions(this);
    
    lxhmResetTooltip(this);
  });
  
  jQuery('body').on('change', 'select[name="lxhm-article-option"]', function() {
    lxhmUpdateTooltip(this);
  });
  
  jQuery('body').on('change', 'select[name="lxhm-server-type"]', function() {
    lxhmEnableSelections();
    
    lxhmResetArticleOptions();
    
    lxhmShowServerWarning();
    
    lxhmGetTooltips();
    
    lxhmResetTooltips();
    
    jQuery('select[name="lxhm-article-type"]').each(function() {
      lxhmGetArticleOptions(this);
    });
  });
});

function lxhmGetFormInfo() {
  var formData = {
    serverType: lxhmGetServerType(),
    rooms: lxhmGetRooms()
  };

  if (!lxhmInputsValid()) {
    lxhmToast('error', 'Bitte füllen Sie alle benötigten Felder aus');
    return false;
  }
  
  lxhmGetProducts(formData);
}

function lxhmGetServerType() {
  return jQuery('select[name="lxhm-server-type"]').val() || null;
}

function lxhmGetRooms() {
  var roomsHtml = jQuery('.lxhm-room');
  rooms = [];
  roomsHtml.each(function() {
    rooms.push(lxhmGetRoom(this));
  })
  return rooms;
}

function lxhmGetRoom(room) {
  var roomName = jQuery(room).find('select[name="lxhm-room-name"]').val() || null;
  var articles = jQuery(room).find('.lxhm-article');
  return {
    roomName: roomName,
    articles: lxhmGetArticles(articles)
  }
}

function lxhmGetArticles(articlesHtml) {
  var articles = [];
  jQuery(articlesHtml).each(function() {
    articles.push(lxhmGetArticle(this));
  })
  return articles;
}

function lxhmGetArticle(articleHtml) {
  return {
    amount: jQuery(articleHtml).find('select[name="lxhm-article-amount"]').val(),
    type: jQuery(articleHtml).find('select[name="lxhm-article-type"]').val(),
    option: jQuery(articleHtml).find('select[name="lxhm-article-option"]').val()
  }
}

function lxhmEnableSelections() {
  jQuery('#lxhm-add-room').prop('disabled', false);
  jQuery('#lxhm-calculate').prop('disabled', false);
  jQuery('#lxhm-add-to-cart').prop('disabled', false);
}

function lxhmShowServerWarning() {
  jQuery('.lxhm-server-warning').css('display', 'inline-block');
}

function lxhmResetArticleOptions() {
  jQuery('select[name="lxhm-article-option"]').val('null');
}

function lxhmInputsValid() {
  var status = true;
  jQuery('select[required]').each(function() {
    if (!lxhmInputValid(this)) status = false;
  })
  return status;
}

function lxhmInputValid(input) {
  if (!jQuery(input).val()) {
    jQuery(input).addClass('invalid');
    return false;
  }
  jQuery(input).removeClass('invalid');
  return true;
}

function lxhmToast(type, message) {
  var html = '<div id="lxhm-toast-element" class="active">';
  if (type == 'error') html += '<svg class="error" viewBox="0 0 24 24"><path d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z" /></svg>';
  else if (type == 'success') html += '<svg class="success" viewBox="0 0 24 24"><path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z" /></svg>';
  html += message;
  html += '</div>';
  var elem = jQuery('body').append(html);
  
  setTimeout(function() {
    jQuery('#lxhm-toast-element').removeClass('active');
    setTimeout(function() {
      jQuery('#lxhm-toast-element').remove();
    }, 250);
  }, 3000);
}

function lxhmUpdateTooltip(optionSelect) {
  optionSelect = jQuery(optionSelect);
  var parent = optionSelect.parent();
  var areaSelect = parent.children('select[name="lxhm-article-type"]');
  var textTarget = parent.children('span.lxhm-tooltip').children('span.tooltip-text');
  
  if (!optionSelect.val() || !areaSelect.val() || !window.lxhmTooltips) return;
  
  var tooltip = window.lxhmTooltips[areaSelect.val()][optionSelect.val()-1];
  textTarget.html(tooltip);
}

function lxhmResetTooltips() {
  jQuery('.tooltip-text').html('Wählen Sie eine Option für nähere Informationen aus.');
}

function lxhmResetTooltip(areaElem) {
  var parent = jQuery(areaElem).parent();
  var tooltipElem = parent.children('.lxhm-tooltip').children('.tooltip-text');
  tooltipElem.html('Wählen Sie eine Option für nähere Informationen aus.');
}
