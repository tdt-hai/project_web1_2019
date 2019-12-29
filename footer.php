                </div>
                <div class="container-fluid-bd">



                </div>
                <!-- Optional JavaScript -->
                <!-- jQuery -->
                <script src="./css_files/lte/jquery.min.js"></script>
                <!-- InputMask -->
                <script src="./css_files/lte/moment.min.js"></script>
                <script src="./css_files/lte/jquery.inputmask.bundle.min.js"></script>
                <!-- date-range-picker -->
                <script src="./css_files/lte/daterangepicker.js"></script>
                <!-- Bootstrap 4 -->
                <script src="./css_files/lte/bootstrap.bundle.min.js"></script>
                <!-- AdminLTE App -->
                <script src="./css_files/lte/adminlte.min.js"></script>
                <!-- AdminLTE for demo purposes -->
                <script src="../../dist/js/demo.js"></script>
                <!-- jQuery first, then Popper.js, then Bootstrap JS -->

                <script>
$(function() {
    $('#datemask').inputmask('dd/mm/yyyy', {
        'placeholder': 'dd/mm/yyyy'
    })
    $('[data-mask]').inputmask()
    $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY hh:mm A'
        }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker({

        startDate: moment().subtract(29, 'days'),
        endDate: moment()
    }, )
})

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#blah')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
                </script>
                <!-- Footer -->
                <footer class="navbar-primary navbar-dark">
                    <!-- Copyright -->
                    <div class="footer-copyright text-center py-3">© 2019 Copyright:
                        <a style="color: black;">HHN</a>
                    </div>
                    <!-- Copyright -->

                </footer>
                <!-- Footer -->
                </body>

                </html>