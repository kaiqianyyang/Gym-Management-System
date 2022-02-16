<html>
<head>
<title> Update Product </title>
<link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "Update tuple in MembershipTable";
        include('operation-name-header.php'); 
    ?>
    <center>
          <hr />

        <h2>Display the Membership Table</h2>
        <form method="GET" action="update.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" name="displayTuples"></p>
        </form>

        <hr />

        <h2>Update tuple in Membership Table</h2> 
        <p>update MonthlyFee or NumOfMonths attributes.</p>

        <form method="POST" action="update.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateMembershipRequest" name="updateMembershipRequest">
            
            ProductID: <input type="text" name="productID" placeholder="Enter the productID"> <br /><br />
            New value: <input type="text" name="newValue" placeholder="Enter the new value"> <br /><br />
            <!-- <input type="text" name="attribute" placeholder="Enter the name of attribute"> <br /><br /> -->
            attribute: 
            <input type="radio" id="attribute" name="attribute" value="MonthlyFee" max="50">
            <label for="MonthlyFee">MonthlyFee</label><br>
            <input type="radio" id="attribute" name="attribute" value="NumOfMonths">
            <label for="NumOfMonths">NumOfMonths</label><br></p>

            <input type="submit" value="UpdateMembershipTable" name="updateMembershipSubmit"></p>
        </form>

       

    </center>

    <?php include ("connect.php"); ?>
    <?php include ("sqlExecute.php"); ?>
    <?php
        // Todo: implementation of handlers and requests
        function handleUpdateMembershipRequest() {

            global $db_conn;

            $productID = $_POST['productID'];
            $newValue = $_POST['newValue'];
            $attribute = $_POST['attribute'];

            if ($attribute == "MonthlyFee") {
                echo "<br>UPDATE Membership SET MonthlyFee='" . $newValue . "' WHERE ProductID='" .  $productID . "'<br>";
            } 
            echo "$attribute";
            if ($attribute == "NumOfMonths") {
                echo "<br>UPDATE Membership SET NumOfMonths='" . $newValue . "' WHERE ProductID='" .  $productID . "'<br>";
            } 
          
                       
            if ($attribute == "MonthlyFee") {
                executePlainSQL("UPDATE Membership SET MonthlyFee='" . $newValue . "' WHERE ProductID='" .  $productID . "'");
                echo "update MonthlyFee";
            } 
            echo "$attribute";
            if ($attribute == "NumOfMonths") {
                executePlainSQL("UPDATE Membership SET NumOfMonths='" . $newValue . "' WHERE ProductID='" .  $productID . "'");
                echo "update NumOfMonths";
            } 
            
            OCICommit($db_conn);


            echo "<br>Retrieved data from MembershipTable:<br>";
            echo "<table>";
            echo "<tr><th>ProductID</th><th>MonthlyFee</th><th>NumOfMonths</th></tr>";
            $membership = executePlainSQL("SELECT *  FROM Membership");
            while ($row = OCI_Fetch_Array($membership, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]" 
            }

            echo "</table>";
        }

        function handleDisplayRequest() {
        

            echo "<br> data from tableMembershipTable:<br>";
            echo "<table>";
            echo "<tr><th>ProductID</th><th>MonthlyFee</th><th>NumOfMonths</th></tr>";
            $membership = executePlainSQL("SELECT *  FROM Membership");
            while ($row = OCI_Fetch_Array($membership, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]" 
            }

            echo "</table>";

            
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateMembershipSubmit', $_POST)) {
                    handleUpdateMembershipRequest();
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


    if (isset($_POST['reset']) ||  isset($_POST['updateMembershipSubmit'])) {
        handlePOSTRequest();
    } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest']) ) {
        handleGETRequest();
    }

    ?>

    
</body>
</html>

