<?php

include __DIR__ . '/bootstrap.php';
global $db;

$exercises = json_decode(file_get_contents('exercises.json'));
$exercises = $exercises->days;

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
    echo "<h2>Not logged in pal</h2><br /><a href='/'>Home</a>";
    exit();
}

if (isset($_POST['posted'])) {
    //Posted a weight
    $prep = $db->prepare("INSERT INTO exercise (user_id, exercise_id, weight) VALUES (:user_id, :exercise_id, :weight)");
    $data = [
        'user_id' => $_SESSION['user']['user_id'],
        'exercise_id' => $_POST['exercise_id'],
        'weight' => $_POST['weight']
    ];
    $prep->execute($data);

    $exerciseInfo = explode("-", $_POST['exercise_id']);

    if (!isset($exercises[$exerciseInfo[0]]->exercises[$exerciseInfo[1]+1])) {
        echo "<h4>You have completed the exercises for today</h4><br /><a href='/'>Home</a>";
        exit();
    } else {
        header('Location: home.php?exercise_id=' . $exerciseInfo[0] . "-" . ($exerciseInfo[1]+1));
    }

}

if (!isset($_GET['day']) && !isset($_GET['exercise_id'])) {
    foreach ($exercises as $index=>$day) {
        echo "<a href='home.php?day={$index}'>Day {$index}</a><br />";
    }
} else if (!isset($_GET['exercise_id'])) {
    //Day is set though
    foreach ($exercises[$_GET['day']]->exercises as $index=>$exercise) {
        echo "<a href='home.php?exercise_id={$_GET['day']}-{$index}'>{$exercise->name}</a><br />";
    }
} else {
    $exerciseInfo = explode("-", $_GET['exercise_id']);
    $currentExercise = $exercises[$exerciseInfo[0]]->exercises[$exerciseInfo[1]];
    $prep = $db->prepare('SELECT * FROM exercise WHERE user_id = :user_id AND exercise_id = :exercise_id');
    $prep->execute([
        'user_id' => $_SESSION['user']['user_id'],
        'exercise_id' => $_GET['exercise_id']
    ]);
    $lastLift = $prep->fetch(PDO::FETCH_ASSOC);
    $weight = "N/A";
    if ($lastLift) {
        $weight = $lastLift['weight'];
    }

    echo <<<HTML
        <h4>{$currentExercise->name}</h4>
        <h5>Sets: {$currentExercise->sets}</h5>
        <h5>Reps: {$currentExercise->reps}</h5><br />
        <p>Last lift: <b>{$weight}</b></p>
        <form action="/home.php" method="post">
            <input type="hidden" name="exercise_id" value="{$_GET['exercise_id']}">
            Weight Lifted (kg): <input type="number" name="weight" /><br />
            <input type="submit" name="posted" value="Submit">
        </form>
HTML;

}
