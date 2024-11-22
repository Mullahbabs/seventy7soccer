<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $requiredFields = [
            'teamname', 'player1', 'player2', 'player3', 'player4',
            'player5', 'player6', 'player7', 'player8',
            'coachname', 'managername', 'coachemail', 'coachmobile', 'managermobile'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Validate file uploads
        $requiredFiles = [
            'teamlogo', 'player1id', 'player2id', 'player3id', 'player4id',
            'player5id', 'player6id', 'player7id', 'player8id', 'coachid', 'Managerid'
        ];

        foreach ($requiredFiles as $fileField) {
            if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Error uploading file: $fileField");
            }
        }

        // Save the uploaded files
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($requiredFiles as $fileField) {
            $filePath = $uploadDir . basename($_FILES[$fileField]['name']);
            if (!move_uploaded_file($_FILES[$fileField]['tmp_name'], $filePath)) {
                throw new Exception("Failed to save uploaded file: $fileField");
            }
        }

        // Save form data (for example, in a database or a file)
        // Note: Implement proper database handling and sanitization here
        $formData = [
            'teamname' => $_POST['teamname'],
            'teamlogo' => $_POST['teamlogo'],
            'coachname' => $_POST['coachname'],
            'managername' => $_POST['managername'],
            'coachemail' => $_POST['coachemail'],
            'coachmobile' => $_POST['coachmobile'],
            'managermobile' => $_POST['managermobile'],
            'player1name' => $_POST['player1name'],
            'player2name' => $_POST['player2name'],
            'player3name' => $_POST['player3name'],
            'player4name' => $_POST['player4name'],
            'player5name' => $_POST['player5name'],
            'player6name' => $_POST['player6name'],
            'player7name' => $_POST['player7name'],
            'player8name' => $_POST['player8name'],
            'player1id' => $_POST['player1id'],
            'player2id' => $_POST['player2id'],
            'player3id' => $_POST['player3id'],
            'player4id' => $_POST['player4id'],
            'player5id' => $_POST['player5id'],
            'player6id' => $_POST['player6id'],
            'player7id' => $_POST['player7id'],
            'player8id' => $_POST['player8id'],
            'coachid' => $_POST['coachid'],
            'manager1id' => $_POST['manager1id'],
            // Add other fields as needed
        ];
        
        // Example: Writing data to a JSON file
        $dataFile = 'form_data.json';
        $data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
        $data[] = $formData;
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));

        // Success response
        echo json_encode(['success' => true, 'message' => 'Form submitted successfully.']);
    } catch (Exception $e) {
        // Catch and handle any errors
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
