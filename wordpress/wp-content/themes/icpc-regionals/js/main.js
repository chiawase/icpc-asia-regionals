var navHeight = document.getElementsByTagName('nav')[0].clientHeight;

$('a[href*="#"]:not([href="#"])').click(function (e) {
  e.preventDefault();
  if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
    var target = $(this.hash);
    target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
    if (target.length) {
      $('html, body').animate({
        scrollTop: target.offset().top - (navHeight*1.5)
      }, 800);
      location.hash = this.hash;
      return false;
    }
  }
});