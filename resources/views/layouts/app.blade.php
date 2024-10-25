<!-- desh(2024-10-14) -->
<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Evolve HRM</title>

        <meta content="Human Resource and Payroll Management System" name="description" />
        <meta content="Evolve Technologies Pvt Ltd" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    
        <!-- jsvectormap css -->
        <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    
        <!--Swiper slider css-->
        <link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert css-->
        <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        
        <!--datatable css-->
        <link rel="stylesheet" href="{{ asset('assets/css/datatables/dataTables.bootstrap5.min.css') }}" />
        <!--datatable responsive css-->
        <link rel="stylesheet" href="{{ asset('assets/css/datatables/responsive.bootstrap.min.css') }}" />

        <link rel="stylesheet" href="{{ asset('assets/css/datatables/buttons.dataTables.min.css') }}">

        <!-- select2 -->
        <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    
        <!-- Layout config Js -->
        <script src="{{ asset('assets/js/layout.js') }}"></script>
        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
        

        <!-- jQuery -->
	    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}" crossorigin="anonymous"></script>

        <script>
            let DataTablesForAjax = '';
            $(document).ready(function(){
                DataTablesForAjax = $('.dataTables-example').DataTable();
            })
    
            $(window).on('load', function(){
                $('#preloader').fadeOut(1000);
                $('.navbar').removeClass('wrapper-hidden');
                var x = $('.page-content').removeClass('wrapper-hidden');
                if(x){
                    setTimeout(function(){
                        $('.sidebar-expand-md').removeClass('wrapper-hidden');
                    }, 1000);
                }
            });
        </script>

        <style>
            .cursor-pointer{
                cursor: pointer;
            }
            .select2-container {
                z-index: 9999;
            }

        </style>
    </head>
    <body>

        <div id="layout-wrapper">
            <!-- Top Bar and  Page Navigation -->
            @include('layouts.topbar')
            @include('layouts.navigation')

            <div class="vertical-overlay"></div>

            <div class="main-content">
                
                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        @isset($header)
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                    {{ $header }}
                                </div>
                            </div>
                        </div>
                        @endisset
                        <!-- end page title -->

                        <!-- start Content -->
                        {{ $slot }}
                        <!-- end Content -->
                        
                    </div>
                </div>
    
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © Evolve.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Design & Develop by Evolve Technologies Pvt Ltd.
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
    
        </div>

        <!-- common functions -->
        @include('layouts.common')

        <!--start back-to-top
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        end back-to-top-->

        <!--preloader-->
        <div id="preloader">
            <div id="status">
                <div class="spinner-border text-primary avatar-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
        <script src="{{ asset('assets/js/plugins.js') }}"></script>

        <!-- prismjs plugin -->
        <script src="{{ asset('assets/libs/prismjs/prism.js') }}"></script>

        <!-- apexcharts -->
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- Vector map-->
        <script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

        <!--Swiper slider js-->
        <script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

        <!-- Dashboard init -->
        <script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>

        <!--datatable js-->
        <script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/js/other/0.1.53/vfs_fonts.js') }}"></script>
        <script src="{{ asset('assets/js/other/0.1.53/pdfmake.min.js') }}"></script>
        <script src="{{ asset('assets/js/other/3.1.3/jszip.min.js') }}"></script>

        <!-- Sweet Alerts js -->
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <!-- Sweet alert init js-->
        <script src="{{ asset('assets/js/pages/sweetalerts.init.js') }}"></script>

        <!--select2-->
        <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/select2.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

        <script>
		
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();

                $('.req').append('<span class="text-danger">*</span>');
            })

            //number only text filed
            $(document).on('keypress','.numonly', function(eve){
                if ((eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
                    eve.preventDefault();
                }
            });

            //decimal only text filed
            $(document).on('keypress','.deconly', function(eve){
                if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
                    eve.preventDefault();
                }
            });

            //datatable initialize
            function init_dataTable(selector){
                DataTablesForAjax = $(selector).DataTable({
                    pageLength: 25,
                    responsive: true,
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [
                        {extend: 'copy'},
                        {extend: 'csv'},
                        {extend: 'excel', title: 'ExampleFile'},
                        {extend: 'pdf', title: 'ExampleFile'},

                        {extend: 'print',
                        customize: function (win){
                                $(win.document.body).addClass('white-bg');
                                $(win.document.body).css('font-size', '10px');

                                $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                        }
                        }
                    ]

                });
            }

            /*
            //==============================================================
            // select2 code for disable selected item in dropdown
            //==============================================================
            // Handle the select event
            $('.select2-multiple').on('select2:select', function (e) {
                    var selectedId = e.params.data.id; // Get the selected item's ID
                    
                    // Hide the selected item in the dropdown
                    $('.select2-multiple option[value="' + selectedId + '"]').attr('disabled', true); //when we disable it can't get value to send by formData. do sth else here
                    
                    // Refresh the dropdown
                    $('.select2-multiple').select2();
            });
            
            // Handle the unselect event
            $('.select2-multiple').on('select2:unselect', function (e) {
                var unselectedId = e.params.data.id; // Get the unselected item's ID
                
                // Enable the unselected item back in the dropdown
                $('.select2-multiple option[value="' + unselectedId + '"]').attr('disabled', false);
                
                // Refresh the dropdown
                $('.select2-multiple').select2();
            });
            //==============================================================
            */
           
        </script>
    </body>
</html>
