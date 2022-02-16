<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>selection</title>
</head>

<body>
    <?php include('navbar.php'); ?>
    <?php
    $title = "Selection";
    include('operation-name-header.php');
    ?>

    <div class="container-fluid d-grid gap-4">
        <?php include('selection/customer.php'); ?>
        <?php include('selection/staff.php'); ?>
        <?php include('selection/assessment.php'); ?>
    </div>

</body>

</html>


<?php
include("connect.php");
include("sqlExecute.php");

?>