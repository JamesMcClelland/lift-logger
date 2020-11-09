<?php

include __DIR__ . '/bootstrap.php';
global $db;

if (isset($_POST['posted'])) {
    //Try the login
    $maxId = $db->query('SELECT MAX(user_id) as user_id FROM users')->fetch(PDO::FETCH_ASSOC);
    $prep = $db->prepare('INSERT INTO users (user_id, username, password) VALUES (:id, :username, :password)');
    $id = 1;
    if ($maxId) {
        $id = $maxId['user_id'] + 1;
    }
    if ($prep->execute([
        'id' => $id,
        'username' => $_POST['username'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
    ])) {
        //Logged in and success
        $_SESSION['user'] = [
            'user_id' => $id,
            'username' => $_POST['username']
        ];
        header('Location: home.php');
    } else {
        echo "<h3>Failed to create account, I was too lazy to tell you why</h3>";
    }
}

echo <<<HTML
<form action="/create.php" method="post">
    Username: <input type="text" name="username" /><br />
    Password: <input type="password" name="password" /><br />
    <input type="submit" name="posted" value="Submit" />
</form>
<a href="/">
    Home
</a>
HTML;
