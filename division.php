<html>
<head>
<title> division operation </title>
<link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "division table";
        include('operation-name-header.php'); 
    ?>
    <center>
        <hr />

        <h2>Display the Tuples in all related Table</h2>
        <form method="GET" action="division.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" name="displayTuples"></p>
        </form>

        <hr />

        <h2>division: Want to get the salesperson's StaffID who selled to all customers(involved with three tables: Customer, Order_Sell_Renew, Salesperson)</h2>
        
        <form method="POST" action="division.php"> <!--refresh page when submitted-->
            <input type="hidden" id="divisionRequest" name="divisionRequest"> 
            <input type="submit" value="divisionTable" name="divisionSubmit"></p>
        </form>

       

    </center>

    <?php include ("connect.php"); ?>
    <?php include ("sqlExecute.php"); ?>
    <?php
        // Todo: implementation of handlers and requests
        function handleDivisionSubmitRequest() {
            echo "<br>SELECT S.staffID S <br />
            FROM salesperson S <br />
            WHERE Not Exists <br />
            ((select C.customerID from Customer C)<br />
            MINUS<br />
            (select O.customerID from Order_Sell_Renew O  where S.staffID = O.staffID))<br>";
            global $db_conn;

            $divisionTable = executePlainSQL("SELECT S.staffID S
                                                FROM salesperson S
                                                WHERE Not Exists 
                                                ((select C.customerID from Customer C)
                                                MINUS
                                                (select O.customerID from Order_Sell_Renew O  where S.staffID = O.staffID))");   
     
            
            OCICommit($db_conn);




            echo "<br>Retrieved data from divisionTable:<br>";
            echo "<table>";
            echo "<tr><th>StaffID</th></tr>";
           
            while ($row = OCI_Fetch_Array($divisionTable, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr> "; //or just use "echo $row[0]" 
            }

            echo "</table>";
        }

        function handleDisplayRequest() {
            $order = executePlainSQL("SELECT *  FROM Order_Sell_Renew");
            $customer = executePlainSQL("SELECT *  FROM Customer");
            $salesPerson = executePlainSQL("SELECT *  FROM Salesperson");

            echo "<br> data from table Order_Sell_Renew:<br>";
            echo "<table>";
            echo "<tr><th>OrderNo</th><th>OSR_Date</th><th>TotalPrice</th><th>CustomerID</th><th>StaffID</th></tr>";

            while ($row = OCI_Fetch_Array($order, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2] . "</td><td>". $row[3] . "</td><td>". $row[4] . "</td></tr>"; //or just use "echo $row[0]" 
            }

            echo "</table>";

            echo "<br>data from table Customer:<br>";
            echo "<table>";
            echo "<tr><th>CustomerID</th><th>FirstName</th><th>LastName</th><th>Email</th><th>PhoneNumber</th><th>MembershipExpireDate</th><th>PrivateLessonRemaining</th></tr>";

            while ($row = OCI_Fetch_Array($customer, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2] . "</td><td>". $row[3] . "</td><td>". $row[4] . "</td><td>". $row[5] . "</td><td>". $row[6] . "</td></tr>"; //or just use "echo $row[0]" 
            }

            echo "</table>";

            echo "<br>data from table SalesPerson:<br>";
            echo "<table>";
            echo "<tr><th>StaffID</th><th>TotalSaleAmount</th></tr>";

            while ($row = OCI_Fetch_Array($salesPerson, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]" 
            }

            echo "</table>";

            
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                 if (array_key_exists('divisionSubmit', $_POST)) {
                    handleDivisionSubmitRequest();
                }
                
                disconnectFromDB();
            }
        }

         // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest() {
        if (connectToDB()) {
            if (array_key_exists('displayTuples', $_GET)) {
                handleDisplayRequest();
            }
            disconnectFromDB();
        }
    }

    if (isset($_POST['divisionRequest'])) {
        handlePOSTRequest();
    } else if (isset($_GET['displayTupleRequest'])) {
        handleGETRequest();
    }

    ?>

    
</body>
</html>
