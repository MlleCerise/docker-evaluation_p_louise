<?php
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM article");
    echo "<h1>Articles</h1><ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li><strong>{$row['title']}</strong>: {$row['body']}</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
