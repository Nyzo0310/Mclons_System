<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        h2 {
            font-family: Georgia, serif;
            font-size: 30px;
            font-weight: 600;
            color: #495057;
            text-align: left;
            margin-bottom: 20px;
        }

        #modalPhoto {
            border: 2px solid #ddd;
            padding: 10px;
            background-color: #f8f9fa;
        }

        .main-content {
            padding: 25px;
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

        #searchInput {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }


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
        }

        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .btn-success {
            background: linear-gradient(90deg, #28a745, #218838);
        }

        .btn-danger {
            background: linear-gradient(90deg, #dc3545, #b02a37);
        }

        .add-employee-button {
            margin-bottom: 20px;
        }
        .table tbody td:nth-child(3),
        .table tbody td:nth-child(4) {
            font-weight: bold;
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
    <div class="main-content">
        <h2>Employee List</h2>
        <div class="add-employee-button">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="fas fa-plus"></i> New
            </button>
            <div class="table-wrapper">
    <!-- Search Bar -->
    <div class="d-flex justify-content-end mb-3">
        <input
            type="text"
            id="searchInput"
            class="form-control"
            placeholder="Search by Name, ID, or Position"
            onkeyup="filterTable()"
            style="width: 300px;"
        />
    </div>
    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Employee ID</th>
                    <th>Photo</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Birthdate</th>
                    <th>Contact No</th>
                    <th>Gender</th>
                    <th>Position</th>
                    <th>Schedule</th>
                    <th>Statutory Benefits</th>
                    <th>Action</th>
                </tr>
                    </thead>
                    <tbody>
                        @if(count($employees) === 0)
                            <tr>
                                <td colspan="11" class="text-center">No data available in the table</td>
                            </tr>
                        @else
                            @foreach($employees as $employee)
                                <tr>
                                    <td>{{ $employee->employee_id }}</td>

                                    <td>
                                        @if($employee->photo)
                                            <img src="{{ asset('storage/' . $employee->photo) }}" 
                                                 alt="Employee Photo"
                                                 class="img-thumbnail" 
                                                 width="50" height="50"
                                                 style="cursor: pointer;"
                                                 onclick="showPhotoModal('{{ asset('storage/' . $employee->photo) }}')">
                                        @else
                                            <span>No Photo</span>
                                        @endif
                                    </td>
                                    

                                    <td>{{ $employee->first_name }}</td>
                                    <td>{{ $employee->last_name }}</td>
                                    <td>{{ $employee->address }}</td>
                                    <td>{{ $employee->birthdate }}</td>
                                    <td>{{ $employee->contact_no }}</td>
                                    <td>{{ $employee->gender }}</td>
                                    <td>{{ $employee->position?->position_name ?? 'N/A' }}</td>
                                    <td>{{ $employee->schedule?->description ?? 'N/A' }}</td>
                                    <td>{{ $employee->deduction ? $employee->deduction->name : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" onclick="editEmployee({{ $employee->employee_id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <button class="btn btn-danger btn-sm" onclick="deleteEmployee({{ $employee->employee_id }})">
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
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Employee Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalPhotoImage" src="" alt="Employee Photo" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>
    

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/add" method="POST" enctype="multipart/form-data" id="addEmployeeForm">
                        @csrf
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_no" class="form-label">Contact No</label>
                            <input type="text" class="form-control" id="contact_no" name="contact_no" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="position_id" class="form-label">Position</label>
                            <select id="position_id" name="position_id" class="form-select">
                                <option value="">No Position</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->position_id }}">{{ $position->position_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="statutory_benefits" class="form-label">Statutory Benefits</label>
                            <select id="statutory_benefits" name="statutory_benefits" class="form-select" required>
                                <option value="">Select Benefits</option>
                                @foreach ($deduction as $statutory)
                                    <option value="{{ $statutory->deduction_id }}">{{ $statutory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label">ID Image</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            title: 'Success',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
    @endif
    ...
</table>
</div>
</div>
</div>
<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Required for the PUT method -->
                    <div class="mb-3">
                        <label for="edit_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_birthdate" class="form-label">Birthdate</label>
                        <input type="date" class="form-control" id="edit_birthdate" name="birthdate" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_contact_no" class="form-label">Contact No</label>
                        <input type="text" class="form-control" id="edit_contact_no" name="contact_no" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gender" class="form-label">Gender</label>
                        <select id="edit_gender" name="gender" class="form-select" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_position_id" class="form-label">Position</label>
                        <select id="edit_position_id" name="position_id" class="form-select">
                            <option value="">No Position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->position_id }}">{{ $position->position_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_schedule" class="form-label">Schedule</label>
                        <select id="edit_schedule" name="schedule_id" class="form-select" required>
                            <option value="">Select Schedule</option>
                            @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->schedule_id }}">
                                {{ $schedule->description }}
                            </option>
                            
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_statutory_benefits" class="form-label">Statutory Benefits</label>
                        <select id="edit_statutory_benefits" name="statutory_benefits" class="form-select" required>
                            <option value="">Select Benefits</option>
                            @foreach ($deduction as $statutory)
                                <option value="{{ $statutory->deduction_id }}">{{ $statutory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Add Scripts at the End -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function filterTable() {
    const input = document.getElementById("searchInput");
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

    
    function showPhotoModal(photoUrl) {
    const modalPhotoImage = document.getElementById('modalPhotoImage');
    modalPhotoImage.src = ''; // Clear any previous image
    modalPhotoImage.src = photoUrl; // Set the new photo URL

    // Show the modal
    const photoModal = new bootstrap.Modal(document.getElementById('photoModal'));
    photoModal.show();
}

function editEmployee(employeeId) {
    fetch(`/employee/${employeeId}`)
        .then(response => response.json())
        .then(data => {
            // Set form values dynamically
            document.getElementById('editEmployeeForm').action = `/employee/${employeeId}`;
            document.getElementById('edit_first_name').value = data.first_name;
            document.getElementById('edit_last_name').value = data.last_name;
            document.getElementById('edit_address').value = data.address;
            document.getElementById('edit_birthdate').value = data.birthdate;
            document.getElementById('edit_contact_no').value = data.contact_no;

            // Set gender default value
            const genderDropdown = document.getElementById('edit_gender');
            [...genderDropdown.options].forEach(option => {
                if (option.value === data.gender) {
                    option.selected = true;
                }
            });

            // Populate other fields
            document.getElementById('edit_position_id').value = data.position_id;
            document.getElementById('edit_schedule').value = data.schedule_id;
            document.getElementById('edit_statutory_benefits').value = data.statutory_benefits;

            // Show the modal
            new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
        })
        .catch(error => console.error('Error:', error));
}

    // Fix modal close issue
    document.getElementById('editEmployeeModal').addEventListener('hidden.bs.modal', function () {
        document.querySelector('.modal-backdrop').remove(); // Remove stuck backdrop
        document.body.classList.remove('modal-open'); // Remove unclickable overlay
    });

    function deleteEmployee(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/employee/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.message || 'Employee deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload the page after successful deletion
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'Failed to delete employee.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }
</script>

</body>
</html>
