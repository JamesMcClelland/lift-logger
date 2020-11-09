<?php

include __DIR__ . '/bootstrap.php';
global $db;

if (isset($_POST['posted'])) {
    //Try the login
    $prep = $db->prepare('SELECT * FROM users WHERE username = :username');
    $prep->bindParam('username', $_POST['username ']);
    $prep->execute();
    $found = $prep->fetch(PDO::FETCH_ASSOC);
    if (!$found) {
        echo "<h2>User not found</h2>";
    } else {
        if (password_verify($_POST['password'], $found['password'])) {
            unset($found['password']);
            $_SESSION['user'] = $found;
        } else {
            echo "<h2>Incorrect password</h2>";
        }
    }
}

echo <<<HTML
<form action="/" method="post">
    Username: <input type="text" name="username" /><br />
    Password: <input type="password" name="password" /><br />
    <input type="submit" name="posted" value="Submit" />
</form>
<a href="/create.php">
    Create account
</a>
HTML;
