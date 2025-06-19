<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="style.css"> <!-- pastikan ini file CSS yang sama dengan sign up -->
</head>
<body>
    <div class="form-container">
        <form action="login.php" method="post">
            <h2>Login Admin</h2>
            <label for="username">Username</label>
            <input type="text" name="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
