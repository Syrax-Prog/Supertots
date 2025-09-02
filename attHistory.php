<!DOCTYPE html>
<html>

<head>
    <title>Attendance Management</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .general {
            margin-left: 225px;
            padding-bottom: 50px;
        }

        .general h1 {
            color: #333;
        }

        .table-container {
            width: 90%;
            overflow-x: auto;
            margin-top: 20px;
            margin-left: 70px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:nth-child(odd) {
            background-color: #fff;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .viewButton {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .viewButton:hover {
            background-color: #388E3C;
        }

        #attendancePopup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 1000;
            border-radius: 10px;
            max-width: 80%;
            overflow-y: auto;
            max-height: 80%;
        }

        #popupContent {
            margin-bottom: 20px;
        }

        .closeButton {
            background-color: #ff7043;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .closeButton:hover {
            background-color: #e64a19;
        }

        .statusDropdown {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sidebar.php";
        include "conn.php";
        ?>

        <div class="general">
            <h1>Attendance History</h1>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Class ID</th>
                            <th>Class Name</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $today = date('Y-m-d');
                        $query = "
                            SELECT class.classid, class.classname, attendance.Adate
                            FROM class
                            JOIN child ON class.classid = child.classid
                            JOIN childattendance ON child.childid = childattendance.childid
                            JOIN attendance ON childattendance.attendanceid = attendance.attendanceid
                            WHERE attendance.Adate < '$today'
                            GROUP BY class.classid, class.classname, attendance.Adate
                            ORDER BY attendance.Adate DESC
                        ";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $classid = $row['classid'];
                                $classname = $row['classname'];
                                $Adate = $row['Adate'];
                        ?>
                                <tr>
                                    <td><?php echo $classid; ?></td>
                                    <td><?php echo $classname; ?></td>
                                    <td><?php echo $Adate; ?></td>
                                    <td>
                                        <button class="viewButton" data-classid="<?php echo $classid; ?>" data-date="<?php echo $Adate; ?>">View</button>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>No attendance history available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Popup for attendance details -->
    <div id="attendancePopup">
        <div id="popupContent"></div>
        <button class="closeButton" onclick="closePopup()">Close</button>
    </div>

    <script>
        document.querySelectorAll('.viewButton').forEach(button => {
            button.addEventListener('click', function() {
                var classId = this.getAttribute('data-classid');
                var date = this.getAttribute('data-date');

                // Fetch attendance details via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_attendance_details.php?classid=' + classId + '&date=' + date, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var popupContent = document.getElementById('popupContent');
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                popupContent.innerHTML = response.error;
                            }
                        } catch (e) {
                            popupContent.innerHTML = xhr.responseText;
                        }

                        var popup = document.getElementById('attendancePopup');
                        popup.style.display = 'block'; // Display the popup

                        // Add event listeners for dropdowns
                        document.querySelectorAll('.statusDropdown').forEach(dropdown => {
                            dropdown.addEventListener('change', function() {
                                var childAttendanceId = this.getAttribute('data-childattendanceid');
                                var newStatus = this.value;

                                // Update attendance status via AJAX
                                var xhrUpdate = new XMLHttpRequest();
                                xhrUpdate.open('POST', 'update_attendance_status.php', true);
                                xhrUpdate.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                xhrUpdate.onload = function() {
                                    if (xhrUpdate.status === 200) {
                                        alert('Attendance status updated successfully.');
                                    } else {
                                        alert('Failed to update attendance status.');
                                    }
                                };
                                xhrUpdate.send('childattendanceid=' + childAttendanceId + '&status=' + newStatus);
                            });
                        });
                    }
                };
                xhr.send();
            });
        });

        function closePopup() {
            var popup = document.getElementById('attendancePopup');
            popup.style.display = 'none'; // Hide the popup
        }
    </script>
</body>

</html>
