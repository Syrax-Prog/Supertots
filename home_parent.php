<html>

<head>
    <title>Homepage Parent</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .cHome {
            height: auto;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .grid-item {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .grid-item h3 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .grid-item.total-students {
            background-color: #FFEB3B; /* highlight */
        }

        .grid-item.total-teachers {
            background-color: #FF7043; /* accent */
        }

        .grid-item.total-admins {
            background-color: #FFEB3B; /* highlight */
        }

        .grid-item.clock {
            background-color: #FF7043; /* accent */
        }

        table {
            width: 1000px;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
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

        table th,
        table td {
            text-align: center;
        }

        .highlight {
            background-color: #FFEB3B;
        }

        .accent {
            background-color: #FF7043;
            color: white;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .view-activity-btn {
            background-color: #4CAF50;
            color: white;
        }

        .view-progress-btn {
            background-color: #2196F3;
            color: white;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sideParent.php";
        include "conn.php";

        if (isset($_SESSION['id'])) {
            $parentid = $_SESSION['id'];

            // Query to fetch children associated with the parent
            $sql_children = "SELECT c.childid, c.name, cl.classname FROM child c
                             INNER JOIN parent_child pc ON c.childid = pc.childid
                             INNER JOIN class cl ON c.classid = cl.classid
                             WHERE pc.parentid = '$parentid'";
            $result_children = mysqli_query($conn, $sql_children);
        }
        ?>

        <div class="grid-container">
            <div class="grid-item total-students">
                <h3>Total Students</h3>
                <h3><?php
                    $query = "SELECT COUNT(*) AS total FROM child";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $total = $row['total'];
                        echo "$total";
                    }
                    ?></h3>
            </div>
            <div class="grid-item total-teachers">
                <h3>Total Teacher</h3>
                <h3><?php
                    $query = "SELECT COUNT(*) AS total FROM staff WHERE role = 'Teacher'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $total = $row['total'];
                        echo "$total";
                    }
                    ?></h3>
            </div>
            <div class="grid-item total-admins">
                <h3>Total Admin</h3>
                <h3><?php
                    $query = "SELECT COUNT(*) AS total FROM staff WHERE role = 'Admin'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $total = $row['total'];
                        echo "$total";
                    }
                    ?></h3>
            </div>
            <div class="grid-item clock">
                <h3>
                    <div id="clock"></div>
                </h3>
                <script>
                    function updateClock() {
                        var now = new Date();
                        var hours = now.getHours();
                        var minutes = now.getMinutes();
                        var seconds = now.getSeconds();
                        var day = now.getDate();
                        var month = now.getMonth() + 1; // Months are zero-indexed
                        var year = now.getFullYear();
                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12;
                        minutes = minutes < 10 ? '0' + minutes : minutes;
                        seconds = seconds < 10 ? '0' + seconds : seconds;
                        var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                        var dateString = day + '/' + month + '/' + year;
                        document.getElementById('clock').innerHTML = timeString + '<br>' + dateString;
                    }

                    setInterval(updateClock, 1000); // Update the clock every second
                    updateClock(); // Run the function immediately to prevent delay
                </script>
            </div>
        </div>

        <div style="margin-top:50px; margin-left:400px;">
            <br><br><h3 style="margin-left: 400px;">Children under this Parent</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Class Name</th>
                    <th>Action</th>
                </tr>
                <?php
                if ($result_children) {
                    while ($row = mysqli_fetch_assoc($result_children)) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['classname'] . "</td>";
                        echo "<td>
                            <button class='action-btn view-activity-btn' onclick=\"window.location.href='viewActivity.php?childid=" . $row['childid'] . "'\">View Activity</button>
                            <button class='action-btn view-progress-btn' onclick=\"window.location.href='viewProgressP.php?childid=" . $row['childid'] . "'\">View Progress</button>
                          </td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>

</html>
