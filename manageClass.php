<!DOCTYPE html>
<html>

<head>
    <title>Homepage admin</title>
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
    </style>
</head>

<body>
    <div class="cHome" style="height: auto;">
        <?php
        include "navigation/navbar.php";
        include "navigation/sideAD.php";
        include "conn.php";

        // Initialize variables for search
        $search_query = "";
        $sql_where = "";

        // Check if search query is submitted
        if (isset($_GET['search'])) {
            $search_query = mysqli_real_escape_string($conn, $_GET['search']);
            $sql_where = " WHERE classid LIKE '%$search_query%' OR classname LIKE '%$search_query%'";
        }

        // Query to fetch classes with optional search
        $query = "SELECT * FROM class" . $sql_where;
        $result = mysqli_query($conn, $query);
        $i = 1;
        ?>
        <div class="general">
            <h1>Manage Class</h1>
            <div class="search-container">
                <!-- Search Form -->
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Search by Class ID or Class Name" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="butang">Search</button>
                </form>
            </div>

            <a href="add.php?o=Class"><button class="butang" style="margin-left:170px; width: 100px; background-color:lightgreen; color:black;">Add</button></a>
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <table style="margin-left: 250px;">
                    <thead>
                        <tr>
                            <th style="width: 100px;">No.</th>
                            <th>Class ID</th>
                            <th>Class Name</th>
                            <th style="width: 200px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['classid']; ?></td>
                                <td><?php echo $row['classname']; ?></td>
                                <td style="text-align: center;" style="display:flex; flex-direction:column;">
                                    <a href="editClass.php?id=<?php echo $row['classid']; ?>">
                                        <button class="butang">Edit</button>
                                    </a>
                                    <a href="dropClass.php?id=<?php echo $row['classid']; ?>">
                                        <button class="butang" style="background-color: red;">Drop</button>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $i++;
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="no-results">No classes found.</div>
            <?php } ?>
        </div>
    </div>
</body>

</html>