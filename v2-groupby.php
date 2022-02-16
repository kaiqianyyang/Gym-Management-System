<!-- 3 tables, cannot display columns of COUNT(...) -->
<html>
<title> Group By </title>
<link rel="stylesheet" href="styleForGroupBy.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "Group By on Customer, Trainer, Train Table";
        include('operation-name-header.php'); 
    ?>

    <form method="GET" action="v2-groupby.php"> <!--refresh page when submitted-->
        <h2>Display Customer, Trainer, Train Table</h2> 
        <div  class="form-inline">
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value="Display Tables" name="displayTables"></p>
        </div>
    </form>
    <hr />
    
    <form method="POST" action="v2-groupby.php"><!--refresh page when submitted-->

        <h2>Select Columns on Customer, Trainer, Train Table</h2> 
        <p style="color:grey;">Example: SELECT C.LastName FROM Train T, Trainer S, Customer C WHERE S.StaffID=T.StaffID AND C.CustomerID=T.CustomerID AND T.TimeSlot=15 GROUP BY C.LastName;</p>
        <p>Please select the columns you want to project in the table Customer.</p>
        <div  class="form-inline">
            <input type="checkbox" id="fname" name="columnsSelection[]" value="fname">
            <label for="fname">First Name</label><br>
                <label><input name="fn_aggregation" type="radio" value="DEFAULT" checked>DEFAULT</label>
                <label><input name="fn_aggregation" type="radio" value="COUNT">COUNT</label>
        </div>

        <div  class="form-inline">
            <input type="checkbox" id="lname" name="columnsSelection[]" value="lname" checked>
            <label for="lname">Last Name</label><br>
                <label><input name="ln_aggregation" type="radio" value="DEFAULT" checked>DEFAULT</label>
                <label><input name="ln_aggregation" type="radio" value="COUNT">COUNT</label>
        </div>

        <div  class="form-inline">
            <input type="checkbox" id="cid" name="columnsSelection[]" value="cid" max="50" checked>
            <label for="cid">Customer ID</label><br>
                <label><input name="cid_aggregation" type="radio" value="DEFAULT">DEFAULT</label>
                <label><input name="cid_aggregation" type="radio" value="COUNT" checked>COUNT</label>
        </div>

        <div class="form-inline">
            <input type="checkbox" id="email" name="columnsSelection[]" value="email">
            <label for="email">Email</label><br>
                <label><input name="em_aggregation" type="radio" value="DEFAULT" checked>DEFAULT</label>
                <label><input name="em_aggregation" type="radio" value="COUNT">COUNT</label>
        </div>

        <div  class="form-inline">
            <input type="checkbox" id="phonenumber" name="columnsSelection[]" value="phonenumber">
            <label for="phonenumber">Phone Number</label><br>
                <label><input name="pn_aggregation" type="radio" value="DEFAULT" checked>DEFAULT</label>
                <label><input name="pn_aggregation" type="radio" value="COUNT">COUNT</label>
        </div>

        <div  class="form-inline">
            <input type="checkbox" id="expiredate" name="columnsSelection[]" value="expiredate">
            <label for="expiredate">Membership Expire Date</label><br>
                <label><input name="ed_aggregation" type="radio" value="DEFAULT" checked>DEFAULT</label>
                <label><input name="ed_aggregation" type="radio" value="COUNT">COUNT</label>
        </div>

        <div  class="form-inline">
            <input type="checkbox" id="lessonremaining" name="columnsSelection[]" value="lessonremaining">
            <label for="lessonremaining">Private Lessons Remaining</label><br> </p>
                <label><input name="lm_aggregation" type="radio" value="DEFAULT" checked>DEFAULT</label>
                <label><input name="lm_aggregation" type="radio" value="MIN">MIN</label>
                <label><input name="lm_aggregation" type="radio" value="MAX">MAX</label>
                <label><input name="lm_aggregation" type="radio" value="AVG">AVG</label>
                <label><input name="lm_aggregation" type="radio" value="COUNT">COUNT</label>
        </div>
    
    <hr />
    <h2>The Condition of WHERE</h2> 
        <p>S.StaffID=T.StaffID AND C.CustomerID=T.CustomerID</p>
        
        <p style="font-size: 20px;">AND 
        <input type="checkbox" id="timeslot" name="timeSelection[]" value="timeslot" max="50" checked>
            <label for="timeslot">Time Slot</label>
                    <label><input name="timeoperators" type="radio" value=">">GT</label>
                    <label><input name="timeoperators" type="radio" value="=" checked>EQ</label>
                    <label><input name="timeoperators" type="radio" value="<">LT</label>
                    <input type="number" id="timeslotConditionValue" name="timeslotConditionValue" value=15>
                <br><br>
                </p>
    <hr />

    <h2>Select Columns To Group by</h2> 
        <p>Please select the columns you want to groupby in the table Customer, your selection MUST be in selected columns above.</p>
        <div  class="form-inline">
            <p>GROUP BY: </p>
                <label><input name="groupby" type="radio" value="cid">CustomerID</label>
                <label><input name="groupby" type="radio" value="fname">FirstName</label>
                <label><input name="groupby" type="radio" value="lname" checked>LastName</label>
                <label><input name="groupby" type="radio" value="email">Email</label>
                <label><input name="groupby" type="radio" value="phonenumber">PhoneNumber</label>
                <label><input name="groupby" type="radio" value="expiredate">MembershipExpireDate</label>
                <label><input name="groupby" type="radio" value="lessonremaining">PrivateLessonsRemaining</label>
                <label><input name="groupby" type="radio" value="default">Default</label>
        </div>

        <!-- <input type="hidden" id="groupbyQueryRequest" name="groupbyQueryRequest">
        <input type="submit" value="Group By" name="groupbySubmit"></p> -->

        <input type="hidden" id="groupbyQueryRequest" name="groupbyQueryRequest">
        <input type="submit" value="Group By" name="groupbySubmit"></p>

    </form>

    <hr />

        <?php include ("connect.php"); ?>
        <?php include ("sqlExecute.php"); ?>
        <?php

        function handleGroupbyRequest() {
            global $db_conn;
            $result = executePlainSQL("SELECT C.LASTNAME, COUNT(C.CUSTOMERID) FROM Train T, Trainer S, Customer C WHERE S.STAFFID=T.STAFFID AND C.CUSTOMERID=T.CUSTOMERID AND T.TIMESLOT=15 GROUP BY C.LASTNAME");
            printGroupbyResult($result);
            
            OCICommit($db_conn);
        }
        
        // SELECT C.LastName, COUNT(C.CustomerID) FROM Train T, Trainer S, Customer C WHERE S.StaffID=T.StaffID AND C.CustomerID=T.CustomerID AND T.TimeSlot=15 GROUP BY C.LastName;
        function printGroupbyResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Train T, Trainer S, Customer C:<br>";
            echo "<table>";
            echo "<tr><th>LastName</th><th>Count(CustomerID)</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["LASTNAME"] . "</td><td>" . $row["COUNT(C.CUSTOMERID)"] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function displayUsedTable() {
            global $db_conn;
            $result_customer = executePlainSQL("SELECT * FROM CUSTOMER");
            $result_trainer = executePlainSQL("SELECT * FROM TRAINER");
            $result_train = executePlainSQL("SELECT * FROM TRAIN");
            printResultForCustomer($result_customer);
            printResultForTrainer($result_trainer);
            printResultForTrain($result_train);
            
            OCICommit($db_conn);
        }

        function printResultForCustomer($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Customer:<br>";
            echo "<table>";
            echo "<tr><th>CustomerID</th><th>FirstName</th><th>LastName</th><th>Email</th><th>PhoneNumber</th><th>MembershipExpireDate</th><th>PrivateLessonRemaining</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["CUSTOMERID"] . "</td><td>" . $row["FIRSTNAME"] . "</td><td>" . $row["LASTNAME"] . "</td><td>" . $row["EMAIL"] . "</td><td>" . $row["PHONENUMBER"] . "</td><td>" . $row["MEMBERSHIPEXPIREDATE"] . "</td><td>" . $row["PRIVATELESSONREMAINING"] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function printResultForTrainer($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Trainer:<br>";
            echo "<table>";
            echo "<tr><th>StaffID</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["STAFFID"] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        function printResultForTrain($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Train:<br>";
            echo "<table>";
            echo "<tr><th>TimeSlot</th><th>Train_Date</th><th>CustomerID</th><th>StaffID</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["TIMESLOT"] . "</td><td>" . $row["TRAIN_DATE"] . "</td><td>" . $row["CUSTOMERID"] . "</td><td>" . $row["STAFFID"] . "</td></tr>"; //or just use "echo $row[0]"
            }
            echo "</table>";
        }

        // HANDLE ALL POST ROUTES
        // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('groupbyQueryRequest', $_POST)) {
                    handleDisplayRequest();
                    handleGroupbyRequest();
                }

                disconnectFromDB();
            }
        }

        function handleDisplayRequest() {
            global $db_conn;
            displayUsedTable();
        }

        // HANDLE ALL GET ROUTES
        // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('displayTables', $_GET)) {
                    handleDisplayRequest();
               }
                disconnectFromDB();
            }
        }

        if (isset($_POST['groupbySubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayTupleRequest'])) {
            handleGETRequest();
        }

        ?>

    </body>
</html>

