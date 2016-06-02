<?php wp_footer(); ?>
<footer>
  <ul class="sitemap">
    <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
    <li><a href="about.html">About</a></li>
    <li><a href="schedule.html">Schedule</a></li>
    <li><a href="instructions.html">Instructions</a></li>
    <li><a href="news.html">News &amp; Announcements</a></li>
    <li><a href="register.html">How to Register</a></li>
  </ul>

  <div class="copyright">
    Copyright &copy; 2016<br>
    <span style="font-size: 10px;">Website by <a href="http://chisenires.me">Chi Se&ntilde;ires</a> and <a href="http://iandelacruz.me">Ian De La Cruz</a></span>
  </div>

  <div class="logos">
    <a href="http://ls.ateneo.edu"><img src="<?php echo get_template_directory_uri(); ?>/img/ateneo-logo.jpg"></a>
    <a href="http://www.ibm.com/"><img src="<?php echo get_template_directory_uri(); ?>/img/ibm-logo.jpg"></a>
  </div>
</footer>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript">
var navHeight = document.getElementsByTagName('nav')[0].clientHeight;

$('a[href*="#"]:not([href="#"])').click(function (e) {
  e.preventDefault();
  if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
    var target = $(this.hash);
    target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
    if (target.length) {
      $('html, body').animate({
        scrollTop: target.offset().top - (navHeight*2)
      }, 800);
      location.hash = this.hash;
      return false;
    }
  }
});
</script>
</body>
</html>
