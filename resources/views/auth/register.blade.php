<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta & CSS -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SB Admin 2 - Register</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet" />
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />

    <style>
        body.bg-gradient-primary {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgb(58 59 69 / 15%);
            padding: 2rem;
            max-width: 480px;
            width: 100%;
        }

        .register-title {
            color: #4e73df;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        label {
            font-weight: 600;
        }

        /* Input focus effect */
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        /* Button */
        .btn-register {
            background-color: #4e73df;
            color: white;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-register:hover {
            background-color: #2e59d9;
            color: white;
        }

        /* Error message */
        small.text-danger {
            font-size: 0.85rem;
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="register-card">
        <h2 class="register-title">Register User & Lecturer</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Role selection -->
            <div class="form-group mb-4">
                <label for="role">Role <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-control" required onchange="toggleRoleInputs()">
                    <option value="" disabled selected>-- Select Role --</option>
                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Lecturer</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Common inputs -->
            <div class="form-group mb-4">
                <label for="name">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required />
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mb-4">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required />
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-row mb-4">
                <div class="form-group col-md-6">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" required />
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                        required />
                </div>
            </div>

            <!-- Lecturer-specific inputs -->
            <div id="dosen-inputs" style="display: none;">
                <hr />
                <h5 class="text-primary mb-3">Lecturer Data</h5>
                <div class="form-group mb-3">
                    <label for="employee_id">Employee ID (NIDN)</label>
                    <input type="text" id="employee_id" name="employee_id" class="form-control"
                        value="{{ old('employee_id') }}" />
                    @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="degree">Degree</label>
                    <input type="text" id="degree" name="degree" class="form-control" value="{{ old('degree') }}" />
                    @error('degree') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="faculty_dosen">Faculty</label>
                    <input type="text" id="faculty_dosen" name="faculty" class="form-control"
                        value="{{ old('faculty') }}" />
                    @error('faculty') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="study_program_dosen">Study Program</label>
                    <input type="text" id="study_program_dosen" name="study_program" class="form-control"
                        value="{{ old('study_program') }}" />
                    @error('study_program') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- User-specific inputs (Student) -->
            <div id="user-inputs" style="display: none;">
                <hr />
                <h5 class="text-primary mb-3">Student Data</h5>
                <div class="form-group mb-3">
                    <label for="nrp">Student ID (NRP)</label>
                    <input type="number" id="nrp" name="nrp" class="form-control" value="{{ old('nrp') }}" />
                    @error('nrp') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="faculty_user">Faculty</label>
                    <input type="text" id="faculty_user" name="faculty" class="form-control"
                        value="{{ old('faculty') }}" />
                    @error('faculty') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="study_program_user">Study Program</label>
                    <input type="text" id="study_program_user" name="study_program" class="form-control"
                        value="{{ old('study_program') }}" />
                    @error('study_program') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="batch_year">Batch Year</label>
                    <input type="text" id="batch_year" name="batch_year" class="form-control"
                        value="{{ old('batch_year') }}" />
                    @error('batch_year') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-register btn-block mt-4">Register</button>
        </form>
    </div>

    <!-- JS Script -->
    <script>
        function toggleRoleInputs() {
            const role = document.getElementById('role').value;
            document.getElementById('dosen-inputs').style.display = role === 'dosen' ? 'block' : 'none';
            document.getElementById('user-inputs').style.display = role === 'user' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleRoleInputs();
        });
    </script>
</body>

</html>
