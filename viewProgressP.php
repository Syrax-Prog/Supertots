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
            display: none; /* Hide the form container */
        }

        .general .chart-container, .general .table-container {
            width: 80%;
            margin: auto;
            margin-top: 20px;
        }

        .general .title-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .general .title-container h2 {
            font-size: 24px;
            color: #333;
        }

        .toggle-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .toggle-buttons button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s;
        }

        .toggle-buttons button:hover {
            background-color: #45a049;
        }

        .general table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        .general th, .general td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        .general th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        .general tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .general tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php include "navigation/navbar.php"; ?>
        <?php include "navigation/sideParent.php"; ?>
        <?php include "conn.php"; ?>

        <div class="general" style="margin-left: 250px;">
            <div class="title-container">
                <h2 id="title"></h2>
            </div>
            <div class="toggle-buttons">
                <button id="toggleGraphical">Graphical</button>
                <button id="toggleTable">Table</button>
            </div>
            <div class="chart-container" id="chartContainer" style="scale:0.9;">
                <canvas id="progressChart"></canvas>
            </div>
            <div class="table-container" id="tableContainer" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Exam Type</th>
                            <th>Exam Marks</th>
                        </tr>
                    </thead>
                    <tbody id="examTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggleGraphical').addEventListener('click', function() {
            document.getElementById('chartContainer').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
        });

        document.getElementById('toggleTable').addEventListener('click', function() {
            document.getElementById('chartContainer').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
        });

        // Automatically fetch students and trigger events based on URL parameters
        window.onload = function() {
            var urlParams = new URLSearchParams(window.location.search);
            var classId = urlParams.get('classid');
            var childId = urlParams.get('childid');

            if (childId) {
                fetch(`ambil_childname.php?childid=${childId}`)
                    .then(response => response.json())
                    .then(childData => {
                        var childName = childData.name;

                        fetch('ambil_progress.php?childid=' + childId)
                            .then(response => response.json())
                            .then(data => {
                                var currentYear = new Date().getFullYear();
                                var title = `Showing Academic Progress for ${childName} [ ${currentYear} ]`;
                                document.getElementById('title').textContent = title;

                                // Populate Chart
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

                                // Populate Table
                                var tableBody = document.getElementById('examTableBody');
                                tableBody.innerHTML = ''; // Clear previous content
                                data.forEach(item => {
                                    var row = document.createElement('tr');
                                    var examTypeCell = document.createElement('td');
                                    var examMarksCell = document.createElement('td');
                                    examTypeCell.textContent = item.examType;
                                    examMarksCell.textContent = item.examMarks + '%';
                                    row.appendChild(examTypeCell);
                                    row.appendChild(examMarksCell);
                                    tableBody.appendChild(row);
                                });
                            });
                    });
            }
        };
    </script>
</body>

</html>
