<!DOCTYPE html>
<html>
<style>
* {box-sizing: border-box}
body {font-family: "Lato", sans-serif;}

ul {
list-style-type: none;
margin: 0;
padding: 0;
overflow: hidden;
background-color: #4CAF50;
}

li {
float: left;
}

li a, .dropbtn {
display: inline-block;
color: white;
text-align: center;
padding: 14px 16px;
text-decoration: none;
}

li a:hover, .dropdown:hover .dropbtn {
background-color: #3e8e41;
}

li.dropdown {
display: inline-block;
}

.dropdown-content {
display: none;
position: absolute;
background-color: #f9f9f9;
min-width: 160px;
box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
z-index: 1;
}

.dropdown-content a {
color: black;
padding: 12px 16px;
text-decoration: none;
display: block;
text-align: left;
}

.dropdown-content a:hover {background-color: #f1f1f1;}

.dropdown:hover .dropdown-content {
display: block;
}

.topnav-right {
  float: right;
}


.subnav {
  float: left;
  overflow: hidden;
}

.subnav .subnavbtn {
  font-size: 16px;  
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit;
  margin: 0;
}

.navbar a:hover, .subnav:hover .subnavbtn {
  background-color: #4CAF50;
}

.subnav-content {
  display: none;
  position: absolute;
  left: 0;
  background-color: #f9f9f9;
  width: 100%;
  z-index: 1;
}

.subnav-content a {
  float: left;
    color: black;
  text-decoration: none;
}

.subnav-content a:hover {
  background-color: #f1f1f1;
  color: black;
}

.subnav:hover .subnav-content {
  display: block;
}
</style> 

<body>
    <ul>
        <li><a href="index.php">HOME</a></li>
        <li class="subnav">
            <button class="subnavbtn"> Insert <i class="fa fa-caret-down"></i></button>
            <div class="subnav-content">
                <a href="insert-table-product.php">Product</a>
                <a href="insert-table-customer.php">Customer</a>
                <a href="#">Staff</a>
                <a href="#">Order</a>
                <a href="#">Personal Trainer Certification</a>
                <a href="#">Group Fitness Lesson</a>
                <a href="#">Assessment Result</a>
            </div>
        </li>
        <li><a href="delete.php">Delete</a></li>
        <li><a href="update.php">Update</a></li>
        <li class="subnav">
            <button class="subnavbtn"> Selection <i class="fa fa-caret-down"></i></button>
            <div class="subnav-content">
                <a href="select-customer.php">Customer</a>
                <a href="select-staff.php">Staff</a>
                <a href="select-assessment.php">Assessment</a>
            </div>
        </li>
        <li><a href="projection.php">Projection</a></li>
        <li><a href="join.php">Join</a></li>
        <li><a href="v2-groupby.php">Aggregation With Group By</a></li>
        <li><a href="v2-having.php">Aggregation With Having</a></li>
        <li><a href="nested-groupby.php">Nested Aggregation With Group By</a></li>
        <li><a href="division.php">Division</a></li>

        <li class="topnav-right">
            <a href="#search">Search</a>
            <a href="#about">Login</a>
        </li>
    </ul>

</body>
</html>