<html>
<style>
</style>

<body>

    <?php
        $email = $_POST['email'];
        $password = $_POST['password'];
        $conn = new mysqli("localhost", "root", "", "COMP307-Project");
        if ($conn->connect_error) {
            die("Internal Server Error: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM users WHERE email='".$email."'";
        $result = $conn->query($sql);
        if ($result->num_rows === 0) {echo "Invalid Email"; $error = "Invalid Email";}
        if ($result->num_rows > 1) {echo "Multiple Accounts tied to this Email"; $error = "Multiple Accounts tied to this Email";}
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])){
            #INSERT LOGIN LOGIC
            echo "USER LOGGED IN!";
        }
        else{echo "Invalid Password"; $error = "Invalid Password";}

        echo "<br>";
        echo "<table border='1'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $field => $value) { 
                echo "<td>" . htmlspecialchars($value) . "</td>"; 
            }
            echo "</tr>";
        }
        echo "</table>";
        $conn->close();
    ?>

</body>

</html>