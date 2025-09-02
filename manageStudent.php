<!DOCTYPE html>
<html>

<head>
    <title>Homepage Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            margin-left: 250px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
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
        // Fetch unique class IDs
        $class_query = "SELECT DISTINCT classid FROM child ORDER BY classid ASC";
        $class_result = mysqli_query($conn, $class_query);

        $classid_filter = isset($_GET['id']) ? $_GET['id'] : '';
        $search_query = isset($_GET['search']) ? $_GET['search'] : '';

        $query = "SELECT * FROM child";
        if ($classid_filter) {
            $query .= " WHERE classid = '$classid_filter'";
        }
        if ($search_query) {
            $query .= ($classid_filter ? " AND" : " WHERE") . " name LIKE '%$search_query%'";
        }
        $query .= " ORDER BY childid ASC";
        $result = mysqli_query($conn, $query);
        ?>

        <div class="general" style="margin-left: 100px;">
            <h1 style="text-align: center; background-color: #ddd; padding: 20px 20px; border-radius: 5px; margin-left: 150px;">
                Manage Students<?php echo $classid_filter ? " ($classid_filter)" : ""; ?>
            </h1>

            <div class="search-container">
                <form method="GET" action="" style="display: inline-block;">
                    <input type="text" name="search" placeholder="Search by Name" value="<?php echo $search_query; ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <a href="add.php?o=Student"><button class="butang" style="margin-left:170px; width: 100px; background-color: lightgreen; scale:0.9;">Add</button></a>

            <div class="filter-container">
                <a href="?"><button>All</button></a>
                <?php while ($class_row = mysqli_fetch_assoc($class_result)) {
                    $class_id = $class_row['classid'];
                ?>
                    <a href="?id=<?php echo $class_id; ?>"><button><?php echo $class_id; ?></button></a>
                <?php } ?>
            </div>

            <?php if (mysqli_num_rows($result) > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 100px;">No.</th>
                            <th style="width: 100px;">Student ID</th>
                            <th>Name</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $child_id = $row['childid'];
                            $child_name = $row['name'];
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $child_id; ?></td>
                                <td><?php echo $child_name; ?></td>
                                <td style="text-align: center;">
                                    <a href="editStudent.php?id=<?php echo $child_id; ?>"><button>Edit</button></a>
                                    <a href="dropStudent.php?id=<?php echo $child_id; ?>" onclick="return confirm('Are you sure you want to drop this student?');">
                                        <button style="background-color: red;">Drop</button>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $i++;
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p style="text-align: center; color: #999; font-size: 18px;">No students found.</p>
            <?php } ?>
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