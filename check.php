<?php 
    include 'conn.php';


    //----------------------------------------------------------Get Login Value---------------------------------------------------------------
    if (isset($_POST['submit'])){
        $id = $_POST['id'];
        $pass = $_POST['password'];


        //------------------------------------------------------Verify Login Credentials (Parents)---------------------------------------------------
        $stmt = $conn->prepare("SELECT * FROM parent WHERE parentid = ? AND password = ?");
        $stmt->bind_param("ss", $id, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['parent_name'];
            $role = "Parents";
            $images = $row['images'];
            $pppid = $row['parentid'];

            session_start();
            $_SESSION['role'] = $role;
            $_SESSION['name'] = $name;
            $_SESSION['images'] = $images;
            $_SESSION['id'] = $pppid;
            header("Location: home_parent.php");

        
        //-------------------------------------------------------Verify Login Credentials (Teacher/Admin) If Parents Empty-----------------------------
        } else {
            $stmt = $conn->prepare("SELECT * FROM staff WHERE staffid = ? AND password = ?");
            $stmt->bind_param("ss", $id, $pass);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $row['staffid'];
                $role = $row['role'];
                $sName = $row['Name'];
                $images = $row['images'];

                if($role == "Admin"){
                    session_start();
                    $_SESSION['role'] = $role;
                    $_SESSION['name'] = $name;
                    $_SESSION['images'] = $images;
                    header("Location: home_admin.php");  
                }else{
                    session_start();
                    $_SESSION['role'] = $role;
                    $_SESSION['name'] = $name;
                    $_SESSION['images'] = $images;
                    header("Location: home_teacher.php"); 
                }
                


            //-----------------------------------------------------Handle Errors-------------------------------------------------------------------------------
            } else {
                $message = "Login Failed, Check You ID or Password";
                header("Location: login.php?message=$message");
            }
        }
    }
?>