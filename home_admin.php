<html>

<head>
    <title>Homepage admin</title>
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

        table img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }

        table tr td {
            border: none;
            border-bottom: 1px solid #ddd;
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
    </style>
</head>

<body>
    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sideAD.php";
        include "conn.php"; ?>

        <div class="grid-container">
            <div class="grid-item accent">
                <h3>Total Students</h3>
                <h3><?php
                    if (isset($_SESSION['name'])) {
                        $query = "SELECT COUNT(*) AS total FROM child";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $total = $row['total'];
                            echo "$total";
                        }
                    }
                    ?></h3>
            </div>
            <div class="grid-item highlight">
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
            <div class="grid-item accent">
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
            <div class="grid-item highlight">
                <h3>Total Parent</h3>
                <h3><?php
                    $query = "SELECT COUNT(*) AS total FROM parent";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $total = $row['total'];
                        echo "$total";
                    }
                    ?></h3>
            </div>
        </div>

        <div style="margin-top:100px; margin-left:420px;">
            <h3 style="margin-left:450px;">All Teachers</h3>
            <table>
                <tr>
                    <th>Staff ID</th>
                    <th>Qualification</th>
                    <th>Name</th>
                    <th>Images</th>
                </tr>
                <?php
                $query = "SELECT staffid, qualification, password, role, name, images FROM staff WHERE role = 'Teacher'";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['staffid'] . "</td>";
                        echo "<td>" . $row['qualification'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td><img src='img/staff/" . $row['images'] . "' alt='" . $row['name'] . "'></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>

</body>

</html>
