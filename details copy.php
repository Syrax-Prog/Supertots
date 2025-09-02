<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sidebar.php";
        include "conn.php";

        ?>
        <div class="general" style="margin-left: 250px;">
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333;">View Student's Details</h1>

            <?php
            if (isset($_GET['message'])) {
                $mess = $_GET['message']; ?>
                <div style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <p style="font-size: 16px; font-weight: bold; color: #333;"><?php echo $mess; ?></p>
                </div>
            <?php }
            ?>
            <div class="flex-box">
                <div class="sided2" style="padding-left: 20px;">
                    <div class="sided">

                        <?php
                        if (isset($_GET['id'])) {
                            $child_id = $_GET['id'];
                        }

                        $query = "SELECT * FROM child WHERE childid = '$child_id'";
                        $result = mysqli_query($conn, $query);

                        if ($result->num_rows > 0) {
                            $row = mysqli_fetch_assoc($result);

                            $child_id = $row['childid'];
                            $child_name = $row['name'];
                            $gender = $row['gender'];
                            $class = $row['classid'];
                            $dob = $row['dob'];

                            $dobObj = new DateTime($dob);
                            $now = new DateTime();
                            $age = $now->diff($dobObj);
                            $ageYears = $age->y;

                            $imgSrc = "img/student/" . $row['images'];
                        }

                        $query = "SELECT * FROM class WHERE classid = '$class'";
                        $result = mysqli_query($conn, $query);

                        if ($result->num_rows > 0) {
                            $row = mysqli_fetch_assoc($result);

                            $classname = $row['classname'];
                        }

                        ?>
                        <form action="editStudentProcess.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                            <div class="change-img" style="margin-left: 110px;">
                                <img class="pic-edit-AD" src="<?php echo $imgSrc; ?>" alt="">
                            </div>

                            <div>Student ID: <input type="text" value="<?php echo $child_id; ?>" name="childid" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                            <div>Student Name: <input type="text" value="<?php echo $child_name; ?>" name="name" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                            <div>Student Class: <input type="text" value="<?php echo $class; ?>" name="class" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                            <div>Student Age: <input type="text" value="<?php echo $ageYears; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                            <div>Student Gender: <input type="text" value="<?php echo $gender; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                        </form>
                    </div>
                    <div class="sided" style="padding-left: 20px;">
                        <h2 style="padding-left: 20px; padding-bottom: 50px;">Class Details</h2>
                        <form action="" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                            <div>Class ID: <input type="text" value="<?php echo $class; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                            <div>Class Name: <input type="text" value="<?php echo $classname; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                        </form>
                        <br>
                        <h3>Total Student: </h3><br>

                        <?php
                        $sql = "SELECT COUNT(*) AS total_count FROM child WHERE classid = '$class'";
                        $result = mysqli_query($conn, $sql);

                        if ($result->num_rows > 0) {
                            $row = mysqli_fetch_assoc($result);

                            $total = $row['total_count'];
                        }
                        ?>

                        <h4><?php echo $total; ?></h4>
                    </div>
                </div>

                <div class="sided2" style="padding-left: 20px;">
                    <?php

                    $query = " SELECT p.* FROM parent_child pc
                            JOIN child c ON pc.childid = c.childid
                            JOIN parent p ON pc.parentid = p.parentid
                            WHERE c.childid = '$child_id'";

                    $result = mysqli_query($conn, $query);

                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pid = $row['parentid'];
                        $pName = $row['parent_name'];


                    ?>
                        <div class="sided" style="padding-left: 20px;">
                            <h2 style="padding-left: 20px; padding-bottom: 50px;">Guardian <?php echo $i; ?> Details</h2>

                            <form action="" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                                <div>Parent Name: <input type="text" value="<?php echo $pName; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                                <div>Parent ID: <input type="text" value="<?php echo $pid; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                            </form>
                            <div class="GGG">
                                <a href="editParents.php?id=<?php echo $pid; ?>"><button>View Details</button></a>
                            </div>
                        </div>
                    <?php
                        $i = $i + 1;
                    } ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>