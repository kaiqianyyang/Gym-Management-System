<html>
<title> Insert Customer Table</title>
<link rel="stylesheet" href="styleForInsert.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "Insert Customer Table";
        include('operation-name-header.php'); 
    ?>
        <!-- Reset Button -->
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>
        <form method="POST" action="insert-table-customer.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>Insert Values into Customer</h2>
        <p>Insert given values into Customer</p>
        <form method="POST" action="insert-table-customer.php" class="form-inline"> <!--refresh page when submitted-->
            <!-- Insert button -->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            <input type="submit" value="Insert Customer" name="insertCustomerSubmit"></p>
        </form>

        <hr />

        <h2>Display the Tuples in Customer</h2>
        <form method="GET" action="insert-table-customer.php" class="form-inline"> <!--refresh page when submitted-->
            <!-- Display Customer Table -->
            <input type="hidden" id="displayTupleRequestForCustomer" name="displayTupleRequestForCustomer">
            <input type="submit" value="Display Product" name="displayTuplesForCustomer"></p>
        </form>

        <hr />

        <?php include ("connect.php"); ?> 
        <?php include ("sqlExecute.php"); ?>
        <?php
        
        
        /**
         * drop and create table for Customer
         */
        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE Customer");

            // Create new table
            echo nl2br("creating new Customer table\n");
            executePlainSQL("CREATE TABLE Customer (CustomerID CHAR(20) PRIMARY KEY, 
                        FirstName CHAR(20), LastName CHAR(20), Email CHAR(30), 
                        PhoneNumber CHAR(20), MembershipExpireDate DATE, PrivateLessonRemaining int DEFAULT 0)");
            OCICommit($db_conn);
        }  

        /**
         * Handle print request for Customer
         * @param result  The result of SQL statement
         */
        function printResultForCustomer($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Customer:<br>";
            echo "<table>";
            echo "<tr><th>CustomerID</th><th>FirstName</th><th>LastName</th><th>Email</th><th>PhoneNumber</th><th>MembershipExpireDate</th><th>PrivateLessonRemaining</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["CUSTOMERID"] . "</td><td>" . $row["FIRSTNAME"] . "</td><td>" . $row["LASTNAME"] . "</td><td>" . $row["EMAIL"] . "</td><td>" . $row["PHONENUMBER"] . "</td><td>" . $row["MEMBERSHIPEXPIREDATE"] . "</td><td>" . $row["PRIVATELESSONREMAINING"] . "</td></tr>"; // ID?
            }

            echo "</table>";
        }

        /**
         * Handle insert request for Customer
         */
        function handleInsertRequestForCustomer() {
            global $db_conn;
            
            // executeBoundSQL("insert into Customer values (:bind1)", $alltuples);
            executePlainSQL("insert into Customer values ('10001', 'Amelia', 'Nelly', 'Ameliannelly@gmail.com', '4039543991', TO_DATE('2021-12-13','YYYY-MM-DD'), 2)");
            executePlainSQL("insert into Customer values ('10002', 'Ava', 'Freda', 'Avaffreda@gmail.com', '2503652794', TO_DATE('2022-03-11','YYYY-MM-DD'), 23)");
            executePlainSQL("insert into Customer values ('10003', 'Osca', 'Jim', 'Oscajjim@gmail.com', '2895715072', TO_DATE('2022-08-24','YYYY-MM-DD'), 15)");
            executePlainSQL("insert into Customer values ('10004', 'Ivy', 'Du', 'Ivyddu@gmail.com', '8199567549', TO_DATE('2021-12-13','YYYY-MM-DD'), 2)");
            executePlainSQL("insert into Customer values ('10005', 'Arthur', 'Aubry', 'Arthuraaubry@gmail.com', '2508329440', TO_DATE('2022-01-02','YYYY-MM-DD'), 10)");
            executePlainSQL("insert into Customer values ('10006', 'Claire', 'Du', 'Claireddu@gmail.com', '9889567549', TO_DATE('2021-12-13','YYYY-MM-DD'), 2)");
            executePlainSQL("insert into Customer values ('10007', 'Melisa', 'Du', 'Melisaddu@gmail.com', '7208323440', TO_DATE('2021-12-13','YYYY-MM-DD'), 10)");
            echo nl2br("Insert Customer Successfully\n");
            OCICommit($db_conn);
        }

        /**
         * Process display request for Customer
         */
        function handleDisplayRequestForCustomer() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM Customer");
            printResultForCustomer($result);
        }

        /**
         * HANDLE ALL POST ROUTES
         * A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
         */
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequestForCustomer();
                }

                disconnectFromDB();
            }
        }
        /**
         * HANDLE ALL GET ROUTES
         * A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
         */
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('displayTuplesForCustomer', $_GET)) {
                    handleDisplayRequestForCustomer();
                }
                disconnectFromDB();
            }
        }

        if (isset($_POST['reset']) || isset($_POST['insertCustomerSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayTupleRequestForCustomer'])) {
            handleGETRequest();
        }
        
        ?>

    </body>
</html>


