    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <title>Login Page</title>
        <style>
            body {
                background-image: url('https://1.bp.blogspot.com/-fd1WXKk-TAc/XyqfngP4PiI/AAAAAAAAVMw/umQz3tkxtg43uPIy8W5og6gAkpCfjaTvACLcBGAsYHQ/w1563-h1563/bg-snell.png');
                background-size: cover;
                background-position: center;
                margin: 0;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: 'Arial', sans-serif;
            }

            .container {
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                padding: 40px;
                text-align: center;
                width: 100%;
                max-width: 400px;
            }

            .container img {
                max-width: 100px;
                margin-bottom: 20px;
            }

            .textTitle h1 {
                color: #333;
                font-size: 24px;
                margin-bottom: 20px;
            }

            .input-group {
                margin-bottom: 20px;
                text-align: left;
            }

            .input-group label {
                display: block;
                margin-bottom: 5px;
                color: white;
            }

            .input-group input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-sizing: border-box;
            }

            .btnSubmit input {
                background-color: #4caf50;
                border: none;
                border-radius: 5px;
                color: white;
                cursor: pointer;
                font-size: 16px;
                padding: 10px;
                width: 100%;
            }

            .btnSubmit input:hover {
                background-color: #45a049;
            }

            .popup5s {
                display: none;
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #f9edbe;
                color: #222;
                padding: 15px;
                border: 1px solid #f0c36d;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }

            .timer-bar-container {
                position: relative;
                width: 100%;
                height: 10px;
                background-color: #f0c36d;
                border-radius: 5px;
                overflow: hidden;
                margin-top: 10px;
            }

            .timer-bar {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #4caf50;
                animation: countdown 5s linear;
            }

            @keyframes countdown {
                from {
                    width: 100%;
                }
                to {
                    width: 0;
                }
            }
        </style>
    </head>

    <body>
        <?php
        include 'conn.php';
        
        $sql = "SELECT * FROM attendance WHERE Adate < CURDATE()";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dID = $row['attendanceid'];
                $updateSql = "UPDATE attendance SET Astatus = 'Absent' WHERE attendanceid = $dID";
                $conn->query($updateSql);
            }
        }

        $sql = "SELECT acID FROM activity_child 
                INNER JOIN activity ON activity_child.activityid = activity.activityID 
                WHERE activity.payment = 0";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $acID = $row['acID'];
                $updateSql = "UPDATE activity_child SET paymentStatus = 'Free' WHERE acID = $acID";
                $conn->query($updateSql);
            }
        }
        ?>

        <?php if (isset($_GET['message'])) { $message = $_GET['message']; ?>
        <div id="popup5s" class="popup5s">
            <div class="popup-content">
                <?php echo $message; ?>
                <div class="timer-bar-container">
                    <div class="timer-bar"></div>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="container">
            <img src="img/logoSupertots.png" alt="Supertots Logo">
            <div class="textTitle">
                <h1>Welcome to Supertots</h1>
            </div>
            <form action="check.php" method="POST">
                <div class="input-group">
                    <label for="id">User ID</label>
                    <input type="text" id="id" name="id" placeholder="Enter Your User ID" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter Your Password" required>
                </div>
                <div class="btnSubmit">
                    <input type="submit" name="submit" value="Log In">
                </div>
            </form>
        </div>

        <script>
            function showPopup5s() {
                var popup5s = document.getElementById('popup5s');
                popup5s.style.display = 'block';
                setTimeout(function() {
                    popup5s.style.display = 'none';
                }, 5000);
            }

            window.onload = showPopup5s;
        </script>
    </body>

    </html>
