
            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , made by ❤️ BIS
                </div>
                
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <style>
        /* Basic styles for the layout */
        .layout-wrapper {
            transition: margin-left 0.1s ease;
        }
        .layout-without-menu .layout-wrapper {
            margin-left: 0;
        }
        #layout-menu {
            width: 280px;
            transition: transform 0.3s ease;
            transform: translateX(0);
        }
        .layout-without-menu #layout-menu {
            transform: translateX(-100%);
        }
		
		.container-xxl{
		max-width: 100% !important;	
		}
    </style>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script>
        $(document).ready(function() {
            $('#swipeleft').on('click', function() {
                var layoutWrapper = $('.layout-wrapper.layout-content-navbar');
                layoutWrapper.toggleClass('layout-without-menu');
                if (layoutWrapper.hasClass('layout-without-menu')) {
                    $('.container').removeClass('container').addClass('container-fluid');
                } else {
                    $('.container-fluid').removeClass('container-fluid').addClass('container');
                }
            });
        });
    </script>
  </body>
</html>