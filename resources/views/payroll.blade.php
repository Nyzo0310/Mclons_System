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
            margin: 0; /* Remove extra spacing around the title */
        }

        #searchPayrollInput {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Vertically aligns the title and search input */
        }


        .main-content {
        max-width: 95%;
        margin: 40px auto 20px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow-x: auto; /* Enables horizontal scroll for large tables */
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
    .modal-xl {
        max-width: 90%; /* Set to 90% of the screen width */
    }

    .modal-body {
        padding: 20px;
        overflow-x: hidden; /* Prevent horizontal overflow */
    }

    /* Enhanced Table Styles */
.table-wrapper {
    overflow-x: auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
    padding: 15px;
}

.table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.table thead th {
    background: linear-gradient(90deg, #007bff, #0056b3);
    color: white;
    font-weight: bold;
    text-align: center;
    white-space: nowrap;
    padding: 12px;
}

.table tbody td {
    text-align: center;
    vertical-align: middle;
    font-size: 1rem;
    padding: 12px;
}

.table tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

.table tbody tr:hover {
    background-color: #f1f8ff;
    transition: background-color 0.3s;
}

.table th,
.table td {
    border: 1px solid #dee2e6;
}

.table tbody td:first-child {
    font-weight: bold;
}

.table tbody td:nth-last-child(2) {
    font-weight: bold;
}

/* Button Styling */
.btn {
    border-radius: 8px; /* Rounded corners */
    font-size: 0.85rem; /* Comfortable font size */
    padding: 8px 12px; /* Balanced padding */
    margin: 0 4px; /* Spacing between buttons */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    transition: all 0.3s ease; /* Smooth transition for hover */
}

/* View Payslip Button */
.btn-info {
    background-color: #28a745 !important; /* Green background */
    border-color: #28a745 !important; /* Green border */
    color: white !important; /* White text */
    border-radius: 8px; /* Rounded corners */
    font-size: 0.85rem;
    padding: 8px 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-right: 8px; /* Space between buttons */
    transition: transform 0.2s ease, background-color 0.2s ease;
}

.btn-info:hover {
    background-color: #218838 !important; /* Darker green on hover */
    border-color: #1e7e34 !important;
    transform: scale(1.05); /* Slightly enlarge on hover */
}


/* History Button */
.btn-primary {
    margin-top: 5px;
    background-color: #007bff; /* Blue color */
    border-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
    transform: translateY(-2px); /* Slight lift on hover */
}

/* Uniform Button Size */
.btn-sm {
    min-width: 100px; /* Ensures consistent width */
    text-align: center; /* Centers text */
}



</style>

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
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">Payroll</h2>
        <input
            type="text"
            id="searchPayrollInput"
            class="form-control"
            placeholder="Search Payroll"
            onkeyup="filterPayrollTable()"
            style="width: 300px;"
        />
    </div>
    <div class="controls">
        <form action="{{ route('payroll.saveAll') }}" method="POST" style="text-align: right; margin-bottom: 15px;">
            @csrf
            <button type="submit" class="btn btn-success">Save Payroll</button>
        </form>
    </div>
    @if(session('success'))
    <div id="successAlert" class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="table-wrapper">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Employee ID</th>
                    <th>Position</th>
                    <th>Regular Pay</th>
                    <th>Overtime Pay</th>
                    <th>Holiday Pay</th>
                    <th>Night Additional Pay</th>
                    <th>Gross Salary</th>
                    <th>Cash Advance</th>
                    <th>Deductions</th>
                    <th>Total Deductions</th>
                    <th>Net Pay</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee['name'] ?? 'N/A' }}</td>
                    <td>{{ $employee['employee_id'] ?? 'N/A' }}</td>
                    <td>{{ $employee['position_name'] ?? 'N/A' }}</td>
                    <td>{{ number_format($employee['regular_pay'] ?? 0, 2) }}</td>
                    <td>{{ number_format($employee['overtime_pay'] ?? 0, 2) }}</td>
                    <td>{{ number_format($employee['holiday_pay'] ?? 0, 2) }}</td>
                    <td>{{ number_format($employee['extra_2to4_pay'] ?? 0, 2) }}</td>
                    <td>{{ number_format($employee['gross_salary'] ?? 0, 2) }}</td>
                    <td>{{ number_format($employee['cash_advance'] ?? 0, 2) }}</td>
                    <td>{{ $employee['deduction_name'] ?? 'N/A' }} ({{ number_format($employee['deductions'] ?? 0, 2) }})</td>
                    <td>{{ number_format($employee['total_deductions'] ?? 0, 2) }}</td>
                    <td>{{ number_format($employee['net_salary'] ?? 0, 2) }}</td>
                    
                    <td>
                        <!-- View Payslip Button -->
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#payslipModal" 
                            data-name="{{ $employee['name'] }}" 
                            data-id="{{ $employee['employee_id'] }}"
                            data-position="{{ $employee['position_name'] }}"
                            data-regular="{{ number_format($employee['regular_pay'], 2) }}"
                            data-overtime="{{ number_format($employee['overtime_pay'], 2) }}"
                            data-holiday="{{ number_format($employee['holiday_pay'], 2) }}"
                            data-nightot="{{ number_format($employee['extra_2to4_pay'], 2) }}"
                            data-gross="{{ number_format($employee['gross_salary'], 2) }}"
                            data-cashadvance="{{ number_format($employee['cash_advance'], 2) }}"
                            data-deduction-name="{{ $employee['deduction_name'] }}"
                            data-deductions="{{ number_format($employee['deductions'], 2) }}"
                            data-total-deductions="{{ number_format($employee['total_deductions'], 2) }}"
                            data-netpay="{{ number_format($employee['net_salary'], 2) }}">
                            View Payslip
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="viewHistory({{ $employee['employee_id'] }})">
                            History
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
        </table>
    </div>
</div>
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Changed to modal-xl for extra width -->
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="historyModalLabel">
                    <i class="fas fa-history"></i> Employee Payroll History
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body" id="historyModalBody" style="padding: 20px;">
                <!-- Dynamic content will be injected here -->
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payslip Modal -->
<div class="modal fade" id="payslipModal" tabindex="-1" aria-labelledby="payslipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payslipModalLabel">Employee Payslip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="payslipDetails">
                <h3 class="text-center">Mclons Manpower Services</h3>
                <p class="text-center">Payslip for {{ date('F, Y') }}</p>

                <table class="table table-bordered">
                    <tr><th>Employee Name:</th><td id="payslipName"></td></tr>
                    <tr><th>Employee ID:</th><td id="payslipID"></td></tr>
                    <tr><th>Position:</th><td id="payslipPosition"></td></tr>
                </table>

                <h5 class="text-center">Earnings</h5>
                <table class="table table-bordered">
                    <tr><td>Regular Pay</td><td id="payslipRegular"></td></tr>
                    <tr><td>Overtime Pay</td><td id="payslipOvertime"></td></tr>
                    <tr><td>Holiday Pay</td><td id="payslipHoliday"></td></tr>
                    <tr><td>Night Overtime Pay</td><td id="payslipNightOT"></td></tr>
                    <tr><th>Total Gross Salary</th><td id="payslipGross"></td></tr>
                </table>

                <h5 class="text-center">Deductions</h5>
                <table class="table table-bordered">
                    <tr><td>Cash Advance</td><td id="payslipCashAdvance"></td></tr>
                    <tr><td id="payslipDeductionsName"></td><td id="payslipDeductions"></td></tr>
                    <tr><th>Total Deductions</th><td id="payslipDeductionsTotal"></td></tr>
                </table>

                <h4 class="text-end">Net Pay: <span id="payslipNetPay" style="color: green;"></span></h4>
            </div>
             <!-- Modal Footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-success" id="printPayslip">Print</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function filterPayrollTable() {
    const input = document.getElementById("searchPayrollInput");
    const filter = input.value.toLowerCase();
    const table = document.querySelector(".table tbody");
    const rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        let isVisible = false;
        const cells = rows[i].getElementsByTagName("td");

        for (let j = 0; j < cells.length - 1; j++) { // Exclude the last column (Action buttons)
            if (cells[j] && cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                isVisible = true;
                break;
            }
        }

        rows[i].style.display = isVisible ? "" : "none";
    }
}


   function viewHistory(employeeId) {
    fetch(`/payroll/history/${employeeId}`)
        .then(response => response.json())
        .then(data => {
            // Populate the modal with the employee's history
            const historyModalBody = document.getElementById('historyModalBody');
            historyModalBody.innerHTML = `
                <div class="mb-3">
                    <h5 class="text-primary">Payroll History for <span class="fw-bold">${data.employee.name}</span></h5>
                    <p class="mb-2"><strong>Position:</strong> ${data.employee.position}</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Regular Pay</th>
                                <th>Overtime Pay</th>
                                <th>Holiday Pay</th>
                                <th>Night Pay</th>
                                <th>Gross Salary</th>
                                <th>Cash Advance</th>
                                <th>Deductions</th>
                                <th>Net Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.payrolls.map(payroll => `
                                <tr>
                                    <td>${payroll.start_date}</td>
                                    <td>${payroll.end_date}</td>
                                    <td class="text-end">${parseFloat(payroll.regular_pay).toFixed(2)}</td>
                                    <td class="text-end">${parseFloat(payroll.overtime_pay).toFixed(2)}</td>
                                    <td class="text-end">${parseFloat(payroll.holiday_pay).toFixed(2)}</td>
                                    <td class="text-end">${parseFloat(payroll.extra_2to4_pay).toFixed(2)}</td>
                                    <td class="text-end text-success fw-bold">${parseFloat(payroll.gross_salary).toFixed(2)}</td>
                                    <td class="text-end text-danger">${parseFloat(payroll.cash_advance).toFixed(2)}</td>
                                    <td class="text-end text-warning">${parseFloat(payroll.deductions).toFixed(2)}</td>
                                    <td class="text-end text-primary fw-bold">${parseFloat(payroll.net_salary).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            // Show the modal
            const historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
            historyModal.show();
        })
        .catch(error => console.error('Error fetching payroll history:', error));
}
</script>
<!-- JavaScript for Modal -->
<script>
    document.getElementById('payslipModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        // Set values in modal
        document.getElementById('payslipName').textContent = button.getAttribute('data-name');
        document.getElementById('payslipID').textContent = button.getAttribute('data-id');
        document.getElementById('payslipPosition').textContent = button.getAttribute('data-position');
        document.getElementById('payslipRegular').textContent = button.getAttribute('data-regular');
        document.getElementById('payslipOvertime').textContent = button.getAttribute('data-overtime');
        document.getElementById('payslipHoliday').textContent = button.getAttribute('data-holiday');
        document.getElementById('payslipNightOT').textContent = button.getAttribute('data-nightot');
        document.getElementById('payslipGross').textContent = button.getAttribute('data-gross');
        document.getElementById('payslipCashAdvance').textContent = button.getAttribute('data-cashadvance');
        document.getElementById('payslipDeductionsName').textContent = button.getAttribute('data-deduction-name');
        document.getElementById('payslipDeductions').textContent = button.getAttribute('data-deductions');
        document.getElementById('payslipDeductionsTotal').textContent = button.getAttribute('data-total-deductions');
        document.getElementById('payslipNetPay').textContent = button.getAttribute('data-netpay');
    });
    document.getElementById('printPayslip').addEventListener('click', function () {
    const content = document.getElementById('payslipDetails').outerHTML; // Capture the content
    const printWindow = window.open('', '', 'width=800,height=600');

    printWindow.document.write(`
        <html>
            <head>
                <title>Print Payslip</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        line-height: 1.6; 
                        margin: 20px; 
                        color: #333;
                    }
                    table { 
                        width: 100%; 
                        border-collapse: collapse; 
                        margin: 20px 0; 
                    }
                    th, td { 
                        padding: 8px; 
                        text-align: left; 
                        border: 1px solid #ddd; 
                    }
                    h3, h4 { 
                        margin: 0; 
                        text-align: center; 
                        color: #007bff; 
                    }
                </style>
            </head>
            <body>
                <h3>Mclons Manpower Services</h3>
                <h4>Payslip for ${new Date().toLocaleString('default', { month: 'long', year: 'numeric' })}</h4>
                ${content}
            </body>
        </html>
    `);

    printWindow.document.close();

    // Use a timeout to ensure content is fully loaded before printing
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
});

 // Wait for the page to load completely
 document.addEventListener("DOMContentLoaded", function () {
        const successAlert = document.getElementById("successAlert");
        if (successAlert) {
            // Automatically hide the alert after 5 seconds
            setTimeout(() => {
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = "0"; // Fade out
                // Remove from the DOM after the fade-out
                setTimeout(() => successAlert.remove(), 500); // Delay to match fade-out duration
            }, 3000); // 5000ms = 5 seconds
        }
    });
</script>


</body>
</html>