<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointmen-Finder</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../frontend/css/stylesheet.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<!-- As a link -->
<nav class="navbar bg-#">
    <div class="d-flex align-items-center me-auto">
        <img id="logo" src="img/logo.png" alt="" width="30" height="auto" class="me-auto">
        <p id="username" class="me-auto my-auto"></p>
    </div>
    <div class="d-flex align-items-center ms-auto">
        <button class="btn btn-outline-success me-2 ms-auto buttonnavbar" type="button" onclick="newAppointment()">New Appointment</button>
        <button class="btn btn-outline-success me-2 ms-auto buttonnavbar" type="button" onclick="logout()">Logout</button>
    </div>
</nav>



<body onload="enterName()">
    <!-- Search by lastname, give back number of entries -->
    <!--Search field-->

    <!-- message -->

    <div class="row justify-content-center" style="background-color: #DFF3E3;">
        <div class="col"></div>
        <div class="col-6" style="display: flex; justify-content: center; align-items: center;">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Title</th>
                        <th scope="col">Location</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Expiry date</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="listOfAppointments">
                </tbody>
            </table>
            <!--             <div class="text-center" id="listOfAppointments">
            </div> -->
        </div>
        <div class="col"></div>
    </div>
    <?php include "../frontend/app_form.php"; ?>
    <script src="js\app.js"></script>
</body>

</html>