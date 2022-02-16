<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Select Assessment</title>
</head>

<body>
    <?php include('navbar.php'); ?>
    <h2>Assessment</h2>
    <p>
    <div>Examples</div>
    <ol>
        <li> AA_Date = 13-JAN-2021</li> <br>
        <li> CustomerID = 10004 </li><br>
    </ol>
    </p>
    <form method="POST" action="select-assessment.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="selectAssessmentRequest" name="selectAssessmentRequest">
        <?php
        $attributes = [
            'CustomerID', 'AA_Date', 'Weight', 'Height',
            'BMI', 'BodyFatPct', 'MuscleMass'
        ];
        $attributesToChoose = [
            'CustomerID', 'AA_Date'
        ];
        renderSELECT();
        renderWHERE();
        ?>
        <input type="hidden" id="selectAssessmentRequest" name="selectAssessmentRequest">
        <input type="submit" class="btn btn-primary" name="AssessmentSubmit"></p>
    </form>


    <?php include("connect.php"); ?>
    <?php include("sqlExecute.php"); ?>

    <?php
    // request handlers
    function handlePOSTRequest()
    {
        if (connectToDB()) {
            if (array_key_exists('selectAssessmentRequest', $_POST)) {
                handleSelectAssessmentRequest();
            }
            disconnectFromDB();
        }
    }

    function handleSelectAssessmentRequest()
    {
        global $db_conn;
        $selected = $_REQUEST['selected']; // checkedboxes - collumns
        $var1 = $_REQUEST['var1'];
        $input1 = $_REQUEST['input1'];
        $select = "";
        foreach ($selected as $arrtribute) {
            $select = $select . ", " . $arrtribute;
        }
        $select = substr($select, 1);

        if (is_numeric($input1)) {
            $statement = "SELECT $select FROM AssessmentResult_Assess WHERE $var1 = $input1";
        } else {
            $statement = "SELECT $select FROM AssessmentResult_Assess WHERE $var1 = '$input1'";
        }
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

        echo "<br>Retrieved data from table Assessment:<br>";
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

    if (isset($_POST['AssessmentSubmit'])) {
        handlePOSTRequest();
    }
    ?>
</body>

</html>