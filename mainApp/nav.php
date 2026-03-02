<?php

session_start(); //resume session

// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
    header("location: ../index.php");
    exit;
}

?>

<style>
    .topnav {
        position: fixed;
        top: 0;
        left: 0; /* Ensures it starts at the very edge */
        width: 100%;
        z-index: 9999;
        background-color: white; /* Needed so content doesn't show behind boxes */
        box-shadow: 0 2px 10px rgba(0,0,0,0.1); /* Adds a subtle "lift" effect */
    }    

    .menucontainer {
        display: flex;
        list-style-type: none;
        margin: 0;
        padding: 10px; /* Space around the edge of the bar */
        gap: 15px;     /* Space between your "boxes" */
    }

    .menuitem {
        flex-grow: 1;
    }

    .topnav a {
        display: flex;           /* Flex makes centering text easier */
        justify-content: center; /* Horizontal center */
        align-items: center;     /* Vertical center */
        text-decoration: none;
        color: #333;
        font-family: sans-serif;
        font-weight: 500;
        
        /* The "Box" Styling */
        padding: 12px 20px;
        border: 2px solid #eee;  /* Light border */
        border-radius: 8px;      /* Rounded corners */
        transition: all 0.3s ease; /* Smooth hover transition */
    }

    /* Hover State */
    .topnav a:hover {
        background-color: #f0f7ff; /* Soft blue background */
        color: #007bff;            /* Bright blue text */
        border-color: #007bff;     /* Border matches text */
        transform: translateY(-2px); /* Tiny "pop" up effect */
    }
    .topnav a.logout-btn {
        border-color: #ffcccc; /* Soft red border */
        color: #dc3545;        /* Red text */
        font-size: 0.9em;      /* Slightly smaller */
        padding: 8px 15px;     /* More compact */
    }

    .topnav a.logout-btn:hover {
        background-color: #fff5f5;
        border-color: #dc3545;
        color: #a71d2a;
    }

</style>


<nav class="topnav">
    <ul>
        <div class="menucontainer">
            <li class="menuitem"><a href="home.php">Home</a></li>
            <li class="menuitem"><a href="budget.php">Budget Data</a></li>
            <!-- Maybe add settings back later but for now we dont need it.
            <li class="menuitem"><a href="settings.php">Settings</a></li> 
            -->
            <li class="menuitem"><a href="about.php">About</a></li>
            <li class="menuitem"><a href="logout.php" class="logout-btn">Logout</a></li>
        </div>  
    </ul>
</nav>