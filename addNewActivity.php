<!DOCTYPE html>
<html>

<head>
    <title>Homepage Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .activity-form-table {
            width: 1000px;
            border-collapse: collapse;
            margin-left: 60px;
        }
        
        .activity-form-table th,
        .activity-form-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .activity-form-table th {
            background-color: #f2f2f2;
        }

        .activity-form-container {
            background-color: none;
        }

        .activity-form-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }

        .activity-form-submit:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="cHome" style="height: auto;">
        <?php
        include "navigation/navbar.php";
        include "navigation/sideAD.php";
        include "conn.php";
        ?>

        <div class="general" style="margin-left: 240px;">
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333;">Add New Activity</h1>

            <div class="activity-form-container" style="margin-right: 220px;">
                <form class="activity-form" action="AddActivityProcess.php" method="POST">
                    <table class="activity-form-table">
                        <tr>
                            <th>Field</th>
                            <th>Input</th>
                        </tr>
                        <tr>
                            <td>Activity Name:</td>
                            <td><input class="activity-form-input" type="text" name="actName" required></td>
                        </tr>
                        <tr>
                            <td>Activity Venue:</td>
                            <td><input class="activity-form-input" type="text" name="actVenue" required></td>
                        </tr>
                        <tr>
                            <td>Activity Description:</td>
                            <td><textarea class="activity-form-input" name="actDesc" id="actDesc" rows="4" required></textarea></td>
                        </tr>
                        <tr>
                            <td>Start:</td>
                            <td><input class="activity-form-input" type="date" id="actStart" name="actStart" required></td>
                        </tr>
                        <tr>
                            <td>End:</td>
                            <td><input class="activity-form-input" type="date" id="actEnd" name="actEnd" required></td>
                        </tr>
                        <tr>
                            <td>Registration Date Open:</td>
                            <td><input class="activity-form-input" type="date" id="actRegOp" name="actRegOp" required></td>
                        </tr>
                        <tr>
                            <td>Registration Date Close:</td>
                            <td><input class="activity-form-input" type="date" id="actRegCl" name="actRegCl" required></td>
                        </tr>
                        <tr>
                            <td>Entry Fee:</td>
                            <td><input class="activity-form-input" type="number" name="actFee" step="any" required></td>
                        </tr>
                        <tr>
                            <td>Maximum Participant:</td>
                            <td><input class="activity-form-input" type="number" name="actPart" required></td>
                        </tr>
                    </table>
                    <div style="text-align: center;">
                        <input class="activity-form-submit" id="submitBtn" type="submit" name="submit" value="Submit" disabled>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        //ACTIVITY START/END
        document.addEventListener('DOMContentLoaded', function() {
            const actStartInput = document.getElementById('actStart');
            const actEndInput = document.getElementById('actEnd');
            const actRegOpInput = document.getElementById('actRegOp');
            const actRegClInput = document.getElementById('actRegCl');
            const submitBtn = document.getElementById('submitBtn');

            // Function to validate dates
            function validateDates() {
                const startDate = actStartInput.value;
                const endDate = actEndInput.value;
                const regOpenDate = actRegOpInput.value;
                const regCloseDate = actRegClInput.value;
                let isValid = true;

                if (startDate && endDate && endDate < startDate) {
                    alert('Activity Cannot End Before Its Start... Please change end date');
                    actEndInput.value = ''; // Clear the end date input
                    isValid = false;
                }

                if (regOpenDate && regCloseDate && regCloseDate < regOpenDate) {
                    alert('Registration Cannot End Before Its Start... Please change close date');
                    actRegClInput.value = ''; // Clear the close date input
                    isValid = false;
                }

                if (regOpenDate && startDate && regOpenDate > startDate) {
                    alert('Registration Open Date Cannot Be After Activity Start Date... Please change registration open date');
                    actRegOpInput.value = ''; // Clear the open date input
                    isValid = false;
                }

                submitBtn.disabled = !isValid;
            }

            // Event listeners for when the date changes
            actStartInput.addEventListener('change', function() {
                const startDate = actStartInput.value;
                actEndInput.min = startDate; // Set the min attribute of actEnd to the selected start date
                validateDates(); // Validate dates to ensure the current end date is not before the new start date
            });

            actEndInput.addEventListener('change', validateDates);

            actRegOpInput.addEventListener('change', function() {
                const regOpenDate = actRegOpInput.value;
                actRegClInput.min = regOpenDate; // Set the min attribute of actRegCl to the selected open date
                validateDates(); // Validate dates to ensure the current close date is not before the new open date
            });

            actRegClInput.addEventListener('change', validateDates);
        });
    </script>
</body>

</html>
