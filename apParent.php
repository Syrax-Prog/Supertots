<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent View Activities</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .general {
            margin-left: 250px;
            margin-top: 150px;
            font-family: Arial, sans-serif;
        }

        .general h2 {
            font-size: 28px;
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .general table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            margin-left: 40px;
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

        .general .action-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .general .action-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php include "navigation/navbar.php"; ?>
        <?php include "navigation/sideParent.php"; ?>
        <?php include "conn.php"; ?>

        <div class="general">
            <h2>Children Under This Parent</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Class Name</th>
                    <th>Action</th>
                </tr>
                <?php
                if (isset($_SESSION['id'])) {
                    $parentid = $_SESSION['id'];

                    // Query to fetch children associated with the parent
                    $sql_children = "SELECT c.childid, c.name, cl.classname FROM child c
                                     INNER JOIN parent_child pc ON c.childid = pc.childid
                                     INNER JOIN class cl ON c.classid = cl.classid
                                     WHERE pc.parentid = '$parentid'";
                    $result_children = mysqli_query($conn, $sql_children);

                    if ($result_children) {
                        while ($row = mysqli_fetch_assoc($result_children)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['classname']) . "</td>";
                            echo "<td><a href='viewProgressP.php?childid=" . $row['childid'] . "' class='action-button'>View Progress</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No children found under this parent.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No parent session found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>

</html>
