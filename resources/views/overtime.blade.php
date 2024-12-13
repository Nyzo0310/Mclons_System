<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Overtime Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
          /* Global Styles */
          body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }

        h2 {
            font-family: Georgia, serif;
            font-size: 30px;
            font-weight: 600;
            color: #495057;
            text-align: left; /* Aligns the title to the left */
            margin-bottom: 20px;
        }

        /* Navbar Styles */
       .navbar {
            background: linear-gradient(45deg, #007bff, #0056b3);
            padding: 10px 20px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
            margin-right: 10px;
        }

        .navbar .menu-and-logo {
            display: flex;
            align-items: center;
        }

        .navbar .username {
            display: flex;
            align-items: center;
        }

        .navbar .username i {
            margin-right: 5px;
            font-size: 1.2rem;
        }

        /* Sidebar Styles */
        .offcanvas {
            width: 300px;
            background: linear-gradient(to bottom, #333, #444);
            color: white;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5);
        }

        .offcanvas-header {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
        }

        .offcanvas-body {
            padding: 20px 10px;
        }

        /* Fancy Scrollbar */
        .offcanvas-body::-webkit-scrollbar {
            width: 8px;
        }
        .offcanvas-body::-webkit-scrollbar-thumb {
            background-color: #007bff;
            border-radius: 5px;
        }

        /* Sidebar Section Heading Styles */
        .sidebar-section {
            background-color: #444;
            color: #ffffff;
            padding: 15px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        /* Sidebar Menu Link */
        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-radius: 4px;
            margin: 5px 0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #007bff;
            color: #ffffff;
            transform: scale(1.05);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        /* User Info */
        .user-info img {
            border-radius: 50%;
            border: 2px solid #007bff;
            padding: 3px;
        }

        /* Main Content Styles */
        .main-content {
            font-family: 'Georgia', serif;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .new-button {
            font-size: 1.25rem; /* Slightly increase font size */
            padding: 10px 20px; /* Adjust padding for a larger button */
            float: left; /* Align the button to the left */
            margin-bottom: 20px; /* Add space below the button */
        }

        /* Table Styles */
        .table-wrapper {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Highlight Table Header */
        .table thead th {
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: white;
            font-weight: bold;
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding: 10px;
        }

        /* Table Cell Alignment */
        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }

        /* Button Styles */

        /* Specific styling for the "New" button */
        .new-button {
            font-size: 1.2rem; /* Slightly larger font */
            padding: 10px 20px; /* Padding for a prominent appearance */
            margin-bottom: 10px; /* Space below the button */
            background: linear-gradient(90deg, #007bff, #0056b3); /* Match gradient with the theme */
            color: white; /* Text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            transition: transform 0.2s ease; /* Smooth hover effect */
        }

        .new-button:hover {
            opacity: 0.9; /* Slightly fade */
            transform: scale(1.05); /* Slightly enlarge */
        }


        .btn-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .btn-danger {
            background: linear-gradient(90deg, #dc3545, #b02a37);
        }

        .btn-warning {
            background: linear-gradient(90deg, #ffc107, #e0a800);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="menu-and-logo">
            <a class="navbar-brand" href="#">Mclons Manpower Services</a>
            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="username">
            <i class="fas fa-user-circle"></i>
            <span>Guest</span>
        </div>
    </nav>

       <!-- Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start" id="offcanvasMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="sidebar">
                <!-- User Info -->
                <div class="user-info text-center mb-4">
                    @auth
                        <img src="{{ asset('path_to_user_icon.png') }}" alt="User Icon" class="rounded-circle" width="70">
                        <h5 class="mt-2">{{ Auth::user()->username }}</h5>
                        <span><i class="fas fa-circle text-success"></i> Online</span>
                    @else
                        <img src="{{ asset('path_to_guest_icon.png') }}" alt="Guest Icon" class="rounded-circle" width="70">
                        <h5 class="mt-2">Guest</h5>
                        <span><i class="fas fa-circle text-secondary"></i> Offline</span>
                    @endauth
                </div>

                <div class="sidebar-section">Reports</div>
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

                <div class="sidebar-section">Manage</div>
                <a href="{{ route('admin.attendance') }}"><i class="fas fa-calendar-check"></i> Attendance</a>
                <a href="#employeesSubmenu" data-bs-toggle="collapse" class="d-flex align-items-center">
                    <i class="fas fa-users"></i> Employees
                    <i class="fas fa-chevron-right ms-auto"></i>
                </a>
                <div class="collapse" id="employeesSubmenu">
                    <ul class="list-unstyled ps-4">
                        <li><a href="{{ route('admin.addEmployeeList') }}">Employee List</a></li>
                        <li><a href="{{ route('admin.overtime') }}">Overtime</a></li>
                        <li><a href="{{ route('admin.cashadvance') }}">Cash Advance</a></li>
                        <li><a href="{{ route('admin.schedule') }}">Schedules</a></li>
                    </ul>
                </div>

                <a href="{{ route('admin.deduction') }}"><i class="fas fa-dollar-sign"></i> Deductions</a>
                <a href="{{ route('admin.position') }}"><i class="fas fa-briefcase"></i> Positions</a>

                <div class="sidebar-section">Printables</div>
                <a href="{{ route('admin.payroll') }}"><i class="fas fa-print"></i> Payroll</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
<div class="container mt-5">
    <h2>Manage Overtime</h2>
    <!-- New Button -->
    <div class="d-flex justify-content-start mb-3">
        <button class="btn btn-primary new-button" data-bs-toggle="modal" data-bs-target="#newOvertimeModal">
            <i class="fas fa-plus"></i> New
        </button>
    </div>
    <!-- Table Wrapper -->
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Overtime Type</th>
                        <th>Rate Per Hour</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($overtimes->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">No data available in the table</td>
                        </tr>
                    @else
                        @foreach ($overtimes as $overtime)
                            <tr>
                                <td>{{ $overtime->Overtime_Type }}</td>
                                <td>{{ $overtime->Rate_Per_Hour }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btn-edit" 
                                            data-id="{{ $overtime->overtime_id }}" 
                                            data-type="{{ $overtime->Overtime_Type }}" 
                                            data-rate="{{ $overtime->Rate_Per_Hour }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $overtime->overtime_id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


    <!-- New Overtime Modal -->
    <div class="modal fade" id="newOvertimeModal" tabindex="-1" aria-labelledby="newOvertimeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newOvertimeModalLabel">New Overtime Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <form id="addOvertimeForm">
                        @csrf
                        <div class="mb-3">
                            <label for="overtimeType" class="form-label">Overtime Type</label>
                            <input type="text" class="form-control" id="overtimeType" name="Overtime_Type" required>
                        </div>
                        <div class="mb-3">
                            <label for="ratePerHour" class="form-label">Rate Per Hour</label>
                            <input type="number" class="form-control" id="ratePerHour" name="Rate_Per_Hour" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Overtime Modal -->
    <div class="modal fade" id="editOvertimeModal" tabindex="-1" aria-labelledby="editOvertimeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOvertimeModalLabel">Edit Overtime Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editOvertimeForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editOvertimeId" name="id">
                    <div class="mb-3">
                        <label for="editOvertimeType" class="form-label">Overtime Type</label>
                        <input type="text" class="form-control" id="editOvertimeType" name="Overtime_Type" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRatePerHour" class="form-label">Rate Per Hour</label>
                        <input type="number" class="form-control" id="editRatePerHour" name="Rate_Per_Hour" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>



    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.querySelector('#newOvertimeModal form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    const formData = new FormData(this);

    fetch(`/overtime`, {
        method: 'POST', // Ensure your Laravel route for adding overtime is using POST
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to add record.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', data.message, 'success').then(() => {
                location.reload(); // Reload the page to reflect the new data
            });
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', error.message, 'error');
        console.error(error);
    });
});


    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            // Get data attributes from the clicked button
            const id = this.dataset.id;
            const type = this.dataset.type;
            const rate = this.dataset.rate;

            // Populate the modal fields with the current data
            document.querySelector('#editOvertimeId').value = id;
            document.querySelector('#editOvertimeType').value = type;
            document.querySelector('#editRatePerHour').value = rate;

            // Show the modal
            const editModal = new bootstrap.Modal(document.querySelector('#editOvertimeModal'));
            editModal.show();
        });
    });


    document.querySelector('#editOvertimeForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const id = document.querySelector('#editOvertimeId').value;
    const formData = new FormData(this);

    fetch(`/overtime/${id}`, {
        method: 'POST', // Use POST with _method to handle Laravel's PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to update record.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire('Updated!', data.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', error.message, 'error');
        console.error(error);
    });
});


      document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.dataset.id;

        // Show a SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the deletion if the user confirms
                fetch(`/overtime/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete record.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', error.message, 'error');
                    console.error(error);
                });
            }
        });
    });
});
    </script>
</body>
</html>
