$.fn.add_read_buttons = function(options, callback) {
  var $this    = $(this);
  var settings = {
    max_length: 110,
    more_classes: 'btn btn-no-border btn-read-more btn-no-padding btn-white',
    more_icon_classes: 'color-sky roberto roberto-triangle-bottom',
    text_classes: 'hidden',
    less_classes: 'btn btn-no-border btn-read-less btn-no-padding btn-white hidden',
    less_icon_classes: 'color-sky roberto roberto-triangle-top'
  };

  if (options) { $.extend(settings, options); }

  if ($this.length) {
    if ($this.text().length > settings.max_length) {

      var the_content = $this.text();

      the_new_content = the_content.substring(0, parseInt(settings.max_length));
      the_new_content += '<button class="' + settings.more_classes + '" type="button">';
      the_new_content +=  '<i class="' + settings.more_icon_classes + '"></i>';
      the_new_content += '</button>';
      the_new_content += '<span class="' + settings.text_classes + '">';
      the_new_content += the_content.substring(parseInt(settings.max_length), $this.text().length);
      the_new_content += '</span>';
      the_new_content += '<button class="' + settings.less_classes + '" type="button">';
      the_new_content += '<i class="' + settings.less_icon_classes + '"></i>';
      the_new_content += '</button>';

      $this.html(the_new_content).removeClass('hidden');
    }
  }
}
