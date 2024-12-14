<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payroll</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .page-title {
            font-family: Georgia, serif;
            font-size: 30px;
            font-weight: 600;
            color: #495057;
            text-align: left; /* Aligns the title to the left */
            margin-bottom: 20px;
        }

        .main-content {
            max-width: 1200px;
            margin: 60px auto 20px; /* Adds spacing at the top and bottom */
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .controls .btn {
            margin-left: 10px;
        }

        .controls .date-picker {
            display: flex;
            align-items: center;
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

        .status {
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.active {
            color: #28a745;
        }

        .status.inactive {
            color: #dc3545;
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
    <div class="page-title">Payroll</div>
    <div class="controls">
        <div class="date-picker">
            <input type="text" class="form-control" placeholder="Select date range">
            <button class="btn btn-secondary"><i class="fas fa-calendar-alt"></i></button>
        </div>
        <div>
            <button class="btn btn-success"><i class="fas fa-file-alt"></i> Payroll</button>
            <!-- Payslip Button Triggering Modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payslipModal"><i class="fas fa-file-invoice"></i> Payslip</button>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Employee ID</th>
                    <th>Gross Salary</th>
                    <th>Deductions</th>
                    <th>Cash Advance</th>
                    <th>Netpay</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td>{{ $employee->employee_id }}</td>
                        <td>{{ number_format($employee->payroll->gross_salary, 2) }}</td>
                        <td>
                            @if($employee->deduction_name)
                                {{ $employee->deduction_name }} ({{ number_format($employee->total_deductions, 2) }})
                            @else
                                No deductions
                            @endif
                        </td>
                        <td>{{ number_format($employee->approved_cash_advance ?? 0, 2) }}</td>
                        <td>{{ number_format($employee->payroll->net_salary, 2) }}</td>
                        <td class="status active">Active</td>
                        <!-- Add Button to Trigger Modal with Selected Payslip Data -->
                        <td><button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#payslipModal" 
                            data-name="{{ $employee->first_name }} {{ $employee->last_name }}" 
                            data-id="{{ $employee->employee_id }}" 
                            data-gross="{{ number_format($employee->payroll->gross_salary, 2) }}" 
                            data-deductions="{{ $employee->deduction_name ?? 'No deductions' }}" 
                            data-advance="{{ number_format($employee->approved_cash_advance ?? 0, 2) }}" 
                            data-netpay="{{ number_format($employee->payroll->net_salary, 2) }}">
                            View Payslip
                        </button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

 <!-- Payslip Modal -->
 <div class="modal fade" id="payslipModal" tabindex="-1" aria-labelledby="payslipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payslipModalLabel">Payslip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="payslipDetails">
                    <!-- Payslip Content Will Go Here -->
                    <p><strong>Employee Name:</strong> <span id="payslipName"></span></p>
                    <p><strong>Employee ID:</strong> <span id="payslipID"></span></p>
                    <p><strong>Gross Salary:</strong> <span id="payslipGross"></span></p>
                    <p><strong>Deductions:</strong> <span id="payslipDeductions"></span></p>
                    <p><strong>Cash Advance:</strong> <span id="payslipAdvance"></span></p>
                    <p><strong>Net Pay:</strong> <span id="payslipNetPay"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="downloadPayslip">Download as Image</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="printPayslip">Print</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle modal data population when View Payslip button is clicked
    const payslipModal = document.getElementById('payslipModal');
    payslipModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const name = button.getAttribute('data-name');
        const id = button.getAttribute('data-id');
        const gross = button.getAttribute('data-gross');
        const deductions = button.getAttribute('data-deductions');
        const advance = button.getAttribute('data-advance');
        const netpay = button.getAttribute('data-netpay');

        // Set the modal content
        document.getElementById('payslipName').textContent = name;
        document.getElementById('payslipID').textContent = id;
        document.getElementById('payslipGross').textContent = gross;
        document.getElementById('payslipDeductions').textContent = deductions;
        document.getElementById('payslipAdvance').textContent = advance;
        document.getElementById('payslipNetPay').textContent = netpay;
    });

    // Function to handle printing the payslip
    document.getElementById('printPayslip').addEventListener('click', function() {
        const printContent = document.getElementById('payslipDetails').innerHTML;
        const newWindow = window.open('', '', 'width=800, height=600');
        newWindow.document.write('<html><head><title>Print Payslip</title></head><body>' + printContent + '</body></html>');
        newWindow.document.close();
        newWindow.print();
    });

    // Function to handle downloading the payslip as an image
    document.getElementById('downloadPayslip').addEventListener('click', function() {
        const payslipDetails = document.getElementById('payslipDetails');
        
        // Use html2canvas to capture the payslip content
        html2canvas(payslipDetails).then(function(canvas) {
            // Create an image URL from the canvas
            const imgURL = canvas.toDataURL('image/png');

            // Create a link to download the image
            const link = document.createElement('a');
            link.href = imgURL;
            link.download = 'payslip.png'; // Set the filename
            link.click();
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

</body>
</html>