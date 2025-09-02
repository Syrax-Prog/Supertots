<?php  

include 'conn.php';
        if (isset($_POST['present'])){
                $id = $_POST['idChild'];

                echo $id;
                $today = date("Y-m-d"); // Retrieves today's date in the format "YYYY-MM-DD"
                echo "Today's date is: " . $today;

                // Get today's date
                $today = date("Y-m-d");

                // Prepare SQL query
                $sql = "SELECT * FROM attendance WHERE Adate = '$today'";

                // Execute the query
                $result = $conn->query($sql);

                // Check if there are any results
                if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "ID: " . $row["attendanceid"]. " - Attendance Date: " . $row["Adate"]. "<br>";
                }
                } else {
                echo "0 results";
                }

        }
        
?>