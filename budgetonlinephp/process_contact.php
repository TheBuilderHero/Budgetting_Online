<?php
    include "mySQLConnect.php";
    $contactDB = new SQLConnect("budgetting_online");

    // Grab data from the POST request
    $name  = $_POST['Name'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];

    if ($contactDB->insertContact($name, $email, $phone)){
        $message = "Contact saved successfully!";
        $status_class = "success";
    } else {
        $message = "Oops! Something went wrong.";
        $status_class = "error";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
        }
        .response-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .success .icon { color: #2ecc71; }
        .error .icon { color: #e74c3c; }
        
        h2 { color: #2c3e50; margin-bottom: 10px; }
        p { color: #7f8c8d; line-height: 1.6; }
        
        .btn-back {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 30px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-back:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="response-card <?php echo $status_class; ?>">
    <div class="icon">
        <?php echo ($status_class == "success") ? "✓" : "✕"; ?>
    </div>
    <h2><?php echo ($status_class == "success") ? "Awesome!" : "Error"; ?></h2>
    <p><?php echo $message; ?></p>
    <a href="index.php" class="btn-back">Return to Form</a>
</div>

</body>
</html>