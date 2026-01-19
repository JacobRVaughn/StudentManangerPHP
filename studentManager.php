<?php

// Goal of this mini project is to make a CLI php program that will
// Let the User manage a list of students
// author: @Jacob Vaughn


// File path for the JSON file
$jsonFile = "students.json";

// Function to get a boolean from user input
function promptBoolean(string $message): bool {
    while (true) {
        // Prompt the user
        $input = readline($message . " (y/n): ");

        if ($input === false) {
            // Handle unexpected input stream closure
            fwrite(STDERR, "Error: Unable to read input.\n");
            exit(1);
        }

        // Normalize input (trim spaces, lowercase)
        $input = strtolower(trim($input));

        // Acceptable true values
        if (in_array($input, ['y', 'yes', 'true', '1'], true)) {
            return true;
        }

        // Acceptable false values
        if (in_array($input, ['n', 'no', 'false', '0'], true)) {
            return false;
        }

        // Invalid input, re-prompt
        echo "Invalid input. Please enter 'y' or 'n'.\n";
    }
}

function addStudent($name,$age,$isStudent) {
    $newData = [
    "name" => $name,
    "age" => $age,
    "isStudent" => $isStudent,

    ];

    global $jsonFile;

    try {
        // If file doesn't exist, create an empty JSON array
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([], JSON_PRETTY_PRINT));
        }

        // Read the existing JSON file
        $jsonContent = file_get_contents($jsonFile);
        if ($jsonContent === false) {
            throw new Exception("Unable to read JSON file.");
        }

        // Decode JSON into PHP array
        $dataArray = json_decode($jsonContent, true);
        if (!is_array($dataArray)) {
            // If file is empty or invalid JSON, reset to empty array
            $dataArray = [];
        }

        // Append new data
        $dataArray[] = $newData;

        // Encode back to JSON and save
        $jsonData = json_encode($dataArray, JSON_PRETTY_PRINT);
        if (file_put_contents($jsonFile, $jsonData) === false) {
            throw new Exception("Unable to write to JSON file.");
        }

        echo "Data successfully added to JSON file.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function viewStudents() {
    global $jsonFile;

    // Read the existing JSON file
    $jsonContent = file_get_contents($jsonFile);
    if ($jsonContent === false) {
        throw new Exception("Unable to read JSON file.");
    }

    // Decode JSON into PHP array
    $dataArray = json_decode($jsonContent, true);
    if (!is_array($dataArray)) {
        // If file is empty or invalid JSON, reset to empty array
        $dataArray = [];
    }

    foreach ($dataArray as $index => $student) {
        echo ($index + 1) . ". " . $student["name"] 
            . ", Age: " . $student["age"] 
            . ", Student: " . ($student["isStudent"] ? "Yes" : "No") 
            . "\n";
    }
}

function deleteStudent() {
    viewStudents();
    $studentNum = readline("which number student would you like to delete: ");

    global $jsonFile;

    // Read the existing JSON file
    $jsonContent = file_get_contents($jsonFile);
    if ($jsonContent === false) {
        throw new Exception("Unable to read JSON file.");
    }

    $dataArray = json_decode($jsonContent, true);
    if (!is_array($dataArray)) {
        $dataArray = [];
    }

    // remove the student entry from array
    unset($dataArray[$studentNum - 1]);
    $dataArray = array_values($dataArray);

    try {
        // Encode back to JSON and save
        $jsonData = json_encode($dataArray, JSON_PRETTY_PRINT);
        if (file_put_contents($jsonFile, $jsonData) === false) {
            throw new Exception("Unable to write to JSON file.");
        }

        echo "Student successfully deleted from JSON file.\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }


}

while (true) {
    $action = strtolower(readline("Type action [view, add, delete, exit]: "));

    if ($action === "exit") {
        echo "Goodbye!\n";
        break;
    } elseif ($action === "add") {
        $name = readline("Enter New Student Name: ");
        $age = (int) readline("Enter New Student Age: ");
        $isStudent = promptBoolean("Are they a student");
        addStudent($name, $age, $isStudent);
    } elseif ($action === "view") {
        viewStudents();
    } elseif ($action === "delete") {
        deleteStudent();
    } else {
        echo "Invalid action. Try again.\n";
    }
}

