<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
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
         .navbar .btn i.fas.fa-bars {
                color: white; /* Ensures the icon color is white */
                font-size: 1.5rem; /* Adjust the size if necessary */
        }
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
            width: 100%;
            margin: 0;
            box-sizing: border-box;
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
            font-family: 'Georgia', serif;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        /* Card Design Styles */
        .kpi-cards .card {
            display: flex;
            align-items: center;
            padding: 20px;
            border-radius: 10px;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            height: 150px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Icon on the Right */
        .kpi-cards .card .card-icon {
            font-size: 4rem;
            opacity: 0.3;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Hover Effects for Cards */
        .kpi-cards .card:hover {
            transform: scale(1.03);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Card Background Colors */
        .card.bg-blue {
            background-color: #007bff;
        }

        .card.bg-green {
            background-color: #28a745;
        }

        .card.bg-yellow {
            background-color: #ffc107;
            color: #333;
        }

        .card.bg-red {
            background-color: #dc3545;
        }

        /* "More Info" Link */
        .kpi-cards .card a {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
            display: block;
        }

        .kpi-cards .card a:hover {
            text-decoration: underline;
        }

        /* Text Adjustment */
        .card-content {
            margin-right: 250px;
        }

        /* Chevron Rotation */
        .rotate {
            transform: rotate(90deg);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body>

@if(session('login_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Login Successful',
            text: "{{ session('login_success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif


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
            <span>{{ Auth::user()->username }}</span>  <!-- Display username -->
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm ms-3">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
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
        <h5>{{ Auth::user()->username }}</h5>  <!-- Display username here -->
        <span><i class="fas fa-circle text-success"></i> Online</span>
    @else
        <img src="{{ asset('path_to_guest_icon.png') }}" alt="Guest Icon" class="rounded-circle" width="70">
        <h5>Guest</h5>
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
    <h2>Dashboard</h2>
    <!-- KPI Cards -->
    <div class="row g-3 kpi-cards">
        <div class="col-md-3">
            <div class="card bg-blue">
                <div class="card-content">
                    <h3 class="card-title">{{ $totalEmployees }}</h3>
                    <p class="card-text">Total Employees</p>
                </div>
                <i class="fas fa-users card-icon"></i>
                <a href="{{ route('admin.addEmployeeList') }}">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-green">
                <div class="card-content">
                    <h3 class="card-title">{{ number_format($onTimePercentage, 2) }}%</h3>
                    <p class="card-text">On Time Percentage</p>
                </div>
                <i class="fas fa-chart-pie card-icon"></i>
                <a href="{{ route('admin.attendance') }}">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-yellow">
                <div class="card-content">
                    <h3 class="card-title">{{ $onTimeToday }}</h3>
                    <p class="card-text">On Time Today</p>
                </div>
                <i class="fas fa-clock card-icon"></i>
                <a href="{{ route('admin.attendanceDash') }}">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-red">
                <div class="card-content">
                    <h3 class="card-title">{{ $lateToday }}</h3>
                    <p class="card-text">Late Today</p>
                </div>
                <i class="fas fa-exclamation-triangle card-icon"></i>
                <a href="{{ route('admin.attendanceDash') }}">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Monthly Attendance Report -->
    <div class="mt-5" style="height: calc(100vh - 200px);">
        <h4>Monthly Attendance Report</h4>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <label for="viewSelector" class="form-label">View:</label>
                <select id="viewSelector" class="form-select" style="width: 200px;">
                    <option value="current">Current Month</option>
                    <option value="year">Whole Year</option>
                </select>
            </div>
        </div>
        <!-- Responsive Full-Width Graph -->
        <div style="width: 100%; height: 100%;">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const attendanceDataCurrentMonth = @json($attendanceCountsCurrentMonth);

    // Prepare labels and data for the chart
    const currentDate = new Date();
    const totalDays = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
    const labelsCurrentMonth = Array.from({ length: totalDays }, (_, i) => i + 1);
    const ontimeDataMonth = new Array(totalDays).fill(0);
    const lateDataMonth = new Array(totalDays).fill(0);

    attendanceDataCurrentMonth.forEach(data => {
        const dayIndex = parseInt(data.day) - 1; // Adjust for 0-based index
        if (dayIndex >= 0 && dayIndex < totalDays) {
            ontimeDataMonth[dayIndex] = data.ontime || 0;
            lateDataMonth[dayIndex] = data.late || 0;
        }
    });

    const ctx = document.getElementById('attendanceChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labelsCurrentMonth,
        datasets: [
            {
                label: 'On Time',
                data: ontimeDataMonth,
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
            },
            {
                label: 'Late',
                data: lateDataMonth,
                backgroundColor: 'rgba(255, 193, 7, 0.8)',
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 10, // Set the maximum value of the Y-axis
                ticks: {
                    stepSize: 10, // Set the step size to 10
                    callback: function(value) {
                        return value; // Optionally format the Y-axis ticks (e.g., adding '%' symbol)
                    }
                },
            },
            x: {
                ticks: {
                    autoSkip: false, // Ensures all days are displayed on the X-axis
                },
            },
        },
    },
});

</script>

</body>
</html>