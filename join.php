<html>
<head>
<title> Join Product </title>
<link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "Join";
        include('operation-name-header.php'); 
    ?>
    <center>
        <hr />

        <h2>Display the Tuples in all related tables</h2>
        <form method="GET" action="join.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" name="displayTuples"></p>
        </form>

        <hr />

        <h2>Join tuple in Order_Sell_Renew and customer to get the orderNo and firstName and LastName of customer who made a purchase and another condition specified by the user</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="join.php"> <!--refresh page when submitted-->
            <input type="hidden" id="joinOrderAndCustomerRequest" name="joinOrderAndCustomerRequest">
            table:  <input type="text" name="table" placeholder="Order_Sell_Renew / Customer"> <br /><br />
            attribute:  <input type="text" name="attribute" > <br /><br />
            attribute value: <input type="text" name="value" placeholder="attribute value"> <br /><br />
        

            <input type="submit" value="joinOrderAndCustomerTable" name="joinOrderAndCustomerSubmit"></p>
        </form>

       

    </center>

    <?php include ("connect.php"); ?>
    <?php include ("sqlExecute.php"); ?>
    <?php
        // Todo: implementation of handlers and requests
        function handleJoinOrderAndCustomerSubmitRequest() {
           
            global $db_conn;

            $table = $_POST['table']; 
            $attribute = $_POST['attribute']; 
            $value = $_POST['value']; 

            echo "<br>SELECT distinct FirstName, LastName, OrderNo
            FROM Order_Sell_Renew, Customer
            WHERE Customer.CustomerID = Order_Sell_Renew.CustomerID AND '" . $table . "'.'" . $attribute . "' = '" . $value . "' <br>";

            $joinTable = executePlainSQL("SELECT distinct FirstName, LastName, OrderNo
                                        FROM Order_Sell_Renew, Customer
                                        WHERE Customer.CustomerID = Order_Sell_Renew.CustomerID AND " . $table . "." . $attribute . " = '" . $value . "' ");
            OCICommit($db_conn);


            echo "<br>Retrieved data from JoinOrderAndCustomerTable:<br>";
            echo "<table>";
            echo "<tr><th>FirstName</th><th>LastName</th><th>OrderNo</th></tr>";
           
            while ($row = OCI_Fetch_Array($joinTable, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td> ". $row[2] . "</td></tr> "; //or just use "echo $row[0]" 
            }

            echo "</table>";
        }

        function handleDisplayRequest() {
            $order = executePlainSQL("SELECT *  FROM Order_Sell_Renew");
            $customer = executePlainSQL("SELECT *  FROM Customer");
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

            
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('joinOrderAndCustomerSubmit', $_POST)) {
                    handleJoinOrderAndCustomerSubmitRequest();
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

    if ( isset($_POST['joinOrderAndCustomerSubmit'])) {
        handlePOSTRequest();
    } else if (isset($_GET['displayTupleRequest']) ) {
        handleGETRequest();
    }

    ?>

    
</body>
</html>
