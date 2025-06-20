<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - User Form</title>

    <!-- Custom fonts for this template -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layouts.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
            <div class="container-fluid">
            <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Select Room -->
                <div class="form-group">
                    <!-- Room (readonly display + hidden input) -->
                    <div class="form-group">
                        <label>Room</label>
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <input type="text" class="form-control" value="{{ $room->name }}" readonly>
                    </div>

                    <!-- Booking Date (readonly display + hidden input) -->
                    <div class="form-group">
                        <label>Booking Date</label>
                        <input type="hidden" name="booking_date" value="{{ $date }}">
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($date)->format('d M Y') }}" readonly>
                    </div>

                    <!-- Start Time (readonly display + hidden input) -->
                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="hidden" name="start_time" value="{{ $start_time }}">
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($start_time)->format('H:i') }}" readonly>
                    </div>

                    <!-- End Time (readonly display + hidden input) -->
                    <div class="form-group">
                        <label>End Time</label>
                        <input type="hidden" name="end_time" value="{{ $end_time }}">
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($end_time)->format('H:i') }}" readonly>
                    </div>


                <!-- Purpose -->
                <div class="form-group">
                    <label for="purpose">Purpose</label>
                    <textarea name="purpose" class="form-control" rows="3" required>{{ old('purpose') }}</textarea>
                </div>

                <!-- Upload Document -->
                <div class="form-group">
                    <label for="document">Upload Document (PDF only)</label>
                    <input type="file" name="document" class="form-control" accept="application/pdf" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Submit Booking
                </button>
            </form>
            </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- JQuery core (1x only) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />

    <!-- JQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
 
</body>

</html>
