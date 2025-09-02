<!DOCTYPE html>
<html>

<head>
    <title>Manage Exam</title>
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
            text-align: center;
            background-color: #ddd;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
        }

        .general .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .general .search-container input[type="text"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 300px;
            transition: border-color 0.3s;
        }

        .general .search-container input[type="text"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .general .butang {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 70px;
            font-size: smaller;
        }

        .general .butang:hover {
            background-color: #45a049;
        }

        .general table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            color: black;
        }

        .general table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .general table th {
            background-color: #555;
            color: white;
        }

        .general table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .general table tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        .general table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .popup5s {
            display: none;
            position: fixed;
            background-color: #f9edbe;
            color: #222;
            padding: 15px;
            border: 1px solid #f0c36d;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            right: 20px;
            top: 20px;
        }

        .timer-bar-container {
            position: relative;
            width: 100%;
            height: 10px;
            background-color: #f0c36d;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        .timer-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #4caf50;
            animation: countdown 5s linear;
        }

        @keyframes countdown {
            from {
                width: 100%;
            }

            to {
                width: 0;
            }
        }
    </style>
</head>

<body>
    <div class="cHome" style="height: auto;">
        <?php
        include "navigation/navbar.php";
        include "navigation/sideAD.php";
        include "conn.php";

        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        ?>

            <div id="popup5s" class="popup5s">
                <div class="popup-content">
                    <?php echo $message; ?>
                    <div class="timer-bar-container">
                        <div class="timer-bar"></div>
                    </div>
                </div>
            </div>

        <?php }

        $search_query = "";
        $sql_where = "";

        if (isset($_GET['search'])) {
            $search_query = mysqli_real_escape_string($conn, $_GET['search']);
            $sql_where = " WHERE examID LIKE '%$search_query%' OR examType LIKE '%$search_query%' OR classid LIKE '%$search_query%'";
        }

        if (isset($_POST['update'])) {
            $examID = mysqli_real_escape_string($conn, $_POST['examID']);
            $examType = mysqli_real_escape_string($conn, $_POST['examType']);
            $classid = mysqli_real_escape_string($conn, $_POST['classid']);
            $examDate = mysqli_real_escape_string($conn, $_POST['examDate']);

            $update_query = "UPDATE exam SET examType='$examType', classid='$classid', examDate='$examDate' WHERE examID='$examID'";
            mysqli_query($conn, $update_query);
            header("Location: manage_exam.php?message=Exam updated successfully.");
        }

        $query = "SELECT * FROM exam" . $sql_where . " ORDER BY classid";
        $result = mysqli_query($conn, $query);
        $i = 1;
        ?>
        <div class="general">
            <h1>Manage Exam</h1>
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Search by Exam ID, Exam Type, Class ID or Exam Date" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="butang">Search</button>
                </form>
            </div>

            <a href="add.php?o=Exam"><button class="butang" style="margin-left:10%; width: 100px; background-color:lightgreen; color:black;">Add</button></a>
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Exam ID</th>
                            <th>Exam Type</th>
                            <th>Class ID</th>
                            <th>Exam Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <form method="POST" action="">
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <input type="hidden" name="examID" value="<?php echo $row['examID']; ?>">
                                        <?php echo $row['examID']; ?>
                                    </td>
                                    <td><input type="text" name="examType" value="<?php echo $row['examType']; ?>"></td>
                                    <td><input type="text" name="classid" value="<?php echo $row['classid']; ?>"></td>
                                    <td><input type="date" name="examDate" value="<?php echo $row['examDate']; ?>"></td>
                                    <td style="text-align: center; display:flex; height: 100%;  padding: 20px;">
                                        <button type="submit" name="update" class="butang" onclick="return confirm('Are you sure you want to Update this Exam?');">Update</button>
                                        <a href="dropStudent.php?id=<?php echo $row['examID']; ?>&r=E" onclick="return confirm('Are you sure you want to drop this Exam?');">
                                            <button type="button" style="background-color: red;" class="butang">Drop</button>
                                        </a>
                                    </td>
                                </form>
                            </tr>
                        <?php
                            $i++;
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="no-results">No exams found.</div>
            <?php } ?>
        </div>
    </div>

    <script>
        function showPopup5s() {
            var popup5s = document.getElementById('popup5s');
            popup5s.style.display = 'block';

            setTimeout(function() {
                popup5s.style.display = 'none';
            }, 5000);
        }

        window.onload = function() {
            if (document.getElementById('popup5s')) {
                showPopup5s();
            }
        };
    </script>
</body>

</html>
