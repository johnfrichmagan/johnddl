<!DOCTYPE html>
<?php
require '../db/config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('location:../index.php');
}

$id = $_SESSION['user'];
$sql = $conn->prepare("SELECT * FROM `employee` WHERE `employee_id`='$id'");
$sql->execute();
$fetch = $sql->fetch();

$innerJoinSql = "
    SELECT 
        employee.firstname, 
        employee.lastname, 
        order_details.product_id, 
        order_details.Quantity, 
        order_details.Unit_price 
    FROM 
        order_details 
    JOIN 
        employee ON order_details.employee_id = employee.employee_id";

$rightJoinSql = "
    SELECT 
        employee.employee_id, 
        employee.firstname, 
        employee.lastname, 
        employee.username, 
        employee.password, 
        order_details.Product_id, 
        order_details.Quantity, 
        order_details.Unit_price 
    FROM 
        employee 
    RIGHT JOIN 
        order_details ON employee.employee_id = order_details.employee_id";

$leftJoinSql = "
    SELECT 
        employee.employee_id, 
        employee.firstname, 
        employee.lastname, 
        employee.username, 
        employee.password, 
        order_details.Product_id, 
        order_details.Quantity, 
        order_details.Unit_price 
    FROM 
        employee 
    LEFT JOIN 
        order_details ON employee.employee_id = order_details.employee_id";

$outerJoinSql = "
    SELECT 
        employee.employee_id, 
        employee.firstname, 
        employee.lastname, 
        employee.username, 
        employee.password, 
        order_details.Product_id, 
        order_details.Quantity, 
        order_details.Unit_price 
    FROM 
        employee 
    RIGHT JOIN 
        order_details ON employee.employee_id = order_details.employee_id
    UNION
    SELECT 
        employee.employee_id, 
        employee.firstname, 
        employee.lastname, 
        employee.username, 
        employee.password, 
        order_details.Product_id, 
        order_details.Quantity, 
        order_details.Unit_price 
    FROM 
        employee 
    LEFT JOIN 
        order_details ON employee.employee_id = order_details.employee_id";
?>

<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Employee Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d0d0d;
            color: #ff99cc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        h3, h4 {
            color: #ff66b2;
            text-align: center;
        }
        .logout {
            background-color: #ff66b2;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            right: 20px;
            transition: background-color 0.3s ease;
        }
        .logout:hover {
            background-color: #ff3385;
        }
        .header {
            background: #ff66b2;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #333;
            text-align: left;
        }
        th {
            background-color: #ff66b2;
            color: #1a1a1a;
        }
        tr:nth-child(even) {
            background-color: #292929;
        }
        tr:nth-child(odd) {
            background-color: #333333;
        }
        p {
            color: #ff99cc;
            font-weight: bold;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout">Logout</a>
        
        <h3>Welcome</h3>
        <h4><?php echo htmlspecialchars($fetch['firstname'] . " " . $fetch['lastname']); ?></h4>

        <p> Joins </p>

        <!-- Inner Join Results -->
        <div>
            <p>Inner Join Query</p>
            <div class="header">Inner Join Results</div>
            <table>
                <thead>
                    <tr>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($conn->query($innerJoinSql) as $row) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['firstname']) . "</td>
                            <td>" . htmlspecialchars($row['lastname']) . "</td>
                            <td>" . htmlspecialchars($row['product_id']) . "</td>
                            <td>" . htmlspecialchars($row['Quantity']) . "</td>
                            <td>" . htmlspecialchars($row['Unit_price']) . "</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Right Join Results -->
        <div>
            <p>Right Join Query</p>
            <div class="header">Right Join Results</div>
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($conn->query($rightJoinSql) as $row) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['employee_id']) . "</td>
                            <td>" . htmlspecialchars($row['firstname']) . "</td>
                            <td>" . htmlspecialchars($row['lastname']) . "</td>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['password']) . "</td>
                            <td>" . htmlspecialchars($row['Product_id']) . "</td>
                            <td>" . htmlspecialchars($row['Quantity']) . "</td>
                            <td>" . htmlspecialchars($row['Unit_price']) . "</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Left Join Results -->
        <div>
            <p>Left Join Query</p>
            <div class="header">Left Join Results</div>
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($conn->query($leftJoinSql) as $row) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['employee_id']) . "</td>
                            <td>" . htmlspecialchars($row['firstname']) . "</td>
                            <td>" . htmlspecialchars($row['lastname']) . "</td>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['password']) . "</td>
                            <td>" . htmlspecialchars($row['Product_id']) . "</td>
                            <td>" . htmlspecialchars($row['Quantity']) . "</td>
                            <td>" . htmlspecialchars($row['Unit_price']) . "</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Outer Join Results -->
        <div>
            <p>Outer Join Query</p>
            <div class="header">Outer Join Results</div>
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($conn->query($outerJoinSql) as $row) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['employee_id']) . "</td>
                            <td>" . htmlspecialchars($row['firstname']) . "</td>
                            <td>" . htmlspecialchars($row['lastname']) . "</td>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['password']) . "</td>
                            <td>" . htmlspecialchars($row['Product_id']) . "</td>
                            <td>" . htmlspecialchars($row['Quantity']) . "</td>
                            <td>" . htmlspecialchars($row['Unit_price']) . "</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
