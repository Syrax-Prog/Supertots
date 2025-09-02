<!DOCTYPE html>
<html>

<head>
    <title>Homepage Teacher</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* General styling */
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
            margin-left: 1260px;
            /* Adjust as per your layout */
            margin-top: 130px;
            /* Adjust as per your layout */
        }

        /* Style for the timer bar container */
        .timer-bar-container {
            position: relative;
            width: 100%;
            height: 10px;
            background-color: #f0c36d;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        /* Style for the timer bar */
        .timer-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #4caf50;
            animation: countdown 5s linear;
        }

        /* Keyframes for the timer bar animation */
        @keyframes countdown {
            from {
                width: 100%;
            }

            to {
                width: 0;
            }
        }

        /* Adjusted table styles */
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            margin-left: 250px;
            color: black;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #555;
            color: white;
        }

        table tbody tr:nth-child(even) {
            background-color: white;
        }

        table tbody tr:nth-child(odd) {
            background-color: grey;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .butang {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s;
            scale: 0.9;
        }

        .butang:hover {
            background-color: #45a049;
        }

        input[type="text"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .search-container,
        .filter-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container {
            margin-left: 170px;
        }

        .filter-container {
            margin-left: 170px;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sidebar.php";
        include "conn.php"; ?>

        <?php
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

        <?php } ?>

        <div class="general" style="margin-left: 100px;">
            <h1 style="text-align: center; background-color: #ddd; padding: 20px 20px; border-radius: 5px; margin-left: 150px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);">List Student</h1>

            <div class="filter-container">
                <a href="listStudentDetail.php?class=all&message=Showing Student In All Class"><button class="butang">All</button></a>
                <?php
                $query1 = "SELECT * FROM class";
                $result1 = mysqli_query($conn, $query1);

                while ($row1 = mysqli_fetch_assoc($result1)) {
                    $classid = $row1['classid'];
                    $classname = $row1['classname']; ?>

                    <a href="listStudentDetail.php?class=<?php echo $classid; ?>&message=Showing Student In Class <?php echo $classname; ?>"><button class="butang"><?php echo $classname; ?></button></a>
                <?php } ?>
            </div>

            <div class="search-container">
                <form method="GET" action="" style="display: inline-block;">
                    <input type="text" name="search" placeholder="Search by Name" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit" class="butang">Search</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 100px;">No.</th>
                        <th>Name</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;

                    if (isset($_GET['class']) || isset($_GET['search'])) {
                        $sort = isset($_GET['class']) ? $_GET['class'] : 'all';
                        $search_query = isset($_GET['search']) ? $_GET['search'] : '';

                        if ($sort != "all") {
                            $query = "SELECT * FROM child WHERE classid = '$sort'";
                            if ($search_query) {
                                $query .= " AND name LIKE '%$search_query%'";
                            }
                        } else {
                            $query = "SELECT * FROM child";
                            if ($search_query) {
                                $query .= " WHERE name LIKE '%$search_query%'";
                            }
                        }
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $child_id = $row['childid'];
                                $child_name = $row['name'];
                    ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $child_name; ?></td>
                                    <td style="text-align: center;"><a href="details.php?id=<?php echo $child_id; ?>"><button class="butang">View</button></a></td>
                                </tr>
                    <?php
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='3' style='text-align: center; color: #999; font-size: 18px;'>No students found.</td></tr>";
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showPopup5s() {
            var popup5s = document.getElementById('popup5s');
            popup5s.style.display = 'block'; // Show the popup

            // Automatically hide the popup after 5 seconds
            setTimeout(function() {
                popup5s.style.display = 'none';
            }, 5000); // 5000 milliseconds = 5 seconds
        }

        // Show the popup when the page loads
        window.onload = showPopup5s;
    </script>

</body>

</html>
