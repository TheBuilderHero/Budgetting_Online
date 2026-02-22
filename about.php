<!DOCTYPE html>
<html>
    <?php include 'nav.php'; ?>
</html>
<head>
    <!--get my style sheet-->
    <link rel="stylesheet" href="sharedstyle.css">
</head>

<body>
    <form action="process_contact.php" method="POST" class="form-card"> 
        <!-- Header Section -->
        <div class="result-container">
            <img src="dakota.jpeg" alt="Picture of Dakota Stephens">

            <div class="result-header">Contact Information</div>
            <div class="result-text">Software Engineer & AI Enthusiast</div>
        </div>

        <!-- Contact Details -->
        <label>Full Name</label>
        <input type="text" id="Name" name="Name" placeholder="Jane Doe" required>

        <label>Email Address</label>
        <input type="email" id="Email" name="Email" placeholder="jane.doe@example.com" required>

        <label>Phone Number</label>
        <input type="tel" id="Phone" name="Phone" placeholder="+1 (555) 000-1234">

        <!-- Action Buttons -->
        <button type="submit">Send Contact Request</button>
        <!-- Later addition buttom
        <button type="button" style="background-color: #6c757d; margin-top: 5px;">Send Message</button> 
        -->
    </form>
