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

                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Room Booking Approval</h1>

                <!-- DataTable Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Booking Records</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Room Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Action By</th>
                                        <th>Action Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->room->name ?? '-' }}</td>
                                        <td>{{ $booking->start_time }}</td>
                                        <td>{{ $booking->end_time }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($booking->status == 'approved') badge-success 
                                                @elseif($booking->status == 'rejected') badge-danger 
                                                @elseif($booking->status == 'cancelled') badge-secondary 
                                                @else badge-warning @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->approver->name ?? '-' }}</td>
                                        <td>{{ $booking->approval_time ?? '-' }}</td>
                                        <!-- Tombol "Take Action" -->
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#actionModal-{{ $booking->id }}">
                                                Take Action
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Modal untuk setiap baris -->
                                    <div class="modal fade" id="actionModal-{{ $booking->id }}" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel-{{ $booking->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h5 class="modal-title">Take Action</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <p>Detail Data</p>
                                            <ul>
                                            <li><strong>Room:</strong> {{ $booking->user->name ?? 'N/A' }}</li>
                                            <li><strong>Room:</strong> {{ $booking->room->name ?? 'N/A' }}</li>
                                            <li><strong>Purpose:</strong> {{ $booking->purpose ?? 'N/A' }}</li>
                                            <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d') }}</li>
                                            <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</li>
                                            </ul>
                                            @if($booking->files && $booking->files->count())
                                                <hr>
                                                <p><strong>Files:</strong></p>
                                                <ul>
                                                    @foreach ($booking->files as $file)
                                                        <li>
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" class="btn btn-sm btn-primary"  target="_blank">
                                                                Download {{ $file->name ?? 'File' }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                        <!-- Modal Footer -->
                                       <div class="modal-footer">
                                            @if($booking->status == 'pending')
                                                <form action="{{ route('approval.update', $booking->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                                    <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                                </form>
                                            @endif
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

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
