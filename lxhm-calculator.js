jQuery(document).ready( function() {

  jQuery('body').on('click', '.lxhm-delete-room', function() {
    jQuery(this).parents('.lxhm-card').remove();
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
  })
});

function lxhmGetFormInfo() {
  var formData = {
    serverType: lxhmGetServerType(),
    rooms: lxhmGetRooms()
  };
  
  if (!lxhmInputsValid()) {
    jQuery('#lxhm-error-container').html('<div class="lxhm-error-message">Bitte füllen Sie alle benötigten Felder aus.</div>');
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
