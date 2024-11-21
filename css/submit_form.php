<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Email configuration
    $to = "https://console.firebase.google.com/u/0/project/seventy7registration/database/seventy7registration-default-rtdb/data/~2F";
    $subject = "New Registration Form Submission";
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Collect form data
    $teamName = htmlspecialchars($_POST['teamname']);
    $coachEmail = htmlspecialchars($_POST['coachemail']);
    $coachMobile = htmlspecialchars($_POST['coachmobile']);
    $managerMobile = htmlspecialchars($_POST['managermobile']);

    // File handling function
    function uploadFile($fileKey) {
        $uploadDir = "uploads/";
        $fileName = basename($_FILES[$fileKey]["name"]);
        $targetFilePath = $uploadDir . $fileName;

        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($_FILES[$fileKey]["tmp_name"], $targetFilePath)) {
            return $targetFilePath;
        } else {
            return null;
        }
    }

    // Handle team logo
    $teamLogoPath = uploadFile("teamlogo");

    // Handle player details
    $players = [];
    for ($i = 1; $i <= 8; $i++) {
        $playerName = htmlspecialchars($_POST["player{$i}"]);
        $playerIDType = htmlspecialchars($_POST["player{$i}idtype"]);
        $playerIDPath = uploadFile("player{$i}id");
        $players[] = [
            'name' => $playerName,
            'id_type' => $playerIDType,
            'id_path' => $playerIDPath
        ];
    }

    // Coach details
    $coachName = htmlspecialchars($_POST['coachname']);
    $coachIDType = htmlspecialchars($_POST['coachidtype']);
    $coachIDPath = uploadFile('coachid');

    // Manager details
    $managerName = htmlspecialchars($_POST['managername']);
    $managerIDType = htmlspecialchars($_POST['manageridtype']);
    $managerIDPath = uploadFile('Managerid');

    // Compose email body
    $message = "<h1>New Team Registration</h1>";
    $message .= "<p><strong>Team Name:</strong> $teamName</p>";
    $message .= "<p><strong>Coach Email:</strong> $coachEmail</p>";
    $message .= "<p><strong>Coach Mobile:</strong> $coachMobile</p>";
    $message .= "<p><strong>Manager Mobile:</strong> $managerMobile</p>";
    $message .= "<h2>Players</h2>";
    foreach ($players as $player) {
        $message .= "<p><strong>Player Name:</strong> {$player['name']}</p>";
        $message .= "<p><strong>ID Type:</strong> {$player['id_type']}</p>";
        $message .= "<p><strong>ID Path:</strong> {$player['id_path']}</p>";
    }
    $message .= "<h2>Coach</h2>";
    $message .= "<p><strong>Name:</strong> $coachName</p>";
    $message .= "<p><strong>ID Type:</strong> $coachIDType</p>";
    $message .= "<p><strong>ID Path:</strong> $coachIDPath</p>";
    $message .= "<h2>Manager</h2>";
    $message .= "<p><strong>Name:</strong> $managerName</p>";
    $message .= "<p><strong>ID Type:</strong> $managerIDType</p>";
    $message .= "<p><strong>ID Path:</strong> $managerIDPath</p>";

    // Send email
    if (mail($to, $subject, $message, $headers)) {
        echo "Form submitted successfully, Team be contacted after verification and validation.";
    } else {
        echo "Failed to send email.";
    }
}
?>
