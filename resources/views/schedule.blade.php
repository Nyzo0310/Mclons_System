<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Schedule Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- Font Awesome -->
    </head>
<style>
    /* Global Styles */
    body {
        font-family: 'Arial', sans-serif;
        background: #f8f9fa; /* Light gray background */
        color: #495057;
        margin: 0;
    }

    h2 {
        font-family: Georgia, serif;
        font-size: 30px;
        font-weight: bold;
        color: #495057;
        margin-bottom: 20px;
    }

    /* Navbar Styles */
    .navbar {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
        color: white !important;
        font-weight: bold;
        font-size: 1.5rem;
    }

    /* Table Wrapper */
    .table-wrapper {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    /* Table Styles */
    .table {
        text-align: center;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .table thead th {
        background: linear-gradient(90deg, #007bff, #0056b3);
        color: white;
        text-transform: uppercase;
        font-weight: bold;
        padding: 10px;
    }

    .table tbody tr:hover {
        background-color: #e9ecef;
        transition: background-color 0.3s ease;
    }

    .table tbody td {
        padding: 12px;
        border: 1px solid #dee2e6;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    /* Buttons */
    .btn-primary, .btn-danger, .btn-success {
        border: none;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(90deg, #007bff, #0056b3);
    }

    .btn-danger {
        background: linear-gradient(90deg, #dc3545, #b02a37);
    }

    .btn-primary:hover, .btn-danger:hover, .btn-success:hover {
        opacity: 0.9;
        transform: scale(1.05);
    }

    /* Modals */
    .modal-header {
        background: linear-gradient(90deg, #007bff, #0056b3);
        color: white;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-footer {
        border-top: none;
    }

    /* Alerts */
    .alert {
        border-radius: 5px;
        font-weight: bold;
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
</style>

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

<div class="container mt-5">
    <h2>Manage Schedule</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add New Schedule Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addScheduleModal">Add New Schedule</button>

    <!-- Schedule Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Check-In Time</th>
                <th>Check-Out Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->schedule_id }}</td>
                    <td>{{ $schedule->description }}</td>
                    <td>{{ $schedule->check_in_time }}</td>
                    <td>{{ $schedule->check_out_time }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="openEditModal({{ json_encode($schedule) }})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $schedule->schedule_id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('schedule.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add Schedule</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <select name="description" id="description" class="form-select" required>
                            <option value="morning">Morning</option>
                            <option value="night">Night</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="check_in_time" class="form-label">Check-In Time</label>
                        <input type="time" name="check_in_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="check_out_time" class="form-label">Check-Out Time</label>
                        <input type="time" name="check_out_time" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editScheduleForm">
            @csrf
            <input type="hidden" id="edit_schedule_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <select id="edit_description" class="form-select" required>
                            <option value="morning">Morning</option>
                            <option value="night">Night</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_check_in_time" class="form-label">Check-In Time</label>
                        <input type="time" id="edit_check_in_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_check_out_time" class="form-label">Check-Out Time</label>
                        <input type="time" id="edit_check_out_time" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Schedule</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to open the Edit Modal and populate data
    function openEditModal(schedule) {
        document.getElementById('edit_schedule_id').value = schedule.schedule_id;
        document.getElementById('edit_description').value = schedule.description;
        document.getElementById('edit_check_in_time').value = schedule.check_in_time;
        document.getElementById('edit_check_out_time').value = schedule.check_out_time;

        const editModal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
        editModal.show();
    }
    document.getElementById('editScheduleForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const id = document.getElementById('edit_schedule_id').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch(`/schedule/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            description: document.getElementById('edit_description').value,
            check_in_time: document.getElementById('edit_check_in_time').value,
            check_out_time: document.getElementById('edit_check_out_time').value
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Log response for debugging
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Update failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred!');
    });
});


    // Handle Delete Confirmation
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this schedule?')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`/schedule/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert(data.message);
                      location.reload();
                  } else {
                      alert('Delete failed: ' + data.message);
                  }
              }).catch(error => console.error('Error:', error));
        }
    }
</script>

</body>
</html>
