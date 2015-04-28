$.fn.star_rating = function(options, callback) {
  var $this = $(this);
  var settings = {
    css_class: 'active',
    data_shop_id: $this.data('shop-id'),
    data_star: $this.data('star'),
    data_votes: $this.data('votes'),
  };

  if (options) { $.extend(settings, options) }

  if ($this.length) {

    // init stars activ
    $this.find('> ul > li:lt(' + settings.data_votes + ')').addClass(settings.css_class);

    // on mouseleave init the stars again
    $this.on('mouseleave', function(){
      $this.find('> ul > li').removeClass(settings.css_class);
      $this.find('> ul > li:lt(' + settings.data_votes + ')').addClass(settings.css_class);
    })

    $this.find('> ul > li').hover(function() {
      $(this).prevAll().andSelf().addClass(settings.css_class);
    }, function() {
      $(this).prevAll().andSelf().removeClass(settings.css_class);
    }).find('> .btn').on('click', function(e) {
      e.preventDefault();
      $this.closest('li').removeClass(settings.css_class);
      $.ajax({
        url: root_dir + '/shops/vote',
        data: { id: settings.data_shop_id, stars: $(this).data('star')},
        dataType: 'script',
        success: function() {
          $('#my-modal.modal').modal();
          $this.find('li').unbind();
          $this.find('.btn').attr('disabled', true);
        }
      });
    });
  }
}
