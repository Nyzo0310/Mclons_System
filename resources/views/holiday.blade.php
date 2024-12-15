<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Holiday Calendar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Georgia, serif;
            background-color: #f4f6f9;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .back-button a {
            color: white;
            background-color: #007bff;
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 8px 16px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .back-button a:hover {
            background-color: white;
            color: #007bff;
            border-color: #007bff;
        }
        .header {
            text-align: center;
            margin-top: 60px;
        }
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #007bff;
            font-weight: bold;
        }
        .add-holiday-button {
            text-align: center;
            margin-bottom: 30px;
        }
        .add-holiday-button button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .add-holiday-button button:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        #calendar {
            max-width: 2000px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        .container {
            max-width: 1500px;
        }
        .fc-daygrid-day {
            cursor: pointer; /* Hand cursor for calendar boxes */
            transition: background-color 0.3s ease;
        }
        .fc-daygrid-day:hover {
            background-color: #f0f8ff; /* Light blue background on hover */
        }
        .fc-event {
            cursor: pointer; /* Hand cursor for events */
            background: linear-gradient(45deg, #007bff, #6a11cb);
            color: white;
            border: none;
            font-weight: bold;
            font-size: 0.9rem;
            text-align: center;
            padding: 5px;
            position: absolute;
            top: 80px;
            left: 0;
            right: 0;
            margin: auto;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Back to Dashboard Button -->
    <div class="back-button">
        <a href="{{ route('admin.attendanceDash') }}">← Back to Dashboard</a>
    </div>

    <div class="container mt-5">
        <!-- Page Header -->
        <div class="header">
            <h1>Holiday Calendar</h1>
        </div>

        <!-- Add Holiday Button -->
        <div class="add-holiday-button">
            <button data-bs-toggle="modal" data-bs-target="#addHolidayModal">Add Holiday</button>
        </div>

        <!-- Calendar -->
        <div id="calendar"></div>
    </div>

    <!-- Add Holiday Modal -->
    <div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHolidayModalLabel">Add Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="addHolidayForm">
                        @csrf
                        <div class="mb-3">
                            <label for="holiday_name" class="form-label">Holiday Name</label>
                            <input type="text" class="form-control" id="holiday_name" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="holiday_date" class="form-label">Holiday Date</label>
                            <input type="date" class="form-control" id="holiday_date" name="holiday_date" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="submitHoliday" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.js"></script>
    <script>
    
    document.getElementById('submitHoliday').addEventListener('click', function () {
    const formData = new FormData(document.getElementById('addHolidayForm'));

    fetch('{{ route('admin.addHoliday') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', 'Holiday added successfully!', 'success');
            location.reload(); // Reload to update the calendar
        } else {
            Swal.fire('Error', 'Failed to add holiday.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Failed to add holiday.', 'error');
    });
});



    document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    console.log("Holidays data:", @json($holidays ?? []));

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: @json($holidays ?? []),

        eventClick: function (info) {
            console.log("Event clicked:", info.event);
            if (!info.event.id) {
                Swal.fire('Error', 'Event data is incomplete.', 'error');
                return;
            }

            Swal.fire({
                title: 'Event Action',
                text: `You clicked on "${info.event.title}".`,
                icon: 'info',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Edit',
                denyButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Edit Holiday',
                        input: 'text',
                        inputLabel: 'Enter a new title:',
                        inputValue: info.event.title,
                        showCancelButton: true,
                        confirmButtonText: 'Save',
                    }).then((editResult) => {
                        if (editResult.isConfirmed) {
                            fetch('{{ route('holiday.update') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    id: info.event.id,
                                    title: editResult.value
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    info.event.setProp('title', editResult.value);
                                    Swal.fire('Updated!', `Holiday updated to "${editResult.value}".`, 'success');
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Failed to update holiday.', 'error');
                            });
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire({
                        title: 'Confirm Deletion',
                        text: `Are you sure you want to delete "${info.event.title}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it',
                    }).then((deleteResult) => {
                        if (deleteResult.isConfirmed) {
                            fetch(`/holiday/${info.event.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    info.event.remove();
                                    Swal.fire('Deleted!', data.message, 'success');
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Failed to delete holiday.', 'error');
                            });
                        }
                    });
                }
            });
        },
    });

    calendar.render();
});


    </script>
</body>
</html>
