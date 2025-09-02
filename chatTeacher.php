<?php
session_start();
include "conn.php"; // Database connection file
// Fetch all parents from the database
$sql = "SELECT * FROM parent";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Homepage Teacher</title>
    <style>
        .chat-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .chat-table th,
        .chat-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .chat-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .chat-item {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 5px solid transparent;
        }

        .chat-item a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            flex: 1;
            transition: color 0.3s ease;
        }

        .chat-item a:hover {
            color: #0056b3;
        }

        .chat-item:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #007bff;
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
    </style>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function searchParents() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("apaTable");
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
        include "navigation/sidebar.php";
        ?>
        <div class="general" style="margin-left: 320px; margin-top: 0;">
            <h2>Select Parent to Chat</h2>

            <!-- Search bar -->
            <div class="search-container">
                <input type="text" id="searchInput" onkeyup="searchParents()" placeholder="Search by Teacher Name">
            </div>

            <div class="chat-list">
                <table id="apaTable" class="chat-table">
                    <thead>
                        <tr>
                            <th>Parent ID</th>
                            <th style="width: 400px;">Parent Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['parentid'] . '</td>';
                                echo '<td><div class="chat-item"><a href="sendMessageTeacher.php?parentid=' . $row['parentid'] . '">' . $row['parent_name'] . '</a></div></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="2"><div class="chat-item">No Parent found.</div></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>