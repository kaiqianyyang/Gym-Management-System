<!DOCTYPE html>
<html lang="en">

<body>
    <form method="POST" action="selection.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="selectCustomerRequest" name="selectCustomerRequest">
        <?php
        $attributes = [
            'CustomerID', 'FirstName', 'LastName', 'Email',
            'PhoneNumber', 'MembershipExpireDate', 'PrivateLessonRemaining' // removed ,
        ];
        renderSELECT();
        renderWHERE();
        ?>
        <input type="hidden" id="selectCustomerRequest" name="selectCustomerRequest">
        <input type="submit" class="btn btn-primary" name="customerSubmit"></p >
    </form>


    <?php include ("connect.php"); ?>
    <?php include ("sqlExecute.php"); ?>

    <?php

    // request handlers
    function handlePOSTRequest()
    {
        if (connectToDB()) {
            if (array_key_exists('selectCustomerRequest', $_POST)) {
                handleSelectCustomerRequest();
            }
            disconnectFromDB();
        }
    }

    function handleSelectCustomerRequest()
    {
        global $db_conn;
        $selected = $_REQUEST['selected']; // checkedboxes - collumns
        $var1 = $_REQUEST['var1'];
        $input1 = $_REQUEST['input1'];
        $var2 = $_REQUEST['var2'];
        $input2  = $_REQUEST['input2'];
        $select = "";
        foreach ($selected as $arrtribute) {
            $select = $select . ", " . $arrtribute;
        }
        $select = substr($select, 1);
        $statement = "SELECT $select FROM Customer WHERE $var1 = '$input1' AND $var2 = '$input2'";
        echo $statement;

        
        $result = executePlainSQL($statement);
        printResult($result);
        // echo "<br> retrieved data from table demoTable: <br>";
        // echo "<br> ID     NAME    <br>";
    }

    function printResult($result) { //prints results from a select statement

        $selected = $_REQUEST['selected']; // checkedboxes - columns
        $select = "";
        for ($i = 0; $i < count($selected); $i++) {
            $select = $select . "<th>" . $selected[$i] . "</th>";
        }

        echo "<br>Retrieved data from table Customer:<br>";
        echo "<table>";
        echo "<tr>" . $select . "</tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            $ans = getEachRow();
            var_dump($ans);
            echo "<tr>";
            for ($i = 1; $i <= count($ans); $i++) {
                echo "<td>" . $row[$ans[$i-1]] . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    }

    function getEachRow() {
        $selected = $_REQUEST['selected']; // checkedboxes - columns
        $ans = array();
        for ($i = 0; $i < count($selected); $i++) {
            array_push($ans, transformColumnName($selected[$i]));
        }
        return $ans;
    }

    function transformColumnName($input) {
        $ans;
        if ($input == "CustomerID") {
            $ans = "CUSTOMERID";
        } else if ($input == "FirstName") {
            $ans = "FIRSTNAME";
        } else if ($input == "LastName") {
            $ans = "LASTNAME";
        } else if ($input == "Email") {
            $ans = "EMAIL";
        } else if ($input == "PhoneNumber") {
            $ans = "PHONENUMBER";
        } else if ($input == "MembershipExpireDate") {
            $ans = "MEMBERSHIPEXPIREDATE";
        } else if ($input == "PrivateLessonRemaining") {
            $ans = "PRIVATELESSONREMAINING";
        }
        return $ans;
    }

    // layout function
    function renderSELECT()
    {
        echo "<div class='row'>";
        echo "<div class='col-auto' role='group'>";
        foreach ($GLOBALS['attributes'] as $arrtribute) {
            echo "
            <div class='form-check form-check-inline'>
                <input type='checkbox' name='selected[]' value='" . $arrtribute . "' class='form-check-input' id='" . $arrtribute . "' autocomplete='off'>
                <label class='form-check-label' for='" . $arrtribute . "'>" . $arrtribute . "</label>
            </div>
            ";
        }
        echo "</div>";
        echo "</div>";
    }

    function renderWHERE()
    {
        echo "<div class='row'>";
        renderDropbox("var1");
        echo "<div class='col-auto align-self-center'> <span>=</span> </div>";
        renderInput("input1");
        echo "<div class='col-auto align-self-center'> <span>AND</span> </div>";
        renderDropbox("var2");
        echo "<div class='col-auto align-self-center'> <span>=</span> </div>";
        renderInput("input2");
        echo "</div>";
    }

    function renderDropbox($boxID)
    {
        echo "<div class='col-sm-1'>";
        echo "<label class='visually-hidden' for='" . $boxID . "'>" . $boxID . "</label>";
        echo "<select class='form-select' name='" . $boxID . "' id='" . $boxID . "'>";
        echo "<option selected></option>";
        foreach ($GLOBALS['attributes'] as $arrtribute) {
            echo "<option value='" . $arrtribute . "'>" . $arrtribute . "</option>";
        }
        echo "</select>";
        echo "</div>";
    }

    function renderInput($inputID)
    {
        echo "
        <div class='col-sm-1'>
            <label class='visually-hidden' for='" . $inputID . "'>" . $inputID . "</label>
            <input type='text' name='" . $inputID . "' class='form-control' id='" . $inputID . "' placeholder='value'>
        </div>
        ";
    }

    if (isset($_POST['customerSubmit'])) {
        handlePOSTRequest();
    }
    ?>
</body>

</html>
