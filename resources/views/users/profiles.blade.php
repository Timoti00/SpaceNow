<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="">

<title>SB Admin 2 - Profile Detail</title>

<!-- Custom fonts for this template -->
<link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

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

                <h1 class="h3 mb-4 text-gray-800">Profile Detail</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $profile->name ?? 'No Name' }}</h6>
                        <a href="#" class="btn btn-sm btn-primary" title="Edit Profile" data-toggle="modal" data-target="#editProfileModal">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Full Name</dt>
                            <dd class="col-sm-9">{{ $profile->profiles->full_name ?? '-' }}</dd>

                            <dt class="col-sm-3">Date of Birth</dt>
                            <dd class="col-sm-9">{{ $profile->profiles->date_of_birth ? \Carbon\Carbon::parse($profile->profiles->date_of_birth)->format('d M Y') : '-' }}</dd>

                            <dt class="col-sm-3">Gender</dt>
                            <dd class="col-sm-9">
                                {{ $profile->profiles->gender == 'L' ? 'Laki-laki' : ($profile->profiles->gender == 'P' ? 'Perempuan' : '-') }}
                            </dd>

                            <dt class="col-sm-3">Phone Number</dt>
                            <dd class="col-sm-9">{{ $profile->profiles->phone_number ?? '-' }}</dd>

                            <dt class="col-sm-3">Role</dt>
                            <dd class="col-sm-9">{{ $user->role ?? '-' }}</dd>

                            <dt class="col-sm-3">Additional Info</dt>
                            <dd class="col-sm-9">
                                @if($user->role == 'admin')
                                    Employee ID: {{ $profile->profiles->employee_id ?? '-' }}<br>
                                    Position: {{ $profile->profiles->position ?? '-' }}<br>
                                    Degree: {{ $profile->profiles->degree ?? '-' }}<br>
                                @elseif($user->role == 'dosen')
                                    Employee ID: {{ $profile->profiles->employee_id ?? '-' }}<br>
                                    Position: {{ $profile->profiles->position ?? '-' }}<br>
                                    Degree: {{ $profile->profiles->degree ?? '-' }}<br>
                                    Faculty: {{ $profile->profiles->faculty ?? '-' }}<br>
                                    Study Program: {{ $profile->profiles->study_program ?? '-' }}
                                @elseif($user->role == 'user')
                                    NRP: {{ $profile->profiles->nrp ?? '-' }}<br>
                                    Faculty: {{ $profile->profiles->faculty ?? '-' }}<br>
                                    Study Program: {{ $profile->profiles->study_program ?? '-' }}<br>
                                    Batch Year: {{ $profile->profiles->batch_year ?? '-' }}
                                @else
                                    -
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
                <!-- Modal Edit Profile -->
                <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('profile.update', $profile->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    {{-- Umum --}}
                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" name="full_name" value="{{ $profile->profiles->full_name ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth" value="{{ $profile->profiles->date_of_birth ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" name="gender">
                                            <option value="L" {{ $profile->profiles->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $profile->profiles->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number" value="{{ $profile->profiles->phone_number ?? '' }}">
                                    </div>

                                    {{-- Tambahan berdasarkan role --}}
                                    @if($user->role === 'dosen')
                                        <div class="form-group">
                                            <label for="employee_id">Employee ID</label>
                                            <input type="text" class="form-control" name="employee_id" value="{{ $profile->profiles->employee_id ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="position">Position</label>
                                            <input type="text" class="form-control" name="position" value="{{ $profile->profiles->position ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="degree">Degree</label>
                                            <input type="text" class="form-control" name="degree" value="{{ $profile->profiles->degree ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="faculty">Faculty</label>
                                            <input type="text" class="form-control" name="faculty" value="{{ $profile->profiles->faculty ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="study_program">Study Program</label>
                                            <input type="text" class="form-control" name="study_program" value="{{ $profile->profiles->study_program ?? '' }}">
                                        </div>
                                    @elseif($user->role === 'user')
                                        <div class="form-group">
                                            <label for="nrp">NRP</label>
                                            <input type="text" class="form-control" name="nrp" value="{{ $profile->profiles->nrp ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="faculty">Faculty</label>
                                            <input type="text" class="form-control" name="faculty" value="{{ $profile->profiles->faculty ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="study_program">Study Program</label>
                                            <input type="text" class="form-control" name="study_program" value="{{ $profile->profiles->study_program ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="batch_year">Batch Year</label>
                                            <input type="number" class="form-control" name="batch_year" value="{{ $profile->profiles->batch_year ?? '' }}">
                                        </div>
                                        @elseif($user->role === 'admin')
                                        <div class="form-group">
                                            <label for="employee_id">Employee ID</label>
                                            <input type="text" class="form-control" name="employee_id" value="{{ $profile->profiles->employee_id ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="position">Position</label>
                                            <input type="text" class="form-control" name="position" value="{{ $profile->profiles->position ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="degree">Degree</label>
                                            <input type="text" class="form-control" name="degree" value="{{ $profile->profiles->degree ?? '' }}">
                                        </div>

                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
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

<!-- JQuery core -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>

</html>
