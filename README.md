Student Management CLI (PHP)

Author: Jacob Vaughn
Language: PHP

A simple command-line PHP program to manage a list of students. Users can add, view, and delete students. Student data is persisted in a JSON file (students.json), so it remains between runs.

Features

Add new students (name, age, and whether they are currently a student)

View all students with details (name, age, student status)

Delete a student by number

Data stored persistently in students.json

Simple CLI interface with input validation

How to Run

Make sure you have PHP installed on your system

Clone or download this repository

Open your terminal in the project directory

Run the program:

php students.php


Follow the prompts:

add → Add a new student

view → View all students

delete → Delete a student

exit → Quit the program

Example Usage
Type action [view, add, delete, exit]: add
Enter New Student Name: John
Enter New Student Age: 25
Are they a student (y/n): y
Data successfully added to JSON file.

Type action [view, add, delete, exit]: view
1. John, Age: 25, Student: Yes

Type action [view, add, delete, exit]: delete
Which number student would you like to delete: 1
Student successfully deleted from JSON file.

Type action [view, add, delete, exit]: exit
Goodbye!

Skills Demonstrated

PHP basics: variables, loops, conditionals, functions

Arrays and associative arrays

JSON file read/write for data persistence

Input validation and CLI interaction

Error handling with try/catch
