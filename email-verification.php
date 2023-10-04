<?php

if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    // connect with database
    $conn = mysqli_connect("localhost:3306", "root", "", "test");

    // ตรวจสอบการเชื่อมต่อ
    if (!$conn) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
    } else {
        echo "เชื่อมต่อกับฐานข้อมูลสำเร็จ";
    }

    // Prepare the SQL statement with placeholders
    $sql = "UPDATE users SET email_verified_at = NOW() WHERE email = ? AND verification_code = ?";

    // Create a prepared statement
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ss", $email, $verification_code);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) == 0) {
                die("Verification code failed.");
            }
            echo "<p>You can login now.</p>";
        } else {
            die("Error executing the statement: " . mysqli_error($conn));
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing the statement: " . mysqli_error($conn));
    }

    // Close the connection
    mysqli_close($conn);
}


?>

<form method="POST">
    <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required>
    <input type="text" name="verification_code" placeholder="Enter verification code" required />

    <input type="submit" name="verify_email" value="Verify Email">
</form>