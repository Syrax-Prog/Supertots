<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent View Activities</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .general .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 50%;
            margin: auto;
        }

        .general .form-container label {
            display: block;
            margin-top: 10px;
            font-size: 16px;
        }

        .general .form-container select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .general .chart-container {
            width: 80%;
            margin: auto;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php include "navigation/navbar.php"; ?>
        <?php include "navigation/sidebar.php"; ?>
        <?php include "conn.php"; ?>

        <div class="general" style="margin-left: 250px;">
            <div class="form-container">
                <form id="selectForm">
                    <label for="classSelect">Select Class</label>
                    <select id="classSelect" name="classid" required>
                        <option value="">Select a Class</option>
                        <?php
                        // Fetch classes
                        $staffid = $_SESSION['name'];
                        $query = "SELECT class.classid, class.classname FROM class JOIN class_teacher ON class.classid = class_teacher.classid WHERE class_teacher.staffid = '$staffid'";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['classid'] . "'>" . $row['classname'] . "</option>";
                        }
                        ?>
                    </select>

                    <label for="studentSelect">Select Student</label>
                    <select id="studentSelect" name="childid" required>
                        <option value="">Select a Student</option>
                    </select>
                </form>
            </div>

            <div class="chart-container"  style="scale:0.9;">
                <canvas id="progressChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('classSelect').addEventListener('change', function () {
            var classId = this.value;

            fetch('ambil_student.php?classid=' + classId)
                .then(response => response.json())
                .then(data => {
                    var studentSelect = document.getElementById('studentSelect');
                    studentSelect.innerHTML = '<option value="">Select a Student</option>';
                    data.forEach(student => {
                        var option = document.createElement('option');
                        option.value = student.childid;
                        option.textContent = student.name;
                        studentSelect.appendChild(option);
                    });
                });
        });

        document.getElementById('studentSelect').addEventListener('change', function () {
            var childId = this.value;

            fetch('ambil_progress.php?childid=' + childId)
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById('progressChart').getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.map(item => item.examType),
                            datasets: [{
                                label: 'Exam Marks',
                                data: data.map(item => item.examMarks),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        });
    </script>
</body>

</html>
