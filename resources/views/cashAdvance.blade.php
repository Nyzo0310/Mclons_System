<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Advance Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
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
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Main Content Styles */
        .main-content {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        /* Highlight Table Header */
        .table thead th {
            background: linear-gradient(90deg, #007bff, #0056b3); /* Blue gradient */
            color: white; /* White text for contrast */
            font-weight: bold;
            text-align: center; /* Center align text */
            border-bottom: 2px solid #0056b3; /* Add a border for separation */
            padding: 10px; /* Add padding for better spacing */
        }
             
        /* Table Cell Alignment */
        .table tbody td {
            vertical-align: middle; /* Aligns the text vertically in the center */
            text-align: center; /* Ensures center alignment of text */
        }

        /* Table Wrapper */
        .table-wrapper {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .btn-danger {
            background: linear-gradient(90deg, #dc3545, #b02a37);
        }

        .btn-success {
            background: linear-gradient(90deg, #28a745, #218838);
        }

        /* Action Buttons */
        .btn-edit {
            color: white;
            background-color: #28a745;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .btn-delete {
            color: white;
            background-color: #dc3545;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .btn-edit:hover {
            background-color: #218838;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        /* Align status column */
        .status-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px; /* Add spacing between the circle and text */
            display: inline-block;
        }

        .status-pending {
            background-color: orange;
        }

        .status-approved {
            background-color: green;
        }

        .status-rejected {
            background-color: red;
        }

        /* Modal Styles */
        .modal-header {
            background: #007bff;
            color: white;
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
            @auth
                <span>{{ Auth::user()->username }}</span>
            @else
                <span>Guest</span>
            @endauth
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
                <a href="{{ route('admin.attendanceDash') }}"><i class="fas fa-calendar-check"></i> Attendance</a>
                <a href="#employeesSubmenu" data-bs-toggle="collapse" class="d-flex align-items-center">
                    <i class="fas fa-users"></i> Employees
                    <i class="fas fa-chevron-right ms-auto"></i>
                </a>
                <div class="collapse" id="employeesSubmenu">
                    <ul class="list-unstyled ps-4">
                        <li><a href="{{ route('admin.addEmployeeList') }}">Employee List</a></li>
                 
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
    <div class="main-content">
        <h2>Cash Advance</h2>
        <div class="table-wrapper">
            <!-- Button -->
            <button id="newCashAdvanceButton" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cashAdvanceModal">
                <i class="fas fa-plus"></i> New
            </button>
            <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($cashAdvances->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">No data available</td>
                </tr>
            @else
                @foreach($cashAdvances as $cashAdvance)
                <tr>
                    <td>{{ $cashAdvance->request_date }}</td>
                    <td>{{ $cashAdvance->employee_id }}</td>
                    <td>{{ $cashAdvance->employee ? $cashAdvance->employee->first_name . ' ' . $cashAdvance->employee->last_name : 'No Employee Found' }}</td>
                    <td>{{ number_format($cashAdvance->amount, 2) }}</td>
                    <td>
                        <div class="status-wrapper">
                            <span class="status-circle {{
                                $cashAdvance->status === 'pending' ? 'status-pending' : (
                                $cashAdvance->status === 'approved' ? 'status-approved' : 'status-rejected'
                            ) }}"></span>
                            {{ ucfirst($cashAdvance->status) }}
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm me-1" onclick="editCashAdvance({{ $cashAdvance->cash_advance_id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteCashAdvance({{ $cashAdvance->cash_advance_id }})">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>


    <!-- Modal -->
<div class="modal fade" id="cashAdvanceModal" tabindex="-1" aria-labelledby="cashAdvanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cashAdvanceModalLabel">
                    <i class="fas fa-plus-circle"></i> New Cash Advance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/store">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="employee_id" class="form-label">Employee ID</label>
                        <input type="number" class="form-control" id="employee_id" name="employee_id" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="request_date" class="form-label">Request Date</label>
                        <input type="date" class="form-control" id="request_date" name="request_date" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editCashAdvanceModal" tabindex="-1" aria-labelledby="editCashAdvanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCashAdvanceModalLabel">
                    <i class="fas fa-edit"></i> Edit Cash Advance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCashAdvanceForm" method="POST">
                @csrf
                @method('PUT') <!-- Use the PUT method for updates -->
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_employee_id" class="form-label">Employee ID</label>
                        <input type="number" class="form-control" id="edit_employee_id" name="employee_id" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_request_date" class="form-label">Request Date</label>
                        <input type="date" class="form-control" id="edit_request_date" name="request_date" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="edit_amount" name="amount" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>


<script>
        function editCashAdvance(id) {
        fetch(`/cashadvance/${id}/edit`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch record');
                return response.json();
            })
            .then(data => {
                // Populate the modal fields with fetched data
                document.getElementById('edit_employee_id').value = data.employee_id;
                document.getElementById('edit_request_date').value = data.request_date;
                document.getElementById('edit_amount').value = data.amount;
                document.getElementById('edit_status').value = data.status;

                // Set the form action to the correct endpoint
                document.getElementById('editCashAdvanceForm').action = `/cashadvance/${id}`;

                // Show the modal
                const editModal = new bootstrap.Modal(document.getElementById('editCashAdvanceModal'));
                editModal.show();
            })
            .catch(error => {
                console.error(error);
                Swal.fire('Error!', 'Failed to fetch cash advance record.', 'error');
            });
    }


    function deleteCashAdvance(id) {
    console.log(`Attempting to delete cash advance with ID: ${id}`);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/cashadvance/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire('Deleted!', 'The cash advance record has been deleted.', 'success');
                    location.reload();
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Unknown error');
                    });
                }
            })
            .catch(error => {
                Swal.fire('Error!', error.message || 'Something went wrong.', 'error');
            });
        }
    });
}

function deleteCashAdvance(id) {
    console.log(`Deleting Cash Advance ID: ${id}`); // Debugging
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        allowOutsideClick: false // Prevent clicking outside the alert to close it
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/cashadvance/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The cash advance record has been deleted.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false // Keep the dialog open until the user clicks OK
                    }).then(() => {
                        location.reload(); // Reload after user confirms
                    });
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Unknown error');
                    });
                }
            })
            .catch(error => {
                Swal.fire('Error!', error.message || 'Something went wrong.', 'error');
            });
        }
    });
}


</script>
</body>
</html>
