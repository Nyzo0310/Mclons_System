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
        /* Modal Enhancements */
.modal-header {
    background: linear-gradient(90deg, #007bff, #0056b3);
    color: white;
}

.modal-body h6 {
    font-weight: bold;
    margin-bottom: 10px;
}

.modal-body hr {
    margin-top: 0;
}

#modal-absent-days {
    max-height: 150px;
    overflow-y: auto;
}

#modal-absent-days .list-group-item {
    background-color: #f8f9fa;
    border: none;
    border-bottom: 1px solid #ddd;
    padding: 5px 10px;
}

.modal-footer {
    justify-content: center;
}
.table tbody td:nth-child(2) {
    font-weight: bold;
}
#searchAttendanceInput {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

<!-- Main Content -->
 <!-- Main Content -->
 <div class="main-content">
        <h2>Attendance Records</h2>
        <div class="add-schedule-button d-flex justify-content-between mb-3">
            <a href="{{ route('admin.attendance') }}" class="btn btn-primary">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> Add New Attendance
            </a>
            <a href="{{ route('admin.holiday') }}" class="btn btn-secondary">
                <i class="fas fa-calendar" style="margin-right: 8px;"></i> Manage Holidays
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-3 d-flex justify-content-end">
            <input
                type="text"
                id="searchAttendanceInput"
                class="form-control"
                placeholder="Search Attendance"
                style="width: 300px;"
                onkeyup="filterAttendanceTable()"
            />
        </div>

        <!-- Attendance Table -->
        <div class="card-wrapper">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Total Hours</th>
                            <th>Total Overtime</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTableBody">
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->employee_id }}</td>
                                <td>{{ optional($attendance->employee)->first_name . ' ' . optional($attendance->employee)->last_name }}</td>
                                <td>{{ $attendance->check_in_time }}</td>
                                <td>{{ $attendance->check_out_time ?? 'N/A' }}</td>
                                <td>
                                    @if($attendance->check_out_time)
                                        {{ gmdate("H:i", strtotime($attendance->check_out_time) - strtotime($attendance->check_in_time)) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ number_format($attendance->overtime_hours, 2) }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm view-report-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reportModal" 
                                        data-employee-id="{{ $attendance->employee_id }}"
                                        data-employee-name="{{ optional($attendance->employee)->first_name . ' ' . optional($attendance->employee)->last_name }}">
                                        <i class="fas fa-print"></i> View Report
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="reportModalLabel">
                    <i class="fas fa-user-circle me-2"></i> Employee Attendance Report
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Employee Information -->
                <div class="mb-3">
                    <h6 class="text-secondary">Employee Information</h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Employee ID:</strong> <span id="modal-employee-id" class="text-primary"></span></p>
                            <p><strong>Employee Name:</strong> <span id="modal-employee-name"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Present:</strong> <span id="modal-total-present" class="text-success"></span></p>
                            <p><strong>Total Absent:</strong> <span id="modal-total-absent" class="text-danger"></span></p>
                            <p><strong>Total Late Check-ins:</strong> <span id="modal-total-late" class="text-warning"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Absent Days -->
                <div>
                    <h6 class="text-secondary">Absent Days</h6>
                    <hr>
                    <div class="p-2 bg-light rounded">
                        <ul id="modal-absent-days" class="list-group list-group-flush"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printModal()">
                        <i class="fas fa-print me-1"></i> Print Report
                    </button>
                </div>
                
                
            </div>
        </div>
    </div>
</div>


<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<!-- JavaScript -->
<script>
function filterAttendanceTable() {
        const input = document.getElementById("searchAttendanceInput").value.toLowerCase();
        const tableBody = document.getElementById("attendanceTableBody");
        const rows = tableBody.getElementsByTagName("tr");

        Array.from(rows).forEach(row => {
            const cells = row.getElementsByTagName("td");
            let matches = false;

            for (let cell of cells) {
                if (cell && cell.textContent.toLowerCase().includes(input)) {
                    matches = true;
                    break;
                }
            }

            row.style.display = matches ? "" : "none";
        });
    }

document.addEventListener("DOMContentLoaded", () => {
    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    const viewButtons = document.querySelectorAll('.view-report-btn');

    viewButtons.forEach(button => {
        button.addEventListener('click', () => {
            const employeeId = button.getAttribute('data-employee-id');
            const employeeName = button.getAttribute('data-employee-name');

            // Reset modal content
            document.getElementById('modal-employee-id').textContent = employeeId;
            document.getElementById('modal-employee-name').textContent = employeeName;
            document.getElementById('modal-total-present').textContent = 'Loading...';
            document.getElementById('modal-total-absent').textContent = 'Loading...';
            document.getElementById('modal-total-late').textContent = 'Loading...';
            document.getElementById('modal-absent-days').innerHTML = '';

            // Fetch the report data via AJAX
            fetch(`/report/${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-total-present').textContent = data.total_present || 0;
                    document.getElementById('modal-total-absent').textContent = data.total_absent || 0;
                    document.getElementById('modal-total-late').textContent = data.total_late || 0;

                    const absentDaysList = document.getElementById('modal-absent-days');
                    absentDaysList.innerHTML = ''; // Clear previous content
                    data.absent_days.forEach(day => {
                        const li = document.createElement('li');
                        li.textContent = day;
                        li.classList.add('list-group-item');
                        absentDaysList.appendChild(li);
                    });

                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching report:', error);
                    alert('Failed to load report. Please try again.');
                });
        });
    });
});
function printModal() {
    const printContents = document.querySelector('.modal-body').innerHTML; // Only modal body content
    const originalTitle = document.title; // Store the current page title

    // Open a new window for printing
    const printWindow = window.open('', '', 'height=600, width=800');
    printWindow.document.write(`
        <html>
            <head>
                <title>${originalTitle}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        color: #333;
                    }
                    h1, h2, h3, h4, h5, h6 {
                        margin-top: 0;
                        text-align: center;
                    }
                    .print-container {
                        width: 100%;
                        margin: 0 auto;
                        border: 1px solid #ccc;
                        padding: 20px;
                        border-radius: 8px;
                    }
                    ul {
                        list-style-type: none;
                        padding: 0;
                    }
                    ul li {
                        padding: 5px;
                        border-bottom: 1px solid #ddd;
                    }
                </style>
            </head>
            <body>
                <div class="print-container">
                    <h2>Employee Attendance Report</h2>
                    ${printContents}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close(); // Close the document to finish rendering
    printWindow.focus(); // Focus on the new window
    printWindow.print(); // Trigger print
    printWindow.close(); // Close the window after printing
}


</script>

</body>

</html>
