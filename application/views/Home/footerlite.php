      <footer class="">
      </footer>
  </div>
     <?php echo $this->mi_css_js->js(); ?>
     <script  type="text/javascript" >

$(document).ready(function() {
    var sidebarToggle = $('body .sidebar-toggle');
    var sidebarIcon = $('#sidebar-icon');
    var navbar = $(".toggleNavbar");
    var toggleNavbarBtn = $("#toggleNavbarBtn");
    var toggleIcon = $("#toggleIcon");
    var lastScrollTop = 0;

    $('#myLink').click(function() {
        sidebarIcon.toggleClass(sidebarToggle.length ? 'glyphicon-list glyphicon-remove' : 'glyphicon-remove glyphicon-list');
    });

    toggleNavbarBtn.click(function() {
        navbar.fadeToggle(100, function() {
            toggleIcon.toggleClass(navbar.is(":visible") ? 'fa-bars fa-arrow-up' : 'fa-arrow-up fa-bars');
        });
    });

    $(window).scroll(function() {
        var scrollTop = $(this).scrollTop();
        if (scrollTop > navbar.height() && scrollTop > lastScrollTop) {
            toggleNavbarBtn.fadeIn();
        } else {
            toggleNavbarBtn.fadeOut();
        }
        lastScrollTop = scrollTop;
    });
});



     </script>

  </body>



</html>