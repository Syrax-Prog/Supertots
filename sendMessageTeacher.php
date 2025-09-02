<!DOCTYPE html>
<html>

<head>
    <title>Chat with Parent</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .custom-chat-container {
            width: 80%;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .custom-chat-header {
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            font-size: 24px;
        }

        .custom-chat-messages {
            padding: 20px;
            height: 300px;
            overflow-y: auto;
            border-bottom: 1px solid #dddddd;
        }

        .custom-chat-input {
            display: flex;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .custom-chat-input input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #dddddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .custom-chat-input button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .custom-chat-input button:hover {
            background-color: #45a049;
        }

        .custom-message {
            margin-bottom: 10px;
        }

        .custom-message.teacher {
            text-align: right;
        }

        .custom-message p {
            display: inline-block;
            padding: 10px;
            border-radius: 4px;
            max-width: 60%;
        }

        .custom-message.teacher p {
            background-color: #e1f5e0;
            border: 1px solid #4CAF50;
        }

        .custom-message.parent p {
            background-color: #f1f1f1;
            border: 1px solid #dddddd;
        }
    </style>
</head>

<body>
    <div class="cHome">

        <?php
        include "navigation/navbar.php";
        include "navigation/sidebar.php";

        session_start();
        include 'conn.php';
        $staffid = $_SESSION['name'];
        $parentid = $_GET['parentid'];

        $sqlT = "SELECT parent_name FROM parent WHERE parentid = '$parentid'";
        $resultT = $conn->query($sqlT);
        if ($resultT->num_rows > 0) {
            $rowT = $resultT->fetch_assoc();
            $teacherName = $rowT['parent_name'];
        }

        if (isset($_POST['message']) && !empty($_POST['message'])) {
            $Sms = $conn->real_escape_string($_POST['message']);
            $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$staffid', '$parentid', '$Sms')";
            $conn->query($sql);
        }
        ?>

        <div class="general" style="margin-left: 250px; margin-top: 0;">
            <div class="custom-chat-container">
                <div class="custom-chat-header">
                    <?php echo $teacherName; ?>
                </div>
                <div class="custom-chat-messages" id="chat-messages">
                    <?php
                    $sql = "SELECT * FROM messages WHERE (sender_id = '$staffid' AND receiver_id = '$parentid') OR (sender_id = '$parentid' AND receiver_id = '$staffid') ORDER BY timestamp ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if ($row['sender_id'] == $staffid) {
                                echo '<div class="custom-message teacher"><p>' . htmlspecialchars($row['message']) . '</p></div>';
                            } else {
                                echo '<div class="custom-message parent"><p>' . htmlspecialchars($row['message']) . '</p></div>';
                            }
                        }
                    } else {
                        echo '<p>No messages found.</p>';
                    }
                    ?>
                </div>
                <div class="custom-chat-input">
                    <form action="sendMessageTeacher.php?parentid=<?php echo $parentid; ?>" method="POST">
                        <input type="text" name="message" placeholder="Type your message..." style="width: 900px;">
                        <button type="submit">Send</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function scrollToBottom() {
            var chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function fetchMessages() {
            $.ajax({
                url: 'updateMessages.php?parentid=<?php echo $parentid; ?>',
                method: 'GET',
                success: function(data) {
                    var messages = JSON.parse(data);
                    var chatMessages = $('#chat-messages');
                    chatMessages.html(''); // Clear the chat messages
                    messages.forEach(function(message) {
                        var messageClass = message.sender_id == '<?php echo $staffid; ?>' ? 'teacher' : 'parent';
                        var messageHtml = '<div class="custom-message ' + messageClass + '"><p>' + message.message + '</p></div>';
                        chatMessages.append(messageHtml);
                    });
                    scrollToBottom();
                }
            });
        }

        $(document).ready(function() {
            fetchMessages();
            setInterval(fetchMessages, 5000); // Fetch new messages every 5 seconds
            scrollToBottom(); // Scroll to bottom on page load
        });
    </script>

</body>

</html>