<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Select Customer</title>
</head>

<body>
    <?php include('navbar.php'); ?>
    <?php include("connect.php"); ?>
    <?php include("sqlExecute.php"); ?>

    <?php
    $title = "Select Customer";
    include('operation-name-header.php');
    ?>
    <h2>Customer</h2>
    <p>
    <div>Examples</div>
    <ol>
        <li> First Name = Amelia AND Last Name = Nelly </li> <br>
        <li> Email = Ivyddu@gmail.com AND MembershipExpireDate = 13-DEC-2021 </li><br>
        <li> First Name = Ivy AND PrivateLessonRemaining = 2 </li><br>
    </ol>
    </p>
    <form method="POST" action="select-customer.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="selectCustomerRequest" name="selectCustomerRequest">
        <?php
        $attributes = [
            'CustomerID', 'FirstName', 'LastName', 'Email',
            'PhoneNumber', 'MembershipExpireDate', 'PrivateLessonRemaining' // removed ,
        ];
        $attributesToChoose = [
            'CustomerID', 'FirstName', 'LastName', 'Email',
            'PhoneNumber' // removed ,
        ];
        renderSELECT();
        renderWHERE();
        ?>
        <input type="hidden" id="selectCustomerRequest" name="selectCustomerRequest">
        <input type="submit" class="btn btn-primary" name="customerSubmit"></p>
    </form>

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
        if (is_numeric($input1) && is_numeric($input2)) {
            $statement = "SELECT $select FROM Customer WHERE $var1 = $input1 AND $var2 = $input2";
        } else if (is_numeric($input1)) {
            $statement = "SELECT $select FROM Customer WHERE $var1 = $input1 AND $var2 = '$input2'";
        } else if (is_numeric($input2)) {
            $statement = "SELECT $select FROM Customer WHERE $var1 = '$input1' AND $var2 = $input2";
        } else {
            $statement = "SELECT $select FROM Customer WHERE $var1 = '$input1' AND $var2 = '$input2'";
        }
        echo $statement;


        $result = executePlainSQL($statement);
        printResult($result);
    }

    function printResult($result)
    { //prints results from a select statement

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
                // echo nl2br($i . "\n");
                echo "<td>" . $row[$ans[$i - 1]] . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    }

    function getEachRow()
    {
        $selected = $_REQUEST['selected']; // checkedboxes - columns
        $ans = array();
        for ($i = 0; $i < count($selected); $i++) {
            array_push($ans, transformColumnName($selected[$i]));
        }
        return $ans;
    }

    function transformColumnName($input)
    {
        return strtoupper($input);
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