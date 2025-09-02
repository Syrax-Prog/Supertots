<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage admin</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-container {
            width: 700px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-left: 510px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container input[type="date"],
        .form-container input[type="file"] {
            width: 97%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container input[type="submit"]:hover {
            background-color: #4cae4c;
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
    <script>
        function calculateAge(dob) {
            const birthYear = new Date(dob).getFullYear();
            const currentYear = new Date().getFullYear();
            return currentYear - birthYear;
        }

        function setClassId() {
            const dob = document.getElementById('dob').value;
            if (dob) {
                const age = calculateAge(dob);
                let classId;
                if (age === 4) {
                    classId = 'M';
                } else if (age === 5) {
                    classId = 'N';
                } else if (age === 6) {
                    classId = 'Z';
                } else {
                    classId = '';
                    alert('Age must be between 4 and 6 years old.');
                }
                document.getElementById('classid').value = classId;
            }
        }
    </script>
</head>

<body>
    <div class="cHome">
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

        <?php } ?>

        <div class="general">
            <?php
            $role = $_GET['o'];
            if ($role == "Student") {
            ?>
                <div class="form-container">
                    <h2>Add Student</h2>
                    <form action="add_student.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>

                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>

                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" required onchange="setClassId()">

                        <label for="classid">Class ID</label>
                        <input type="text" id="classid" name="classid" readonly required placeholder="Will Be Determined By Age">

                        <label for="images">Images</label>
                        <input type="file" id="images" name="images" accept="image/*" required>

                        <input type="submit" value="Add Student">
                    </form>
                </div>
            <?php
            }
            ?>

            <?php
            if ($role == "Parent") {
            ?>
                <div class="form-container">
                    <h2>Add Parent</h2>
                    <form action="add_parent.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>

                        <label for="contact">Contact</label>
                        <input type="text" id="contact" name="contact" required>

                        <label for="workphone">Work Phone</label>
                        <input type="text" id="work" name="workphone" required>

                        <label for="images">Images</label>
                        <input type="file" id="images" name="images" accept="image/*" required>

                        <input type="submit" value="Add Student">
                    </form>
                </div>
            <?php
            }
            ?>

            <?php
            if ($role == "Teacher") {
            ?>
                <div class="form-container">
                    <h2>Add Teacher</h2>
                    <form action="add_teacher.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>

                        <label for="contact">qualification</label>
                        <input type="text" id="contact" name="qualification" required>

                        <label for="images">Images</label>
                        <input type="file" id="images" name="images" accept="image/*" required>

                        <input type="submit" value="Add Teacher">
                    </form>
                </div>
            <?php
            }
            ?>

            <?php
            if ($role == "Class") {
            ?>
                <div class="form-container">
                    <h2>Add Class</h2>
                    <form action="add_class.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <label for="name">Class ID</label>
                        <input type="text" id="name" name="id" required>

                        <label for="contact">Class Name</label>
                        <input type="text" id="contact" name="name" required>

                        <input type="submit" value="Add Class">
                    </form>
                </div>
            <?php
            }
            ?>

            <?php
            if ($role == "Exam") {
                // Fetch class names from the database
                $sql_classes = "SELECT * FROM class";
                $result_classes = mysqli_query($conn, $sql_classes);

                $classes = [];
                while ($row = mysqli_fetch_assoc($result_classes)) {
                    $classes[] = [
                        'classid' => $row['classid'],
                        'classname' => $row['classname']
                    ];
                }
            ?>
                <div class="form-container">
                    <h2>Add Exam</h2>
                    <form action="add_exam.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <label for="examType">Exam Type</label>
                        <input type="text" id="examType" name="examType" required>

                        <label for="className">Class Name</label>
                        <select id="className" name="classid" required>
                            <option value="">Select a Class</option>
                            <?php foreach ($classes as $class) : ?>
                                <option value="<?php echo htmlspecialchars($class['classid']); ?>"><?php echo htmlspecialchars($class['classname']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="examDate">Exam Date</label>
                        <input type="date" id="examDate" name="examDate" required>

                        <input type="submit" value="Add Exam">
                    </form>
                </div>
            <?php
            }
            ?>


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