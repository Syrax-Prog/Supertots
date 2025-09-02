<?php
include "conn.php";

if (isset($_GET['classid']) && isset($_GET['date'])) {
    $classid = $_GET['classid'];
    $date = $_GET['date'];

    // Fetch attendance details
    $query = "
        SELECT childattendance.childattendanceid, child.name, child.gender, attendance.Astatus 
        FROM child
        JOIN childattendance ON child.childid = childattendance.childid
        JOIN attendance ON childattendance.attendanceid = attendance.attendanceid
        WHERE child.classid = '$classid' AND attendance.Adate = '$date'
    ";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<h3>Attendance details for $date</h3>";
        echo "<table>";
        echo "<tr><th>Name</th><th>Gender</th><th>Status</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            $childattendanceid = $row['childattendanceid'];
            $name = $row['name'];
            $gender = $row['gender'];
            $status = $row['Astatus'];
            echo "<tr>";
            echo "<td>" . $name . "</td>";
            echo "<td>" . $gender . "</td>";
            echo "<td>
                    <select class='statusDropdown' data-childattendanceid='$childattendanceid'>
                        <option value='Present'" . ($status == 'Present' ? ' selected' : '') . ">Present</option>
                        <option value='Absent'" . ($status == 'Absent' ? ' selected' : '') . ">Absent</option>
                    </select>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No attendance records found for this date.</p>";
    }
} else {
    echo json_encode(["error" => "Invalid parameters."]);
}
?>
