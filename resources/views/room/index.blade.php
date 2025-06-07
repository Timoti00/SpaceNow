<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .room .btn-delete-room,
        .room .btn-edit-room {
            display: none;
            z-index: 10;
            padding: 2px 6px;
            font-size: 12px;
            line-height: 1;
        }

        .room:hover .btn-delete-room,
        .room:hover .btn-edit-room {
            display: block !important;
        }
    </style>


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
                    <h2>Room Map</h2>

                    <div class="btn-group mb-3" role="group">
                        @foreach($floors as $floor)
                        <button class="btn btn-primary floor-btn" data-floor="{{ $floor->id }}">
                            {{ $floor->name }}
                        </button>
                        @endforeach
                    </div>
                                        

                    <div class="text-center">
                        <h2 id="floor-title">Floor {{ $floors->first()->name ?? '' }}</h2>
                        <a id="manage-room-btn" href="#" class="btn btn-success ms-3">
                            <i class="fas fa-tools me-1"></i> Manage
                        </a>
                    </div>

                    {{-- Map Room --}}
                    <div class="d-flex justify-content-center">
                        <div id="map" style="width:800px; height:500px; border:1px solid #ccc; position:relative; background:#f0f0f0;">
                            @foreach($rooms as $room)
                            <div 
                                class="room draggable floor-map floor-{{ $room->floor_id }}" 
                                data-id="{{ $room->id }}" 
                                style="
                                    position:absolute;
                                    left: {{ $room->position_x }}px;
                                    top: {{ $room->position_y }}px;
                                    width: {{ $room->width }}px;
                                    height: {{ $room->height }}px;
                                    background-color: {{ $room->color ?? '#007bff' }};
                                    color: white;
                                    text-align: center;
                                    line-height: {{ $room->height }}px;
                                    border-radius: 5px;
                                ">
                                {{ $room->room_code }}
                                {{-- <button class="btn btn-sm btn-danger btn-delete-room"
                                    data-id="{{ $room->id }}"
                                    style="position:absolute; top:2px; right:2px; display:none;">
                                    &times;
                                </button>
                                <button class="btn btn-sm btn-warning btn-edit-room"
                                    data-id="{{ $room->id }}"
                                    data-room_code="{{ $room->room_code }}"
                                    data-name="{{ $room->name }}"
                                    data-width="{{ $room->width }}"
                                    data-height="{{ $room->height }}"
                                    data-color="{{ $room->color }}"
                                    data-description="{{ $room->description }}"
                                    style="position:absolute; top:2px; left:2px; display:none;">
                                    ✎
                                </button> --}}
                            </div>
                            @endforeach
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
                        <span>Copyright &copy; Your Website 2020</span>
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
<!-- JQuery core (1x saja) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- JQuery UI CSS -->
<link
  rel="stylesheet"
  href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"
/>

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

<script>
    let currentFloorId = null;

    document.querySelectorAll('.floor-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedFloor = this.dataset.floor;
            const floorName = this.textContent.trim();

            // Simpan currentFloorId
            currentFloorId = selectedFloor;

            // Update judul lantai
            document.getElementById('floor-title').textContent = `Floor ${floorName}`;

            // Sembunyikan semua room
            document.querySelectorAll('.floor-map').forEach(el => {
                el.style.display = 'none';
            });

            // Tampilkan room untuk lantai terpilih
            document.querySelectorAll(`.floor-${selectedFloor}`).forEach(el => {
                el.style.display = 'block';
            });

            // Update href tombol Manage
            const manageBtn = document.getElementById('manage-room-btn');
            manageBtn.href = `/room/${selectedFloor}/map`;
        });
    });
    // Saat tombol lantai diklik
    document.querySelectorAll('.floor-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedFloor = this.dataset.floor;

            // Sembunyikan semua room
            document.querySelectorAll('.floor-map').forEach(el => {
                el.style.display = 'none';
            });

            // Tampilkan hanya room untuk lantai yang dipilih
            document.querySelectorAll(`.floor-${selectedFloor}`).forEach(el => {
                el.style.display = 'block';
            });
        });
    });

    // Opsional: Tampilkan default lantai pertama saat halaman load
    document.addEventListener('DOMContentLoaded', function () {
        const firstBtn = document.querySelector('.floor-btn');
        if (firstBtn) firstBtn.click();
    });

    document.querySelectorAll('.floor-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedFloor = this.dataset.floor;
            const floorName = this.textContent.trim();

            // Ganti judul lantai di tengah
            document.getElementById('floor-title').textContent = `Floor ${floorName}`;

            // Sembunyikan semua room
            document.querySelectorAll('.floor-map').forEach(el => {
                el.style.display = 'none';
            });

            // Tampilkan hanya room untuk lantai yang dipilih
            document.querySelectorAll(`.floor-${selectedFloor}`).forEach(el => {
                el.style.display = 'block';
            });
        });
    });

</script>

</body>

</html>