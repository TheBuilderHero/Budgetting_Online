<!DOCTYPE html>
<html>
    <?php include 'nav.php'; ?>
</html>
<head>
    <!--get my style sheet-->
    <link rel="stylesheet" href="sharedstyle.css">
</head>

<body>
    <div class="form-card">
        <!-- Header Section -->
        <div class="result-container">
            <img src="dakota.jpeg" alt="Picture of Dakota Stephens">

            <div class="result-header">Contact Information</div>
            <div class="result-text">Software Engineer & AI Enthusiast</div>
        </div>

        <!-- Contact Details -->
        <label>Full Name</label>
        <input type="text" id="name" placeholder="Jane Doe" >

        <label>Email Address</label>
        <input type="email" id="email" placeholder="jane.doe@example.com" >

        <label>Phone Number</label>
        <input type="tel" id="number" placeholder="+1 (555) 000-1234" >

        <!-- Action Buttons -->
        <button type="button">Send Message</button>
        <button type="button" style="background-color: #6c757d; margin-top: 5px;">Save Contact</button>
    </div>
