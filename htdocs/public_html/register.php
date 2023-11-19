<html>
<style>
</style>

<body>

    <?php
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = password_hash($password, PASSWORD_DEFAULT);

        $conn = new mysqli("localhost", "root", "", "COMP307-Project");
        if ($conn->connect_error) {
            die("Internal Server Error: " . $conn->connect_error);
        }

        $sql = "INSERT INTO users (email, password) VALUES ('".$email."','".$password."')";
        $result = $conn->query($sql);
        if ($result === TRUE) {
            echo "Yes";
        }
        else{
            echo "Error: ".$sql."<br>".$conn->error;
        }
        $conn->close();
    ?>

</body>

</html>