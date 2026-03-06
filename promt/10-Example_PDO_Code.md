User score update.

$pdo = new PDO($dsn,$user,$pass);

$stmt = $pdo->prepare("
UPDATE users
SET score = score + :points
WHERE telegram_id = :id
");

$stmt->execute([
    ':points' => 10,
    ':id' => $telegram_id
]);