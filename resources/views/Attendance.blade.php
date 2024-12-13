<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #edf2f7;
            margin: 0;
            padding: 0;
        }

        /* Fade-in Animation */
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Attendance Card */
        .attendance-card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: left;
            margin: 80px auto;
        }

        #clock {
            font-family: 'Orbitron', sans-serif;
            font-size: 60px;
            font-weight: 700;
            color: #2d3748;
            text-align: center;
            margin-bottom: 20px;
            animation: pulse 2s infinite; /* Pulsing animation */
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .form-label {
            font-family: 'Georgia', serif;
            font-size: 18px;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 10px;
            display: block;
        }

        input[type="text"], select {
            width: 100%;
            padding: 12px 16px;
            font-size: 16px;
            color: #4a5568;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background-color: #f7fafc;
            margin-bottom: 25px;
            transition: box-shadow 0.2s ease-in-out, border-color 0.2s ease-in-out;
        }

        input[type="text"]:focus, select:focus {
            border-color: #3182ce;
            box-shadow: 0 0 8px rgba(49, 130, 206, 0.5);
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: 500;
            color: #ffffff;
            background-color: #3182ce;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2b6cb0;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .back-button {
            margin: 20px;
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 500;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .back-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .attendance-card {
                padding: 30px;
            }

            #clock {
                font-size: 48px;
            }

            input[type="text"], select {
                padding: 12px 15px;
            }

            input[type="submit"] {
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="{{ route('admin.attendanceDash') }}" class="back-button fade-in">
        <i class="fas fa-arrow-left"></i> Back to Attendance Dashboard
    </a>

    <!-- Attendance Form -->
    <div class="attendance-card fade-in">
        <div id="clock"></div>
        <form action="{{ route('admin.submit') }}" method="post">
            @csrf
            <div>
                <label for="employee_id" class="form-label">Employee ID</label>
                <input type="text" id="employee_id" name="employee_id" placeholder="Enter Employee ID" required>
            </div>
            <div>
                <label for="attendancestatus" class="form-label">Attendance</label>
                <select id="attendancestatus" name="attendancestatus" required>
                    <option value="">--Select--</option>
                    <option value="timein">Time In</option>
                    <option value="timeout">Time Out</option>
                </select>
            </div>
            <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="check_in_time" value="{{ now()->toTimeString() }}">
            <input type="submit" value="Submit">
        </form>
    </div>

    <script>
        // Function to update clock in real time
        function updateClock() {
            const now = new Date();
            const currentTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('clock').textContent = currentTime;
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Display SweetAlert2 on success
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
        });
    @endif

    // Display SweetAlert2 on error
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: "{{ session('error') }}",
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
        });
    @endif
</script>

</body>
</html>
