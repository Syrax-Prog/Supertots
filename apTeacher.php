<!DOCTYPE html>
<html>

<head>
    <title>Academic Performance</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .general {
            margin-left: 225px;
            padding: 20px;
        }

        .general h1 {
            color: #333;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-container select,
        .form-container input[type="submit"] {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #388E3C;
        }

        .table-container {
            width: 90%;
            overflow-x: auto;
            margin-top: 20px;
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

        .updateButton,
        .viewButton {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }

        .updateButton:hover,
        .viewButton:hover {
            background-color: #388E3C;
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
            <h1>Academic Performance</h1>
            <a href="viewSP.php"><button class="viewButton" style="width: 100px;">View Progress</button></a>
            <div class="form-container">
                <form method="POST">
                    <label for="class">Select Class:</label>
                    <select id="class" name="classid">
                        <option value="">Select Class</option>
                        <?php
                        // Fetch classes assigned to the teacher
                        $staffid = $_SESSION['name'];
                        $query = "
                            SELECT class.classid, class.classname 
                            FROM class 
                            JOIN class_teacher ON class.classid = class_teacher.classid 
                            WHERE class_teacher.staffid = '$staffid'
                        ";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['classid'] . "'>" . $row['classname'] . "</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" name="selectClass" value="Select Class">
                </form>
            </div>

            <?php
            if (isset($_POST['selectClass']) && !empty($_POST['classid'])) {
                $classid = $_POST['classid'];
            ?>

                <div class="form-container">
                    <form method="POST">
                        <input type="hidden" name="classid" value="<?php echo $classid; ?>">
                        <label for="exam">Select Exam:</label>
                        <select id="exam" name="examid">
                            <option value="">Select Exam</option>
                            <?php
                            // Fetch exams for the selected class
                            $query = "SELECT examID, examType, examDate FROM exam WHERE classid = '$classid'";
                            $result = mysqli_query($conn, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['examID'] . "'>" . $row['examType'] . " (" . $row['examDate'] . ")</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" name="selectExam" value="Select Exam">
                    </form>
                </div>

            <?php
            }

            if (isset($_POST['selectExam']) && !empty($_POST['examid'])) {
                $classid = $_POST['classid'];
                $examid = $_POST['examid'];
            ?>

                <div class="table-container">
                    <form method="POST" action="update_exam_results.php">
                        <input type="hidden" name="examid" value="<?php echo $examid; ?>">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch students and their marks for the selected exam
                                $query = "
                                SELECT child.childid, child.name, progress.examMarks 
                                FROM child 
                                LEFT JOIN progress ON child.childid = progress.childid AND progress.examID = '$examid' 
                                WHERE child.classid = '$classid'
                            ";
                                $result = mysqli_query($conn, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['childid'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td><input type='number' name='marks[" . $row['childid'] . "]' value='" . $row['examMarks'] . "'></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <input type="submit" class="updateButton" value="Update Results">
                    </form>
                </div>

            <?php
            }
            ?>

        </div>
    </div>
</body>

</html>