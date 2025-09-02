<!DOCTYPE html>
<html>

<head>
    <title>Homepage Admin</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Adjusted table styles */
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
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
            transform: scale(0.9);
        }

        .butang:hover {
            background-color: #45a049;
        }

        .general {
            margin-left: 100px;
        }

        .general h1 {
            text-align: center;
            background-color: #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-left: 150px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
            margin-left: 250px;
        }

        .search-container input[type="text"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }

        .search-container input[type="text"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .no-results {
            text-align: center;
            color: #999;
            font-size: 18px;
            margin: 20px 0;
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

        // Initialize variables for search
        $search_query = '';
        if (isset($_GET['search'])) {
            $search_query = mysqli_real_escape_string($conn, $_GET['search']);
        }

        // Construct the base SQL query
        $query = "SELECT * FROM staff WHERE role = 'Teacher'";

        // Add search conditions if $search_query is not empty
        if (!empty($search_query)) {
            // Separate the conditions properly using parentheses to ensure correct logical order
            $query .= " AND (staffid LIKE '%$search_query%' OR name LIKE '%$search_query%')";
        }

        // Execute the query
        $result = mysqli_query($conn, $query);
        $i = 1;
        ?>

        <div class="general">
            <h1>Manage Teachers</h1>
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Search by Staff ID or Name" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="butang">Search</button>
                </form>
            </div>
            <a href="add.php?o=Teacher"><button class="butang" style="margin-left:170px; width: 100px; background-color:lightgreen; color:black;">Add</button></a>
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <table style="margin-left: 250px;">
                    <thead>
                        <tr>
                            <th style="width: 100px;">No.</th>
                            <th style="width: 100px;">Staff ID</th>
                            <th>Name</th>
                            <th style="width: 200px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['staffid'];
                            $name = $row['name'];
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $id; ?></td>
                                <td><?php echo $name; ?></td>
                                <td style="text-align: center;"><a href="editTeacher.php?id=<?php echo $id; ?>"><button class="butang">Edit</button></a>
                                    <a href="dropStudent.php?id=<?php echo $id; ?>&r=T" onclick="return confirm('Are you sure you want to drop this Teacher?');">
                                        <button style="background-color: red;" class="butang">Drop</button>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $i++;
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="no-results">No teachers found.</div>
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