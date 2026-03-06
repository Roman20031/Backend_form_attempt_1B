<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "komentar_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Chyba připojení: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $jmeno = trim($_POST["jmeno"]);
    $komentar = trim($_POST["komentar"]);

    if (!empty($jmeno) && !empty($komentar)) {

        $stmt = $conn->prepare("INSERT INTO komentare (jmeno, komentar) VALUES (?, ?)");
        $stmt->bind_param("ss", $jmeno, $komentar);
        $stmt->execute();
        $stmt->close();

        $message = "Komentář byl uložen.";
    } else {
        $message = "Vyplňte všechna pole.";
    }
}

$result = $conn->query("SELECT * FROM komentare ORDER BY datum DESC");
?>

<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<title>Komentáře</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="wrapper">

    <form method="POST" class="form-box">

        <div class="form-row">
            <label>Jméno příjmení</label>
            <input type="text" name="jmeno">
        </div>

        <div class="form-row">
            <label>Komentář</label>
            <textarea name="komentar"></textarea>
        </div>

        <button type="submit">Odeslat</button>

        <?php if($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

    </form>

    <div class="comments-section">

        <?php while($row = $result->fetch_assoc()): ?>
            <div class="comment">
                <div class="comment-name">
                    <?= htmlspecialchars($row["jmeno"]) ?>
                </div>

                <div class="comment-text">
                    <?= nl2br(htmlspecialchars($row["komentar"])) ?>
                </div>

                <div class="comment-time">
                    <?= $row["datum"] ?>
                </div>
            </div>
        <?php endwhile; ?>

    </div>

</div>

</body>
</html>

<?php $conn->close(); ?>

<!--Souerces
https://chatgpt.com/
https://chatgpt.com/c/699f6d5f-c3d8-832b-a63a-93ae12daae23
https://www.youtube.com/watch?v=lWa1Ssa7p_A


-->