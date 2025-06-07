<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2</title>

    <!-- Custom fonts for this template -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
    #map-wrapper {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
    }

    #map {
        width: 100%;
        padding-top: 62.5%; /* 500/800 = 62.5% aspect ratio */
        position: relative;
        background: #f0f0f0;
        border: 1px solid #ccc;
    }
    .room {
        position: absolute;
        color: white;
        border-radius: 5px;
        overflow: hidden;
        font-size: 0.75rem;

        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        line-height: 1; /* lebih pasti */
        margin: 0;
        padding: 0;
    }
    .room h3 {
        margin: 0;
        padding: 0;
        line-height: 1;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }




    @media (max-width: 768px) {
        .room {
            font-size: 0.6rem;
        }
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

                <!-- Page Heading -->
                <h1 class="h3 mb-4 text-gray-800">Room Booking</h1>

                <!-- Filter Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="filterForm" class="form-inline">
                            <div class="form-group mx-2">
                                <label for="date" class="mr-2">Date</label>
                                @php
                                    $nowDate = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d');
                                    $nowTime = \Carbon\Carbon::now()->format('H:i');
                                    $nextHour = \Carbon\Carbon::now()->addHour()->format('H:i');
                                @endphp

                                <input 
                                    type="date" 
                                    id="date" 
                                    name="date" 
                                    class="form-control" 
                                    value="{{ request()->input('date', $nowDate) }}"
                                    min="{{ $nowDate }}"
                                >

                            </div>
                            <div class="form-group mx-2">
                                <label for="start_time" class="mr-2">Start Time</label>
                                <input 
                                    type="time" 
                                    id="start_time" 
                                    name="start_time" 
                                    class="form-control" 
                                    value="{{ request()->input('start_time', $nowTime) }}"
                                    min="{{ $nowTime }}"
                                >
                            </div>

                            <div class="form-group mx-2">
                                <label for="end_time" class="mr-2">End Time</label>
                                <input 
                                    type="time" 
                                    id="end_time" 
                                    name="end_time" 
                                    class="form-control" 
                                    value="{{ request()->input('end_time', $nextHour) }}"
                                    min="{{ $nowTime }}"
                                >       
                            </div>
                            <button type="submit" class="btn btn-primary ml-2">Search</button>
                        </form>
                    </div>
                </div>

                <div class="btn-group mb-3" role="group">
                    @foreach($floors as $floor)
                    <button class="btn btn-primary floor-btn" data-floor="{{ $floor->id }}">
                        {{ $floor->name }}
                    </button>
                    @endforeach
                </div>
                {{-- Map Room --}}

                <div class="text-center">
                    <h2 id="floor-title">Floor {{ $floors->first()->name ?? '' }}</h2>
                </div>
                <div class="row">
                    <!-- MAP COLUMN -->
                    <div class="col-lg-6 col-12 d-flex justify-content-center align-items-start mb-3">
                        <div id="map-wrapper">
                            <div id="map">
                                @foreach($allRooms as $room)
                                    <div class="room draggable floor-map floor-{{ $room->floor_id }}" 
                                        data-id="{{ $room->id }}" 
                                        style="
                                            left: {{ $room->position_x / 800 * 100 }}%;
                                            top: {{ $room->position_y / 500 * 100 }}%;
                                            width: {{ $room->width / 800 * 100 }}%;
                                            height: {{ $room->height / 500 * 100 }}%;
                                            background-color: {{ $room->color ?? '#007bff' }};
                                        ">
                                        <h3>{{ $room->room_code }}</h3>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- TABLE COLUMN -->
                    <div class="col-lg-6 col-12">
                        <div class="card shadow mb-4" style="max-height: 520px; overflow-y:auto;">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Available Rooms</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="roomsTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Room Name</th>
                                                <th>Floor</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rooms as $room)
                                            <tr>
                                                <td>{{ $room->name }}</td>
                                                <td>{{ $room->floor->name ?? '-' }}</td>
                                                <td>{{ $room->description ?? '-' }}</td>
                                                <td><span class="badge badge-success">Available</span></td>
                                                <td>
                                                    <a href="#" 
                                                    class="btn btn-sm btn-primary book-btn" 
                                                    data-room-id="{{ $room->id }}">
                                                        Book
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    <script>
            $(document).ready(function () {
                $(document).on('click', '.book-btn', function (e) {
                e.preventDefault(); // Prevent default link behavior

                const roomId = $(this).data('room-id');
                const date = $('#date').val();
                const startTime = $('#start_time').val();
                const endTime = $('#end_time').val();

                if (!date || !startTime || !endTime) {
                    alert("Please fill in date, start time, and end time.");
                    return;
                }

                const url = `{{ route('booking.create') }}?room_id=${roomId}&date=${date}&start_time=${startTime}&end_time=${endTime}`;
                window.location.href = url;
            });
        });
        let currentFloorId = null;
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

        function fetchUpdatedRoomData() {
            // Ambil parameter dari form
            const date = $('#date').val();
            const start_time = $('#start_time').val();
            const end_time = $('#end_time').val();

            $.ajax({
                url: "api/room-check", // sesuaikan route dengan route('book_room.index') jika perlu
                method: 'GET',
                data: {
                    date: date,
                    start_time: start_time,
                    end_time: end_time
                },
                success: function(response) {
                    // Perbarui tabel rooms
                    console.log("Polling response:", response);
                    const tbody = $('#roomsTable tbody');
                    tbody.empty();
                    response.rooms.forEach(room => {
                        tbody.append(`
                            <tr>
                                <td>${room.name}</td>
                                <td>${room.floor.name ?? '-'}</td>
                                <td>${room.description ?? '-'}</td>
                                <td><span class="badge badge-success">Available</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary book-btn" data-room-id="${room.id}">
                                        Book
                                    </a>
                                </td>
                            </tr>
                        `);
                    });

                    // Perbarui warna room di map
                    $('.room').each(function () {
                        const id = $(this).data('id');
                        const updatedRoom = response.allRooms.find(r => r.id == id);
                        if (updatedRoom) {
                            $(this).css('background-color', updatedRoom.color ?? '#007bff');
                        }
                    });
                },
                error: function(xhr) {
                    console.error("Polling error:", xhr.responseText);
                }
            });
        }

        // Mulai polling setiap 15 detik
        setInterval(fetchUpdatedRoomData, 10000);
       



    </script>
    

 
</body>

</html>
