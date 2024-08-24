<?php
$target_dir = "uploads/";
$original_filename = basename($_FILES["file"]["name"]);
$imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

// Generate a unique name for the file by appending a timestamp
$filename_without_extension = pathinfo($original_filename, PATHINFO_FILENAME);
$unique_filename = $filename_without_extension . "_" . time() . "." . $imageFileType;
$target_file = $target_dir . $unique_filename;

// Validate the file type and size
$check = getimagesize($_FILES["file"]["tmp_name"]);
if ($check !== false && $_FILES["file"]["size"] < 5000000) { // Check if image and size < 5MB
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo json_encode(["url" => $target_file]);
    } else {
        echo json_encode(["error" => "Sorry, there was an error uploading your file."]);
    }
} else {
    echo json_encode(["error" => "File is not an image or is too large."]);
}
?>
