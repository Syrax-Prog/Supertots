<html>

<head>
    <title>Attendance Management</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Table styling */
        .general table {
            width: 1000px;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
        }

        .general table th,
        .general table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .general table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        .general table td {
            background-color: #fff;
            color: #666;
        }

        .general table td a {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .general table td a:hover {
            background-color: #0056b3;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .cHome {
                flex-direction: column;
            }

            .general {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sidebar.php";
        include "conn.php"; ?>

        <div class="general" style="margin-left: 225px;">
            <h1>Class List</h1>

            <?php
            if (isset($_GET['name'])) {
                $staffid = $_GET['name'];

                // Use prepared statements to prevent SQL injection
                $query = $conn->prepare("
                    SELECT c.classid, c.classname
                    FROM class_teacher ct
                    JOIN class c ON ct.classid = c.classid
                    WHERE ct.staffid = ?
                ");
                $query->bind_param("s", $staffid);
                $query->execute();
                $result = $query->get_result();

                if ($result->num_rows > 0) {
                    echo '<table border="1" cellspacing="0" cellpadding="10">';
                    echo '<tr><th>Class ID</th><th>Class Name</th><th>Action</th></tr>';
                    while ($row = $result->fetch_assoc()) {
                        $classid = $row['classid'];
                        $classname = $row['classname'];
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($classid) . '</td>';
                        echo '<td>' . htmlspecialchars($classname) . '</td>';
                        echo '<td><a href="ListStudentAttendance.php?id=' . htmlspecialchars($classid) . '">View Attendance</a></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>No classes found.</p>';
                }
                $query->close();
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>

</html>