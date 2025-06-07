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
                    <h1>Map Floor: {{ $floor->name }}</h1>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div style="display:flex; gap:20px;">

                        {{-- Form tambah room --}}
                        <div style="width: 300px;">
                            <h4>Add Room</h4>
                            <form id="roomForm" action="{{ route('room.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="floor_id" value="{{ $floor->id }}">
                                <div class="mb-3">
                                    <label>Room Code</label>
                                    <input type="text" name="room_code" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Room Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Width (m)</label>
                                    <input type="number" name="width_meter" class="form-control" value="5" step="0.1" required>
                                </div>

                                <div class="mb-3">
                                    <label>Height (m)</label>
                                    <input type="number" name="height_meter" class="form-control" value="5" step="0.1" required>
                                </div>
                                <input type="hidden" name="width" id="width_px" value="250">
                                <input type="hidden" name="height" id="height_px" value="250">
                                <div class="mb-3">
                                    <label>Color</label>
                                    <input type="color" name="color" class="form-control" value="#007bff">
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <input type="hidden" name="room_id" id="room_id">
                                <input type="hidden" name="position_x" id="position_x">
                                <input type="hidden" name="position_y" id="position_y">

                                <button type="submit" id="submitBtn" class="btn btn-primary">Add Room</button>
                            </form>
                        </div>

                        {{-- Map room --}}
                        <div id="map" style="width:800px; height:500px; border:1px solid #ccc; position:relative; background:#f0f0f0;">
                            @foreach($rooms as $room)
                            <div class="room draggable" 
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
                                    cursor: move;
                                    ">
                                {{ $room->room_code }}
                                <button class="btn btn-sm btn-danger btn-delete-room"
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
                                </button>
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
const scale = 50; // 1 meter = 50 pixels

// Saat form submit, konversi meter ke pixel
$('#roomForm').on('submit', function(e){
    e.preventDefault();

    const form = $(this);
    const roomId = $('#room_id').val();
    const isEdit = roomId !== "";

    // Konversi ukuran dari meter ke pixel
    const widthMeter = parseFloat($('input[name="width_meter"]').val()) || 0;
    const heightMeter = parseFloat($('input[name="height_meter"]').val()) || 0;
    $('#width_px').val(widthMeter * scale);
    $('#height_px').val(heightMeter * scale);

    const formData = form.serialize();

    const url = isEdit 
        ? `/room/${roomId}` 
        : form.attr('action');
    const method = isEdit ? 'PATCH' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: formData,
        success: function(response){
            location.reload(); // atau update DOM tanpa reload
        },
        error: function(xhr){
            alert('Failed to save room');
        }
    });
});

$(function(){
    $(".draggable").draggable({
        containment: "#map",
        stop: function(event, ui){
            var roomId = $(this).data('id');
            var posX = ui.position.left;
            var posY = ui.position.top;

            $.ajax({
                url: "{{ route('room.updatePosition') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: roomId,
                    position_x: posX,
                    position_y: posY,
                },
                success: function(response){
                    console.log('Position updated for room ' + roomId);
                },
                error: function(xhr){
                    alert('Failed to update position');
                }
            });
        }
    });
});
$(document).on('click', '.btn-delete-room', function(e){
    e.stopPropagation(); // agar tidak trigger draggable
    let btn = $(this);
    let roomId = btn.data('id');

    if(confirm("Are you sure you want to delete this room?")) {
        $.ajax({
            url: "/room/" + roomId,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response){
                if(response.success){
                    btn.closest('.room').remove(); // hapus elemen dari peta
                } else {
                    alert("Failed to delete room");
                }
            },
            error: function(){
                alert("Error occurred while deleting room");
            }
        });
    }
});
$(document).on('click', '.btn-edit-room', function(e) {
    e.stopPropagation(); // Mencegah draggable

    const id = $(this).data('id');

    // Request data lengkap ruangan ke controller
    $.ajax({
        url: '/room/' + id, // pastikan route ini tersedia di Laravel
        type: 'GET',
        success: function(response) {
            // Isi form dengan data dari response
            $('#room_id').val(response.id);
            $('input[name="room_code"]').val(response.room_code);
            $('input[name="name"]').val(response.name);
            $('input[name="width_meter"]').val((response.width / scale).toFixed(2));
            $('input[name="height_meter"]').val((response.height / scale).toFixed(2));
            $('input[name="color"]').val(response.color);
            $('textarea[name="description"]').val(response.description);

            // Kalau ada koordinat
            $('input[name="position_x"]').val(response.position_x);
            $('input[name="position_y"]').val(response.position_y);

            // Ganti tombol submit menjadi Update
            $('#submitBtn').text('Update Room');
        },
        error: function(xhr) {
            alert('Gagal mengambil data ruangan');
        }
    });
});


</script>

</body>

</html>