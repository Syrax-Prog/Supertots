<?php
session_start();
$parents = $_SESSION['id'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Homepage Teacher</title>
    <style>
        .chat-table {
            width: 60%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }

        .chat-table th,
        .chat-table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #dee2e6;
        }

        .chat-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .chat-item {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .chat-item a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .chat-item a:hover {
            color: #0056b3;
        }

        .chat-item:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chat-item:active {
            background-color: #e2e6ea;
            transform: scale(0.98);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .chat-list {
            animation: fadeIn 0.5s ease-in-out;
        }

        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-container input[type=text] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-container input[type=text]:focus {
            outline: none;
            border-color: #007bff;
        }

        .general {
            margin-left: 230px;
            margin-top: 0;
            text-align: center;
        }

        h2 {
            margin-top: 20px;
            font-size: 24px;
        }
    </style>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function searchParents() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("parentTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Index 1 is where parent name is located
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</head>

<body>

    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sideParent.php";
        ?>
        <div class="general" style="margin-top: 0;">
            <h2>Select Teacher to Chat</h2>
            <div class="search-container">
                <input type="text" id="searchInput" onkeyup="searchParents()" placeholder="Search for parents...">
            </div>
            <table id="parentTable" class="chat-table">
                <thead>
                    <tr>
                        <th>Teacher ID</th>
                        <th>Teacher Name</th>
                        <th>Students</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "conn.php";

                    $query1 = "SELECT * FROM parent_child WHERE parentid = '$parents'";
                    $result1 = $conn->query($query1);

                    // Array to track processed teachers
                    $processedTeachers = array();

                    if ($result1->num_rows > 0) {
                        while ($row1 = $result1->fetch_assoc()) {
                            $childs = $row1['childid'];

                            // Prepare the SQL query to get class IDs and student names using child IDs
                            $query2 = "SELECT DISTINCT classid, name FROM child WHERE childid = '$childs'";
                            $result2 = $conn->query($query2);

                            if ($result2->num_rows > 0) {
                                while ($row2 = $result2->fetch_assoc()) {
                                    $class = $row2['classid'];
                                    $studentName = $row2['name'];

                                    // Prepare the SQL query to get staff details using class IDs
                                    $query3 = "SELECT * FROM class_teacher WHERE classid = '$class'";
                                    $result3 = $conn->query($query3);

                                    if ($result3->num_rows > 0) {
                                        while ($row3 = $result3->fetch_assoc()) {
                                            $teachers = $row3['staffid'];

                                            // Check if the teacher has already been processed
                                            if (!isset($processedTeachers[$teachers])) {
                                                $processedTeachers[$teachers] = array(
                                                    'name' => '',
                                                    'students' => array()
                                                );
                                            }

                                            // Retrieve teacher's name if not already retrieved
                                            if (empty($processedTeachers[$teachers]['name'])) {
                                                $query4 = "SELECT * FROM staff WHERE staffid = '$teachers'";
                                                $result4 = $conn->query($query4);

                                                if ($result4->num_rows > 0) {
                                                    $row4 = $result4->fetch_assoc();
                                                    $processedTeachers[$teachers]['name'] = $row4['name'];
                                                }
                                            }

                                            // Add the student's name to the teacher's array
                                            $processedTeachers[$teachers]['students'][] = $studentName;
                                        }
                                    }
                                }
                            }
                        }

                        // Display the processed teachers and their students
                        foreach ($processedTeachers as $teacherId => $teacherData) {
                            echo "<tr class='chat-item'>";
                            echo "<td><a href='sendMessageParent.php?teacherid=$teacherId'>$teacherId</a></td>";
                            echo "<td><a href='sendMessageParent.php?teacherid=$teacherId'>" . $teacherData['name'] . "</a></td>";
                            echo "<td>" . implode(", ", $teacherData['students']) . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>