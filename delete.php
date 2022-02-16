<html>
<title> Delete Product Table</title>
<link rel="stylesheet" href="styleForDelete.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "Delete Product Table";
        include('operation-name-header.php'); 
    ?>


        <h2>Delete Values in Product </h2> 
        <p style="color:grey;">Membership ID starts with 1, Package ID starts with 2.</p>
        <form method="POST" action="delete.php" class="form-inline"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequestForProduct" name="deleteQueryRequestForProduct">
            <!-- Input Product information -->
            <p style="text-align:left">Product ID: </p>
            <input type="text" id="insNoToDelete" name="insNoToDelete" placeholder="Enter the Product ID"> <br /><br />
            <!-- Delete Product button -->
            <input type="submit" value="Delete Product" name="deleteProductSubmit"></p>
        </form>

        <hr />
        
        <h2>Delete Values in Membership</h2>
        <form method="POST" action="delete.php" class="form-inline"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequestForMembership" name="deleteQueryRequestForMembership">
            <!-- Input Membership information -->
            <p style="text-align:left">Product ID: </p>
            <input type="text" id="insNoToDelete" name="insNoToDelete" placeholder="Enter the Product ID eg: 1xxxxxxx"> <br /><br />
            <!-- Delete Membership button -->
            <input type="submit" value="Delete Membership" name="deleteMembershipSubmit"></p>
        </form>

        <hr />

        <h2>Delete Values in PersonalTrainingPackage</h2>
        <form method="POST" action="delete.php" class="form-inline"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequestForPackage" name="deleteQueryRequestForPackage">
            <!-- Input Membership information -->
            <p style="text-align:left">Product ID: </p>
            <input type="text" id="insNoToDelete" name="insNoToDelete" placeholder="Enter the Product ID eg: 2xxxxxxx"> <br /><br />
            <!-- Delete Membership button -->
            <input type="submit" value="Delete Package" name="deletePackageSubmit"></p>
        </form>

        <hr />

        <h2>Display the Tuples in Product</h2>
        <form method="GET" action="delete.php" class="form-inline"> <!--refresh page when submitted-->
            <!-- Todo: Delete corresponding product according to its id and type -->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value="Display Product" name="displayTuples"></p>
            <input type="hidden" id="displayTupleRequestForMembership" name="displayTupleRequestForMembership">
            <input type="submit" value="Display Membership" name="displayTuplesForMembership"></p>
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
         * Handle print request for includes
         * @param result  The result of SQL statement
         */
        function printResultForIncludes($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Includes:<br>";
            echo "<table>";
            echo "<tr><th>ProductID</th><th>OrderNo</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["PRODUCTID"] . "</td><td>" . $row["ORDERNO"] . "</td></tr>";
            }

            echo "</table>";
        }

        /**
         * Handle delete request for Membership
         */
        function handleDeleteRequestForMembership() {
            global $db_conn;
            //Getting the values from user and insert data into the table
            $ins_no_to_delete = $_POST['insNoToDelete'];

            $result = executePlainSQL("SELECT '" . $ins_no_to_delete . "' FROM Membership");

            while (($row = oci_fetch_row($result))) {
                unset($insNoToDelete);
            }
            executePlainSQL("DELETE FROM Membership WHERE productid='" . $ins_no_to_delete . "'");
            echo nl2br("Delete Membership Successfully\n");
            OCICommit($db_conn);
        }

        /**
         * Handle delete request for Package
         */
        function handleDeleteRequestForPackage() {
            global $db_conn;
            //Getting the values from user and insert data into the table
            $ins_no_to_delete = $_POST['insNoToDelete'];

            $result = executePlainSQL("SELECT '" . $ins_no_to_delete . "' FROM PersonalTrainingPackage");

            while (($row = oci_fetch_row($result))) {
                unset($insNoToDelete);
            }
            executePlainSQL("DELETE FROM PersonalTrainingPackage WHERE productid='" . $ins_no_to_delete . "'");
            echo nl2br("Delete Package Successfully\n");
            OCICommit($db_conn);
        }

        /**
         * Handle delete request for Product
         */
        function handleDeleteRequestForProduct() {
            global $db_conn;
            //Getting the values from user and insert data into the table
            $ins_no_to_delete = $_POST['insNoToDelete'];

            $result = executePlainSQL("SELECT '" . $ins_no_to_delete . "' FROM Product");

            handleDeleteRequestForIncludes();

            while (($row = oci_fetch_row($result))) {
                unset($insNoToDelete);
            }

            executePlainSQL("DELETE FROM Product WHERE productid='" . $ins_no_to_delete . "'");
            echo nl2br("Delete Product Successfully\n");
            OCICommit($db_conn);
        }

        function handleDeleteRequestForIncludes() {
            global $db_conn;
            //Getting the values from user and insert data into the table
            $ins_no_to_delete = $_POST['insNoToDelete'];

            $result = executePlainSQL("SELECT '" . $ins_no_to_delete . "' FROM Includes");

            while (($row = oci_fetch_row($result))) {
                unset($insNoToDelete);
            }

            executePlainSQL("DELETE FROM Includes WHERE productid='" . $ins_no_to_delete . "'");
            echo nl2br("Delete Includes Successfully\n");
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
         * Process display request for Package
         */
        function handleDisplayRequestForIncludes() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM Includes");
            printResultForIncludes($result);
        }

        /**
         * HANDLE ALL POST ROUTES
         * A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
         */
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('deleteQueryRequestForMembership', $_POST)) {
                    handleDeleteRequestForMembership();
                    handleDeleteRequestForProduct();
                } else if (array_key_exists('deleteQueryRequestForPackage', $_POST)) {
                    handleDeleteRequestForPackage();
                    handleDeleteRequestForProduct();
                } else if (array_key_exists('deleteQueryRequestForProduct', $_POST)) {
                    if ($_POST['insNoToDelete'][0] == "1") {
                        handleDeleteRequestForMembership();
                        handleDeleteRequestForProduct();
                    } else if ($_POST['insNoToDelete'][0] == "2") {
                        handleDeleteRequestForPackage();
                        handleDeleteRequestForProduct();
                    }
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
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                }
                if (array_key_exists('displayTuples', $_GET)) {
                    handleDisplayRequest();
                    handleDisplayRequestForMembership();
                    handleDisplayRequestForPackage();
                    handleDisplayRequestForIncludes();
                }
                if (array_key_exists('displayTuplesForMembership', $_GET)) {
                    handleDisplayRequestForMembership();
                }
                if (array_key_exists('displayTuplesForPackage', $_GET)) {
                    handleDisplayRequestForPackage();
                }
                disconnectFromDB();
            }
        }

        // Todo: delete Product directly according to its type
        if (isset($_POST['reset'])
            || isset($_POST['deleteProductSubmit']) 
            || isset($_POST['deleteMembershipSubmit']) || isset($_POST['deletePackageSubmit'])
            || isset($_POST['insertProductSubmit']) || isset($_POST['insertMembershipSubmit']) || isset($_POST['insertPackageSubmit'])) {
            handlePOSTRequest();
        }
        if (isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest'])
            || isset($_GET['displayTupleRequestForMembership']) || isset($_GET['displayTupleRequestForPackage'])) {
            handleGETRequest();
        }

        
        ?>

    </body>
</html>

