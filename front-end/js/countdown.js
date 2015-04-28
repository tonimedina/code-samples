$.fn.countdown = function(options, callback) {

  var $this    = $(this); // Custom 'this' selector
  var settings = {'date': null, 'format': 'on', 'id': null};

  if (options) { $.extend(settings, options); };

  function countdownProcessor() {

    var expirationDate = Date.parse(settings.date);
        expirationDate = expirationDate / 1000;
    var currentDate    = Math.floor($.now() / 1000);

    if (currentDate > expirationDate) {
      callback.call(this);
      clearInterval(interval);
    };

    var seconds = expirationDate - currentDate;
    var days    = Math.floor(seconds / (60 * 60 * 24)); // Calculate the number of days

    seconds -= days * 60 * 60 * 24; // Update the seconds variable with number of days removed

    var hours = Math.floor(seconds / (60 * 60));

    seconds -= hours * 60 * 60; // Update the seconds variable with number of hours removed

    var minutes = Math.floor(seconds / 60);

    seconds -= minutes * 60; // Update the seconds variable with number of minutes removed

    if (settings.format == 'on') {
      hours   = pad(hours, 2);
      minutes = pad(minutes, 2);
      seconds = pad(seconds, 2);
    };

    if(!isNaN(hours) && !isNaN(minutes) && !isNaN(seconds)){
      $('.end_date_countdown').fadeIn();

      // Update the countdown's html values
      $this.find('.hours').text(hours);
      $this.find('.minutes').text(minutes, 2);
      $this.find('.seconds').text(seconds, 2);
    }
  }

  countdownProcessor(); // Run the function

  interval = setInterval(countdownProcessor, 1000); // Loop the function
}
