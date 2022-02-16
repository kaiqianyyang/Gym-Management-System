<html>
<title> Insert Product Table</title>
<link rel="stylesheet" href="styleForInsert.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "Insert Product Table";
        include('operation-name-header.php'); 
    ?>
        <h2>Insert Values into Product</h2>
        <p>If you wish to insert a product, you MUST first click <b>Insert Product</b> button to input the product ID, then you can add detailed information to each product type.</p>
        <form method="POST" action="insert-table-product.php" class="form-inline"> <!--refresh page when submitted-->
            <!-- Input product information -->
            <p style="text-align:left">Product ID: </p>
            <input type="text" id="pid" name="pid" placeholder="Enter the Product ID"> <br /><br /> <!-- product id -->
            <!-- Insert button -->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            <input type="submit" value="Insert Product" name="insertProductSubmit"></p>
        </form>

        <hr />

        <h2>Insert Values into Membership</h2>
        <form method="POST" action="insert-table-product.php" class="form-inline"> <!--refresh page when submitted-->
            <!-- Input Membership information -->
            <p style="text-align:left">Product ID: </p>
            <input type="text" id="pid" name="pid" placeholder="Enter the Product ID"> <br /><br /> <!-- product id -->
            <p style="text-align:left">Product Fee:</p>
            <input type="text" id="monthlyfee" name="monthlyfee" placeholder="Enter the Monthly Fee"> <br /><br /> <!-- product fee -->
            <p style="text-align:left">Product Duration: </p>
            <input type="text" id="numofmonths" name="numofmonths" placeholder="Enter the Number of Months"> <br /><br /> <!-- product duration -->
            <!-- Insert Membership button -->
            <input type="hidden" id="insertQueryRequestForMembership" name="insertQueryRequestForMembership">
            <input type="submit" value="Insert Membership" name="insertMembershipSubmit"></p>
        </form>

        <hr />
        
        <h2>Insert Values into PersonalTrainingPackage</h2>
        <form method="POST" action="insert-table-product.php" class="form-inline"> <!--refresh page when submitted-->
            <!-- Input PersonalTrainingPackage information -->
            <p style="text-align:left">Product ID: </p>
            <input type="text" id="pid" name="pid" placeholder="Enter the Product ID"> <br /><br /> <!-- product id -->
            <p style="text-align:left">Product Fee:</p>
            <input type="text" id="entryfee" name="entryfee" placeholder="Enter the Entry Fee"> <br /><br /> <!-- product fee -->
            <p style="text-align:left">Product Duration: </p>
            <input type="text" id="numofsessions" name="numofsessions" placeholder="Enter the Number of Sessions"> <br /><br /> <!-- product duration -->
            <!-- Insert PersonalTrainingPackage button -->
            <input type="hidden" id="insertQueryRequestForPackage" name="insertQueryRequestForPackage">
            <input type="submit" value="Insert Package" name="insertPackageSubmit"></p>
        </form>

        <hr />

        <h2>Display the Tuples in Product</h2>
        <form method="GET" action="insert-table-product.php" class="form-inline"> <!--refresh page when submitted-->
            <!-- Display Product Table -->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value="Display Product" name="displayTuples"></p>
            <!-- Display Membership Table -->
            <input type="hidden" id="displayTupleRequestForMembership" name="displayTupleRequestForMembership">
            <input type="submit" value="Display Membership" name="displayTuplesForMembership"></p>
            <!-- Display PersonalTrainingPackage Table -->
            <input type="hidden" id="displayTupleRequestForPackage" name="displayTupleRequestForPackage">
            <input type="submit" value="Display Package" name="displayTuplesForPackage"></p>
        </form>

        <hr />

        <?php include ("connect.php"); ?> 
        <?php include ("sqlExecute.php"); ?>
        <?php
        
        /**
         * Handle print request for product
         * @param result  The result of SQL statement
         */
        function printResultForProduct($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Product:<br>";
            echo "<table>";
            echo "<tr><th>ProductID</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["PRODUCTID"] . "</td></tr>"; // ID?
            }

            echo "</table>";
        }

        /**
         * Handle print request for Membership
         * @param result  The result of SQL statement
         */
        function printResultForMembership($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Membership:<br>";
            echo "<table>";
            echo "<tr><th>ProductID</th><th>MonthlyFee</th><th>ProductDuration</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["PRODUCTID"] . "</td><td>" . $row["MONTHLYFEE"] . "</td><td>" . $row["NUMOFMONTHS"] . "</td></tr>"; // ID?
            }

            echo "</table>";
        }

        /**
         * Handle print request for package
         * @param result  The result of SQL statement
         */
        function printResultForPackage($result) { //prints results from a select statement
            echo "<br>Retrieved data from table PersonalTrainingPackage:<br>";
            echo "<table>";
            echo "<tr><th>ProductID</th><th>EntryFee</th><th>ProductDuration</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["PRODUCTID"] . "</td><td>" . $row["ENTRYFEE"] . "</td><td>" . $row["NUMOFSESSIONS"] . "</td></tr>"; // ID?
            }

            echo "</table>";
        }
        
        /**
         * drop and create table for Product, Membership, PersonalTrainingPackage
         * be careful about the drop order
         */
        function handleResetRequest() {
            global $db_conn;
            // Drop old table - cannot change the order
            executePlainSQL("DROP TABLE Membership");
            executePlainSQL("DROP TABLE PersonalTrainingPackage");
            executePlainSQL("DROP TABLE Product");

            // Create new table
            echo nl2br("creating new Product table\n");
            executePlainSQL("CREATE TABLE Product (productid int PRIMARY KEY)"); // id?
            echo nl2br("creating new Membership table\n");
            executePlainSQL("CREATE TABLE Membership (productid int PRIMARY KEY, MonthlyFee FLOAT UNIQUE, NumOfMonths int UNIQUE, FOREIGN KEY (productid) REFERENCES Product)"); 
            echo nl2br("creating new Package table\n");
            executePlainSQL("CREATE TABLE PersonalTrainingPackage (productid int PRIMARY KEY, EntryFee FLOAT UNIQUE, NumOfSessions int UNIQUE, FOREIGN KEY (productid) REFERENCES Product)"); 
            OCICommit($db_conn);
        }
        
        /**
         * Handle insert request for Product
         */
        function handleInsertRequestForProduct() {
            global $db_conn;

            // Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['pid'] // insNo?
            );

            $alltuples = array (
                $tuple
            );
            
            executeBoundSQL("insert into Product values (:bind1)", $alltuples);
            echo nl2br("Insert Product Successfully\n");
            OCICommit($db_conn);
        }

        /**
         * Handle insert request for Membership
         */
        function handleInsertRequestForMembership() {
            global $db_conn;

            // Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['pid'], // insNo?
                ":bind2" => $_POST['monthlyfee'],
                ":bind3" => $_POST['numofmonths'] 
            );

            $alltuples = array (
                $tuple
            );
            
            executeBoundSQL("insert into Membership values (:bind1,:bind2,:bind3)", $alltuples);
            echo nl2br("Insert Membership Successfully\n");
            OCICommit($db_conn);
        }

        /**
         * Handle insert request for package
         */
        function handleInsertRequestForPackage() {
            global $db_conn;

            // Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['pid'], // insNo?
                ":bind2" => $_POST['entryfee'],
                ":bind3" => $_POST['numofsessions'] 
            );

            $alltuples = array (
                $tuple
            );
            
            executeBoundSQL("insert into PersonalTrainingPackage values (:bind1,:bind2,:bind3)", $alltuples);
            echo nl2br("Insert Package Successfully\n");
            OCICommit($db_conn);
        }

        /**
         * Process display request for Product
         */
        function handleDisplayRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM Product");
            printResultForProduct($result);
        }

        /**
         * Process display request for Membership
         */
        function handleDisplayRequestForMembership() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM Membership");
            printResultForMembership($result);
        }

        /**
         * Process display request for Package
         */
        function handleDisplayRequestForPackage() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM PersonalTrainingPackage");
            printResultForPackage($result);
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
                    echo "BBBB";
                    handleDisplayRequest();
                    handleDisplayRequestForMembership();
                    handleDisplayRequestForPackage();
                    handleInsertRequestForProduct();
                } else if (array_key_exists('insertQueryRequestForMembership', $_POST)) {
                    handleInsertRequestForMembership();
                } else if (array_key_exists('insertQueryRequestForPackage', $_POST)) {
                    handleInsertRequestForPackage();
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
                if (array_key_exists('displayTuples', $_GET)) {
                    handleDisplayRequest();
                    handleDisplayRequestForMembership();
                    handleDisplayRequestForPackage();
                } else if (array_key_exists('displayTuplesForMembership', $_GET)) {
                    handleDisplayRequestForMembership();
                } else if (array_key_exists('displayTuplesForPackage', $_GET)) {
                    handleDisplayRequestForPackage();
                }
                disconnectFromDB();
            }
        }

        if (isset($_POST['reset']) || isset($_POST['insertProductSubmit']) 
            || isset($_POST['insertMembershipSubmit']) || isset($_POST['insertPackageSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayTupleRequest'])
            || isset($_GET['displayTupleRequestForMembership']) || isset($_GET['displayTupleRequestForPackage'])) {
            handleGETRequest();
        }

        
        ?>

    </body>
</html>


