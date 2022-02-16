<html>
<title> Projection </title>
<link rel="stylesheet" href="styleForProjection.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "Projection on Customer, Trainer, Train Table";
        include('operation-name-header.php'); 
    ?>

    <form method="GET" action="projection.php"> <!--refresh page when submitted-->
        <h2>Display Customer, Trainer, Train Table</h2> 
        <div  class="form-inline">
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value="Display Tables" name="displayTables"></p>
        </div>
    </form>
    <hr />

    <form method="POST" action="projection.php"><!--refresh page when submitted-->
        <h2>Projection on Customer, Trainer, Train</h2> 
        <p style="color:grey;">Example: SELECT DISTINCT C.CUSTOMERID, S.STAFFID FROM TRAIN T, TRAINER S, CUSTOMER C WHERE S.STAFFID>102 AND T.TIMESLOT>3 AND T.TRAIN_DATE=TO_DATE('2021-01-13','YYYY-MM-DD');</p>
        <p>Please check the checkbox of columns from TABLE CUSTOMER C you want to display in the result.</p>
            <input type="checkbox" id="cid" name="columnsSelection[]" value="cid" max="50" checked>
            <label for="cid">Customer ID</label><br>
            <input type="checkbox" id="fname" name="columnsSelection[]" value="fname">
            <label for="fname">First Name</label><br>
            <input type="checkbox" id="lname" name="columnsSelection[]" value="lname">
            <label for="lname">Last Name</label><br>
            <input type="checkbox" id="email" name="columnsSelection[]" value="email">
            <label for="email">Email</label><br>
            <input type="checkbox" id="phonenumber" name="columnsSelection[]" value="phonenumber">
            <label for="phonenumber">Phone Number</label><br>
            <input type="checkbox" id="expiredate" name="columnsSelection[]" value="expiredate">
            <label for="expiredate">Membership Expire Date</label><br>
            <input type="checkbox" id="lessonremaining" name="columnsSelection[]" value="lessonremaining">
            <label for="lessonremaining">Private Lessons Remaining</label><br>
        <hr/>
        <p>Please check the checkbox of columns from TABLE TRAINER S you want to display in the result, select and input the constraints for StaffID.</p>
            <input type="checkbox" id="sid" name="columnsSelection[]" value="sid" max="50" checked>
            <label for="sid">Staff ID</label><br>
                    <label><input name="sidoperators" type="radio" value=">" checked>GT</label>
                    <label><input name="sidoperators" type="radio" value="=">EQ</label>
                    <label><input name="sidoperators" type="radio" value="<">LT</label>
                    <input type="number" id="sidConditionValue" name="sidConditionValue" placeholder="eg: 102" value=102>
        <hr/>
        <p>Please check the checkbox of columns from TABLE TRAIN T you want to display in the result, select and input the constraints for TimeSlot and Date.</p>
            <input type="checkbox" id="timeslot" name="columnsSelection[]" value="timeslot" max="50">
            <label for="timeslot">Time Slot</label><br>
                    <label><input name="timeoperators" type="radio" value=">" checked>GT</label>
                    <label><input name="timeoperators" type="radio" value="=">EQ</label>
                    <label><input name="timeoperators" type="radio" value="<">LT</label>
                    <input type="number" id="timeslotConditionValue" name="timeslotConditionValue" placeholder="eg: 3" value=3>
                <br><br>
            <input type="checkbox" id="date" name="columnsSelection[]" value="date">
            <label for="date">Date</label><br>
                    <label><input name="dateoperators" type="radio" value=">">GT</label>
                    <label><input name="dateoperators" type="radio" value="=" checked>EQ</label>
                    <label><input name="dateoperators" type="radio" value="<">LT</label>
                    <input type="date" id="dateConditionValue" name="dateConditionValue" value="2021-01-13" min="2014-01-01" max="2022-12-31">
                <br><br>
            <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">
            <input type="submit" value="Projection" name="projectionSubmit"></p>
    </form>

    <hr />

        <?php include ("connect.php"); ?>
        <?php include ("sqlExecute.php"); ?>
        <?php
        
        /**
         * Handle print request for product
         * @param result  The result of SQL statement
         */
        
        function printResult($result, $tuples) { //prints results from a select statement
            $columns = getColumnNameStr($tuples);
            echo "<br>Retrieved data from table Customer:<br>";
            echo "<table>";
            echo $columns;

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $ans = getEachRowArray($tuples);
                echo "<tr>";
                for ($i = 1; $i <= count($tuples); $i++) {
                    echo "<td>" . $row[$ans[$i-1]] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }

        function getEachRowArray($tuples) {
            $ans = array();
            for ($i = 1; $i <= count($tuples); $i++) {
                $key = ":bind" . $i;
                $str = transformColumnName($tuples[$key])[2];
                array_push($ans, $str);
            }
            return $ans;
        }

        function getColumnNameStr($tuples) {
            $numOfAttr = count($tuples);
            $str = "<tr>";
            for ($i = 1; $i <= $numOfAttr; $i++) {
                $key = ":bind" . $i;
                $str = $str . "<th>" . transformColumnName($tuples[$key])[1] . "</th>";
            }
            $str = $str . "</tr>";
            return $str;
        }

        /**
         * Handle projection request for product
         */
        function handleProjectionRequest() {
            global $db_conn;
            $checkedColumns = projectionCheckboxHelper();
            $numOfAttr = count($_POST['columnsSelection']);
            $tuples = array();
            $columnsStr = $checkedColumns[0];
            for ($i = 1; $i <= $numOfAttr; $i++) {
                $key = ":bind" . $i;
                $tuples[$key] = $checkedColumns[$i-1];
                if ($i != 1) {
                    $columnsStr = $columnsStr . ", " . $checkedColumns[$i-1];
                }
            }
            $alltuples = array (
                $tuples
            );

            $conditionValue = projectionConditionHelper(); // create a conditions string in the WHERE
            if ($conditionValue == "NoWhereCondition") {
                $result = executePlainSQL("SELECT DISTINCT " . $columnsStr . " FROM Customer c, Trainer s, Train t");
            } else {
                $result = executePlainSQL("SELECT DISTINCT " . $columnsStr . " FROM Customer c, Trainer s, Train t WHERE " . $conditionValue);
            }

            printResult($result, $tuples);
            OCICommit($db_conn);
        }

        function projectionConditionHelper() {
            $sid_con_array = $_POST['sidoperators'];
            $time_con_array = $_POST['timeoperators'];
            $date_con_array = $_POST['dateoperators'];
            $con_array = array();
            
            if ($_POST['sidoperators'] && $_POST['sidConditionValue']) {
                $str = "S.STAFFID" . $_POST['sidoperators'] . $_POST['sidConditionValue'];
                array_push($con_array, $str);
            }
            if ($_POST['timeoperators'] && $_POST['timeslotConditionValue']) {
                $str = "T.TIMESLOT" . $_POST['timeoperators'] . $_POST['timeslotConditionValue'];
                array_push($con_array, $str);
            }
            if ($_POST['dateoperators'] && $_POST['dateConditionValue']) { // TO_DATE('2021-01-13','YYYY-MM-DD')
                $str = "T.TRAIN_DATE" . $_POST['dateoperators'] . "TO_DATE('" . $_POST['dateConditionValue'] . "','YYYY-MM-DD')";
                array_push($con_array, $str);
            }

            // var_dump($con_array);

            $con_str = "NoWhereCondition";
            if ($con_array) {
                $con_str = $con_array[0];
                $numOfAttr = count($con_array);
                for ($i = 1; $i < $numOfAttr; $i++) {
                    $con_str = $con_str . " AND " . $con_array[$i] ;
                }
            }
            return $con_str;
        }


        function projectionCheckboxHelper() {
            if(isset($_POST['projectionSubmit'])){
                $chk_array = $_POST['columnsSelection'];
                $cnt_array = count($_POST['columnsSelection']);
                $tuple = array();
                foreach($chk_array as $selected_val) {
                    array_push($tuple, transformColumnName($selected_val)[0]);
                }
                return $tuple;
            }
        }

        function transformColumnName($input) {
            $ans;
            if ($input == "cid" || $input == "C.CUSTOMERID") {
                $ans = array("C.CUSTOMERID", "CustomerID", "CUSTOMERID");
            } else if ($input == "fname" || $input == "C.FIRSTNAME") {
                $ans = array("C.FIRSTNAME", "First Name", "FIRSTNAME");
            } else if ($input == "lname" || $input == "C.LASTNAME") {
                $ans = array("C.LASTNAME", "Last Name", "LASTNAME");
            } else if ($input == "email" || $input == "C.EMAIL") {
                $ans = array("C.EMAIL", "Email", "EMAIL");
            } else if ($input == "phonenumber" || $input == "C.PHONENUMBER") {
                $ans = array("C.PHONENUMBER", "Phone Number", "PHONENUMBER");
            } else if ($input == "expiredate" || $input == "C.MEMBERSHIPEXPIREDATE") {
                $ans = array("C.MEMBERSHIPEXPIREDATE", "Membership ExpireDate", "MEMBERSHIPEXPIREDATE");
            } else if ($input == "lessonremaining" || $input == "C.PRIVATELESSONREMAINING") {
                $ans = array("C.PRIVATELESSONREMAINING", "Private Lessons Remaining", "PRIVATELESSONREMAINING");
            } else if ($input == "sid" || $input == "S.STAFFID") {
                $ans = array("S.STAFFID", "Staff ID", "STAFFID");
            } else if ($input == "timeslot" || $input == "T.TIMESLOT") {
                $ans = array("T.TIMESLOT", "Time Slot", "TIMESLOT");
            } else if ($input == "date" || $input == "T.TRAIN_DATE") {
                $ans = array("T.TRAIN_DATE", "Train Date", "TRAIN_DATE");
            }
            return $ans;
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
        
        /**
         * HANDLE ALL POST ROUTES
         * A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
         */
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('projectionQueryRequest', $_POST)) {
                    handleDisplayRequest();
                    handleProjectionRequest();
                }

                disconnectFromDB();
            }
        }

        // Todo: delete Product directly according to its type
        if (isset($_POST['projectionSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayTupleRequest'])) {
            handleGETRequest();
        }
  
        ?>

    </body>
</html>