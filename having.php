<html>
<title> Group By and Having </title>
<link rel="stylesheet" href="styleForHaving.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php 
        $title = "Group By and Having on Customer, Trainer, Train Table";
        include('operation-name-header.php'); 
    ?>
    
    <form method="POST" action="having.php"><!--refresh page when submitted-->


        <h2>Select Columns on Customer, Trainer, Train Table</h2> 
        <p style="color:grey;">Example: SELECT C.LastName, COUNT(DISTINCT C.PrivateLessonRemaining) FROM Train T, Trainer S, Customer C WHERE S.StaffID=T.StaffID AND C.CustomerID=T.CustomerID GROUP BY C.LastName HAVING COUNT(DISTINCT C. PrivateLessonRemaining)=1;</p>
        <p>Please select the columns you want to project in the table Customer.</p>
        <div  class="form-inline">
            <input type="checkbox" id="cid" name="columnsSelection[]" value="cid" max="50">
            <label for="cid">Customer ID</label><br>
                <label><input name="cid_aggregation" type="radio" value="DEFAULT" checked>DEFAULT</label>
                <label><input name="cid_aggregation" type="radio" value="COUNT">COUNT</label>
        </div>

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
            <input type="checkbox" id="lessonremaining" name="columnsSelection[]" value="lessonremaining" checked>
            <label for="lessonremaining">Private Lessons Remaining</label><br> </p>
                <label><input name="lm_aggregation" type="radio" value="DEFAULT">DEFAULT</label>
                <label><input name="lm_aggregation" type="radio" value="MIN">MIN</label>
                <label><input name="lm_aggregation" type="radio" value="MAX">MAX</label>
                <label><input name="lm_aggregation" type="radio" value="AVG">AVG</label>
                <label><input name="lm_aggregation" type="radio" value="COUNT" checked>COUNT</label>
        </div>

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

        <hr />

        <h2>Select Having Conditions</h2> 
            <p>Please select the columns you want to groupby in the table Customer, your selection MUST be in selected columns above.</p>
            <div  class="form-inline">
                <p>COUNT(PrivateLessonsRemaining) </p>
                    <label><input name="operators" type="radio" value=">">GT</label>
                    <label><input name="operators" type="radio" value="=">EQ</label>
                    <label><input name="operators" type="radio" value="<">LT</label>
                    <input type="number" id="conditionValue" name="conditionValue">
            </div>

            <input type="hidden" id="groupbyQueryRequest" name="groupbyQueryRequest">
        <input type="submit" value="Having" name="groupbySubmit"></p>

    </form>

    <hr />

        <?php include ("connect.php"); ?>
        <?php include ("sqlExecute.php"); ?>
        <?php

        /**
         * Handle print request when there is a groupby condition
         * @param  string The executed SQL statement
         * @param  array Array of column to display
         * @param  array Array of column names that are displayed in the first row of the table
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
                $str = getColNameAndAggr($tuples[$key])[0];
                array_push($ans, $str);
            }
            return $ans;
        }

        function getColumnNameStr($tuples) {
            $numOfAttr = count($tuples);
            $str = "<tr>";
            for ($i = 1; $i <= $numOfAttr; $i++) {
                $key = ":bind" . $i;
                $str = $str . "<th>" . getColNameAndAggr($tuples[$key])[1] . "</th>";
            }
            $str = $str . "</tr>";
            return $str;
        }

        /**
         * Handle print request when there is no groupby condition
         * @param  string The executed SQL statement
         * @param  array Array of column to display
         * @param  array Array of column names that are displayed in the first row of the table
         */
        function printResultByGroupby($result, $tuples, $colNameToDisplayArray) {
            $columns = getGroupByColumnNameStr($colNameToDisplayArray);
            echo "<br>Retrieved data from table Customer:<br>";
            echo "<table>";
            echo $columns;

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $ans = getGroupByEachRowArray($tuples);
                echo "<tr>";
                for ($i = 1; $i <= count($tuples); $i++) {
                    echo "<td>" . $row[$ans[$i-1]] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }

        function getGroupByEachRowArray($tuples) {
            $ans = array();
            for ($i = 1; $i <= count($tuples); $i++) {
                $key = ":bind" . $i;
                $str = $tuples[$key];
                array_push($ans, $str);
            }
            return $ans;
        }

        function getGroupByColumnNameStr($colNameToDisplayArray) {
            $str = "<tr>";
            foreach ($colNameToDisplayArray as $selected_val) {
                $str = $str . "<th>" . $selected_val . "</th>";
            }
            $str = $str . "</tr>";
            return $str;
        }

        /**
         * Handle Groupby Request
         * 
         * get request from user and display the Customer table
         */
        function handleGroupbyRequest() {
            global $db_conn;
            $checkedColumns = groupbyCheckboxHelper();
            $numOfAttr = count($_POST['columnsSelection']);
            $tuples = array();
            $colNameToDisplayArray = array(); // the columns name in the displayed table
            $columnsStr = $checkedColumns[0];

            for ($i = 1; $i <= $numOfAttr; $i++) {
                $key = ":bind" . $i;
                $tuples[$key] = $checkedColumns[$i-1];
                array_push($colNameToDisplayArray, $checkedColumns[$i-1]);
                if ($i != 1) {
                    $columnsStr = $columnsStr . ", " . $checkedColumns[$i-1];
                }
            }
            $alltuples = array (
                $tuples
            );

            $groupby_col = getGroupbyCol();
            $having_opt = $_POST['operators'];
            $having_val = $_POST['conditionValue'];
            $having_con = "Count(PrivateLessonRemaining) " . $_POST['operators'] . " ". $_POST['conditionValue'];
            
            if ($groupby_col == "NoGroupBy") {
                $result = executePlainSQL("SELECT " . $columnsStr . " FROM Customer");
                printResult($result, $tuples);
            } else {
                $result = executePlainSQL("SELECT " . $columnsStr . " FROM Customer GROUP BY " . $groupby_col . " HAVING " . $having_con);
                printResultByGroupby($result, $tuples, $colNameToDisplayArray);
            }

            OCICommit($db_conn);
        }

        /**
         * Handle Aggregation
         * 
         * @return array
         * 
         * return an array of checked column names, with aggregation added to the columns
         */
        function groupbyCheckboxHelper() {
            if(isset($_POST['groupbySubmit'])){
                $chk_array = $_POST['columnsSelection']; // selected columns of customer attr
                $cnt_array = count($_POST['columnsSelection']); // the number of selected columns
                $tuple = array();
                foreach($chk_array as $selected_val) {
                    $selected_aggr = $_POST[getColNameAndAggr($selected_val)[2]];  
                    $aggregated_val = addAggregation($selected_val);
                    array_push($tuple, $aggregated_val);
                }
                return $tuple;
            }
        }

        /**
         * Handle Aggregation
         * check if an aggregation condition is set to default
         * 
         * @param selected_val selected column name
         * 
         * @return string
         * if it is, then return the column name directly, 
         *      otherwise return the aggregated format of column name: aggregation_name(column_name).
         */
        function addAggregation($selected_val) {
            $col_name = getColNameAndAggr($selected_val)[0];
            $aggr = $_POST[getColNameAndAggr($selected_val)[2]];
            if ($aggr == "DEFAULT") {
                return $col_name;
            }
            return $aggr . "(" . $col_name . ")";
        }

        /**
         * Handle Group by
         * 
         * @return string selected groupby condition
         * if the groupby condition is set to default, then return the string "NoGroupBy", 
         *      otherwise return the column name directly.
         */
        function getGroupbyCol() {
            $groupby_val = $_POST['groupby'];
            if ($groupby_val == "default") {
                return "NoGroupBy";
            }
            $groupby_col = getColNameAndAggr($groupby_val)[0];
            return $groupby_col;
        }

        /**
         * transform columns' names and get their aggregation operation
         * 
         * @param input
         * 
         * @return array (column name in the sql table, column name displayed on the website, aggregation set to the column)
         * 
         * if input is <input> tag values of a column or its corresponding name in the sql table
         *      , then save its corresponding name in the sql table as array[0]
         *      , displayed name for the column as array[1].
         *      , its corresponding aggregation <input> tag name as array[2].
         * Otherwise, save the 
         */
        function getColNameAndAggr($input) {
            $ans;
            if ($input == "cid" || $input == "CUSTOMERID") {
                $ans = array("CUSTOMERID", "CustomerID", "cid_aggregation");
            } else if ($input == "fname" || $input == "FIRSTNAME") {
                $ans = array("FIRSTNAME", "First Name", "fn_aggregation");
            } else if ($input == "lname" || $input == "LASTNAME") {
                $ans = array("LASTNAME", "Last Name", "ln_aggregation");
            } else if ($input == "email" || $input == "EMAIL") {
                $ans = array("EMAIL", "Email", "em_aggregation");
            } else if ($input == "phonenumber" || $input == "PHONENUMBER") {
                $ans = array("PHONENUMBER", "Phone Number", "pn_aggregation");
            } else if ($input == "expiredate" || $input == "MEMBERSHIPEXPIREDATE") {
                $ans = array("MEMBERSHIPEXPIREDATE", "Membership ExpireDate", "ed_aggregation");
            } else if ($input == "lessonremaining" || $input == "PRIVATELESSONREMAINING") {
                $ans = array("PRIVATELESSONREMAINING", "Private Lessons Remaining", "lm_aggregation");
            }
            return $ans;
        }

        /**
         * HANDLE ALL POST ROUTES
         * A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
         */
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('groupbyQueryRequest', $_POST)) {
                    handleGroupbyRequest();
                }
                disconnectFromDB();
            }
        }

        // Todo: groupby and display
        if (isset($_POST['groupbySubmit'])) {
            handlePOSTRequest();
        }

        ?>

    </body>
</html>

