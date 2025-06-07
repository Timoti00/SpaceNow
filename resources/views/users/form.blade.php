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
                    <h1 class="h3 mb-4 text-gray-800">
                        {{ isset($users) ? 'Edit User' : 'Add User' }}
                    </h1>

                    <form action="{{ isset($users) ? route('users.update', $users) : route('users.store') }}" method="POST">
                        @csrf
                        @if(isset($users))
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $users->name ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $users->email ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $users->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('role', $users->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Password {{ isset($users) ? '(leave blank if unchanged)' : '' }}</label>
                            <input type="password" name="password" id="password" class="form-control" {{ isset($users) ? '' : 'required' }}>

                            <ul id="password-criteria" class="mt-2 mb-0" style="list-style: none; padding-left: 0;">
                                <li id="char-length" class="text-danger"><i class="fas fa-times"></i> At least 8 characters</li>
                                <li id="uppercase" class="text-danger"><i class="fas fa-times"></i> Contains uppercase letter</li>
                                <li id="number" class="text-danger"><i class="fas fa-times"></i> Contains a number</li>
                                <li id="symbol" class="text-danger"><i class="fas fa-times"></i> Contains a symbol (!@#$...)</li>
                            </ul>

                            <small id="password-strength" class="form-text text-muted"></small>
                        </div>

                        @if(!isset($users))
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <small id="confirm-password-error" class="form-text text-danger"></small>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            {{ isset($users) ? 'Update' : 'Save' }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
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
 <script>
    function validatePasswordCriteria(password) {
        return {
            charLength: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            symbol: /[^A-Za-z0-9]/.test(password)
        };
    }

    function updateCriteriaUI(criteria) {
        const criteriaMap = {
            charLength: '#char-length',
            uppercase: '#uppercase',
            number: '#number',
            symbol: '#symbol'
        };

        Object.entries(criteriaMap).forEach(([key, selector]) => {
            const $el = $(selector);
            if (criteria[key]) {
                $el.removeClass('text-danger').addClass('text-success');
                $el.find('i').removeClass('fa-times').addClass('fa-check');
            } else {
                $el.removeClass('text-success').addClass('text-danger');
                $el.find('i').removeClass('fa-check').addClass('fa-times');
            }
        });
    }

    function getStrengthLabel(score) {
        if (score >= 4) return { label: 'Strong', class: 'text-success' };
        if (score === 3) return { label: 'Medium', class: 'text-warning' };
        return { label: 'Weak', class: 'text-danger' };
    }

    $(document).ready(function () {
        const $password = $('#password');
        const $confirmPassword = $('#password_confirmation');
        const $strengthText = $('#password-strength');
        const $confirmError = $('#confirm-password-error');

        $password.on('input', function () {
            const val = $password.val();
            const criteria = validatePasswordCriteria(val);
            updateCriteriaUI(criteria);

            const score = Object.values(criteria).filter(Boolean).length;
            const { label, class: cls } = getStrengthLabel(score);

            $strengthText.text('Password Strength: ' + label)
                        .removeClass('text-success text-warning text-danger')
                        .addClass(cls);
        });

        $('form').on('submit', function (e) {
            const val = $password.val();
            const confirm = $confirmPassword.length ? $confirmPassword.val() : null;

            // Jika password diisi, validasi harus dijalankan
            if (val.length > 0) {
                const criteria = validatePasswordCriteria(val);
                const allValid = Object.values(criteria).every(Boolean);

                if (!allValid) {
                    e.preventDefault();
                    $strengthText.text('Password must meet all of the criteria above.')
                                .removeClass()
                                .addClass('text-danger');
                    return;
                }

                if (confirm !== null && val !== confirm) {
                    e.preventDefault();
                    $confirmError.text('Confirm Password must match Password.');
                    return;
                } else {
                    $confirmError.text('');
                }
            } else {
                // Jika password kosong saat edit, clear error
                $strengthText.text('');
                $confirmError.text('');
            }
        });

    });

</script>



</body>

</html>
