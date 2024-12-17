<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deduction and Position Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background: #f4f7fa; /* Soft gray background for the whole page */
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
            min-height: 100vh; /* Ensures content covers the full height of the screen */
        }

        /* Table Styles */
        .table-wrapper {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .table thead th {
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: white;
            font-weight: bold;
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding: 10px;
        }
        .table tbody td {
            vertical-align: middle;
            text-align: center;
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
        
    </nav>
   
    <!-- Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start" id="offcanvasMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="sidebar">
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
        
                <!-- Logout Section -->
                <div class="sidebar-section">Account</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger text-decoration-none d-flex align-items-center">
                        <i class="fas fa-sign-out-alt"></i> <span class="ms-2">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
  
    </div>

    <!-- Main Content for Deduction Management -->
    <div class="main-content container mt-5">
        <h2>Manage Deduction</h2>
        <div class="table-wrapper">
            <!-- Button to Open Modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#deductionModal">
                <i class="fas fa-plus"></i> New
            </button>
            <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead style="background-color: #d4e7fd; color: black; font-weight: bold;">
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($deduction->isEmpty())
                <tr>
                    <td colspan="3" class="text-center">No data available in the table</td>
                </tr>
            @else
                @foreach($deduction as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->amount, 2) }}</td>
                    <td>
                        <button class="btn btn-sm btn-success edit-deduction" 
                                data-id="{{ $item->deduction_id }}" 
                                data-name="{{ $item->name }}" 
                                data-amount="{{ $item->amount }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>

                        <button class="btn btn-sm btn-danger delete-deduction" data-id="{{ $item->deduction_id }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

    <!-- Modal for Adding Deduction -->
<div id="deductionModal" class="modal fade" tabindex="-1" aria-labelledby="deductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deductionModalLabel">Add New Deduction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/AddDeduction" method="POST"  id="deductionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Description</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Deduction</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="editDeductionModal" class="modal fade" tabindex="-1" aria-labelledby="editDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeductionModalLabel">Edit Deduction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDeductionForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editDeductionId" name="deduction_id">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Description</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAmount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="editAmount" name="amount" required>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Handle form submission
    document.getElementById('deductionForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        // Get form data
        const name = document.getElementById('name').value;
        const amount = document.getElementById('amount').value;

        // Send POST request using Fetch API
        fetch('/AddDeduction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name, amount: amount })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const deductionModal = bootstrap.Modal.getInstance(document.getElementById('deductionModal'));
                deductionModal.hide();

                // Trigger SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: 'Deduction has been added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reload the page to reflect the new data
                    window.location.href = '/deduction';
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while adding the deduction.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
    document.querySelectorAll('.delete-deduction').forEach(button => {
    button.addEventListener('click', function () {
        const deductionId = this.getAttribute('data-id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/deduction/${deductionId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Deduction has been deleted.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload the page to update the table
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    });
});

// Handle Edit Button Click
document.querySelectorAll('.edit-deduction').forEach(button => {
    button.addEventListener('click', function () {
        const deductionId = this.getAttribute('data-id');
        const deductionName = this.getAttribute('data-name');
        const deductionAmount = this.getAttribute('data-amount');

        // Set form fields
        document.getElementById('editDeductionId').value = deductionId;
        document.getElementById('editName').value = deductionName;
        document.getElementById('editAmount').value = deductionAmount;

        // Set the form action
        document.getElementById('editDeductionForm').action = `/deduction/${deductionId}`;

        // Show the modal
        const editModal = new bootstrap.Modal(document.getElementById('editDeductionModal'));
        editModal.show();
    });
});

document.getElementById('editDeductionForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const actionUrl = form.action;

    fetch(actionUrl, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name: document.getElementById('editName').value,
            amount: document.getElementById('editAmount').value
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to update deduction');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', 'Deduction updated successfully.', 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error!', data.message || 'Failed to update deduction.', 'error');
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire('Error!', 'An unexpected error occurred.', 'error');
    });
});




    // Handle Delete Deduction
    document.querySelectorAll('.delete-deduction').forEach(button => {
    button.addEventListener('click', function () {
        const deductionId = this.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/deduction/${deductionId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete deduction');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'Deduction has been deleted.', 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete deduction.', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                });
            }
        });
    });
});


</script>
</body>
</html>
