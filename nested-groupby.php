<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nested group by</title>

    <?php include("connect.php"); ?>
    <?php include("sqlExecute.php"); ?>
</head>

<body>
    <?php include('navbar.php'); ?>
    <h2>Nested Agrregation with Group By</h2>

    <p>The SQL statement below shows for each salesperson, on the day that they sell the most, how much they sold</p>

    <p>
        <code>
            SELECT S.STAFFID, S.FirstName, S.LastName, MAX(T.staffDateSum) AS maxDailySale <br>
            FROM (SELECT SUM(TotalPrice) AS staffDateSum, StaffID, OSR_DATE<br>
            FROM ORDER_SELL_RENEW<br>
            GROUP BY StaffID, OSR_DATE) T, Staff S<br>
            WHERE T.StaffID = S.StaffID<br>
            GROUP BY S.FirstName, S.LastName, S.STAFFID<br>
        </code>
    </p>

    <form action="nested-groupby.php" method="get">
        <input type="submit" value="show result" name="show">
    </form>

    <?php
    if (isset($_GET['show'])) {
        if (connectToDB()) {
            global $db_conn;
            $statement =
                "   SELECT S.STAFFID, S.FirstName, S.LastName, MAX(T.staffDateSum) AS maxDailySale
                FROM (SELECT SUM(TotalPrice) AS staffDateSum, StaffID, OSR_DATE 
                        FROM ORDER_SELL_RENEW 
                        GROUP BY StaffID, OSR_DATE) T, Staff S
                WHERE  T.StaffID = S.StaffID 
                GROUP BY S.FirstName, S.LastName, S.STAFFID
            ";
            $result = executePlainSQL($statement);
            printResult($result);
        }
        disconnectFromDB();
    }

    function printResult($result)
    {
        echo "<table>";
        echo "<tr>
            <th>Staff ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Max Daily Sale</th>
            </tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
                    <td>" . $row["STAFFID"] . "</td>
                    <td>" . $row["FIRSTNAME"] . "</td>
                    <td>" . $row["LASTNAME"] . "</td>
                    <td>" . $row["MAXDAILYSALE"] . "</td>
                </tr>";
        }
        echo "</table>";
    }
    ?>
</body>

</html>