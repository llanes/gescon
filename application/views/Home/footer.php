  <a href="#" class="back-to-top" id="myScroll" >
    <i class="fa fa-chevron-up"></i>
    </a>
    <footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1
    </div>
    <strong>Copyright &copy; <span id="currentYear"></span> <a href="">GESCOM</a>.</strong> Todos los derechos Reservados.

</footer>

  </div>
     <?php echo $this->mi_css_js->js(); ?>
     <script  type="text/javascript" >
        // Obtener el año actual
        var currentYear = new Date().getFullYear();

        // Actualizar el contenido del elemento con el año actual
        document.getElementById('currentYear').textContent = currentYear;


       <?php if ($this->session->userdata('alerSession')) {  ?>
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 5000
                });

                Toast.fire({
                  type: 'success',
                  title: 'Inicio Session Exitosamente!'
                })
       <?php 
              $this->session->set_userdata('alerSession',false);
        }
        ?>

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


        // Verifica si el navegador admite Service Workers y PWAs
            if ('serviceWorker' in navigator) {
            // Registra el Service Worker
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?php echo base_url('bower_components/service-worker.js')?>').then(function(registration) {
                console.log('ServiceWorker registrado con éxito:', registration);
                }, function(err) {
                console.error('Error al registrar el ServiceWorker:', err);
                });
            });
            }


            // Este bloque de código se movió fuera del Service Worker
            // Ya que las comprobaciones de compatibilidad deben realizarse en el contexto de la página web
            window.onload = function() {
            // Verifica si el navegador admite Service Workers y PWAs
            
            if ('serviceWorker' in navigator && 'InstallTrigger' in window) {
                // Verifica si la aplicación aún no está instalada
                if (!window.matchMedia('(display-mode: standalone)').matches) {
                // Muestra el enlace de instalación
                var installButton = document.getElementById('installButton');
                installButton.style.display = 'block';
                }
            }
            };

     </script>


  </body>
</html>