<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
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

        /* Main Content Styles */
        .main-content {
            padding: 20px;
            min-height: 100vh;
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

        /* Card Table Wrapper */
        .card-wrapper {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Table Styles */
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .add-schedule-button {
            margin-bottom: 20px;
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
    <div class="main-content">
        <h2>Attendance Records</h2>
        <!-- Add New Schedule Button -->
        <div class="add-schedule-button d-flex justify-content-between">
        <a href="{{ route('admin.attendance') }}" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 8px;"></i> Add New Attendance
        </a>
        <!-- Button to Go to Holiday Page -->
        <a href="{{ route('admin.holiday') }}" class="btn btn-secondary">
            <i class="fas fa-calendar" style="margin-right: 8px;"></i> Manage Holidays
        </a>
    </div>
        <div class="card-wrapper">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Date</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date }}</td>
                                <td>{{ $attendance->employee_id }}</td>
                                <td>{{ optional($attendance->employee)->first_name . ' ' . optional($attendance->employee)->last_name }}</td>
                                <td>{{ $attendance->check_in_time }}</td>
                                <td>{{ $attendance->check_out_time ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
