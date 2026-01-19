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

// Gets the all student data from JSON File 
// No params 
// returns array of students and their data
function getStdArr() {
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

    return $dataArray;
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

        $dataArray = getStdArr();

        // Append new data
        $dataArray[] = $newData;

        // Encode back to JSON and save
        $jsonData = json_encode($dataArray, JSON_PRETTY_PRINT);
        if (file_put_contents($jsonFile, $jsonData) === false) {
            throw new Exception("Unable to write to JSON file.");
        }

        echo "Data successfully added to JSON file.\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function viewStudents() {
    $dataArray = getStdArr();

    foreach ($dataArray as $index => $student) {
        echo ($index + 1) . ". " . $student["name"] 
            . ", Age: " . $student["age"] 
            . ", Student: " . ($student["isStudent"] ? "Yes" : "No") 
            . "\n";
    }
}

// returns the avg age or 0 if no data
function getAvgAge() {
    $students = getStdArr();

    $count = 0;
    $sum = 0;
    foreach($students as $student) {
        $sum += $student["age"];
        $count++;
    }
    
    if ($count === 0) {
        return 0;
    }
    return $sum / $count;
}

// returns the count of adults in the student.json file
function getAdltCount() {
    $students = getStdArr();

    $count = 0;
    foreach($students as $student) { 
        if ($student["age"] > 17) {
            $count++;
        }
    }
    return $count;
}

// function returns the count of all in student array with isStudent === true
function getStdCount() {
    $students = getStdArr();

    $count = 0;
    foreach($students as $student) { 
        if ($student["isStudent"]) $count++;
    }
    return $count;
}

function deleteStudent() {
    viewStudents();
    $studentNum = readline("which number student would you like to delete: ");

    global $jsonFile;

    $dataArray = getStdArr();

    $index = (int)$studentNum - 1;

    if (!isset($dataArray[$index])) {
        echo "Invalid student number.\n";
        return;
    }

    // remove the student entry from array
    unset($dataArray[$index]);
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

// Function starts a stats CLI loop for the user to request a certain stat or back out
function stats() {
    while (true) {
        $statAction = strtolower(readline("Type which stat [average age, count adults, count students, back]: "));
        
        if ($statAction === "back") {
        break;
        } elseif ($statAction === "average age") {
            $avgAge = getAvgAge();
            echo "Average Age: " . $avgAge . "\n";
        } elseif ($statAction === "count adults") {
            $countAdlt = getAdltCount();
            echo "Count of Adults: " . $countAdlt . "\n";
        } elseif ($statAction === "count students") {
            $countStd = getStdCount();
            echo "Count of Students: " . $countStd . "\n";
        }
    }
}

while (true) {
    $action = strtolower(readline("Type action [view, add, delete, stats, exit]: "));

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
    } elseif ($action === "stats") {
        stats();
    } else {
        echo "Invalid action. Try again.\n";
    }
}

