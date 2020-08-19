<nav class="navbar navbar-dark fixed-top navbar-expand-lg " style="background-color: #0D0221;">
    <a class="navbar-brand" href="index.php">Digital Wallet</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            
            <li class="nav-item " id="userCreditBalance">
            <a href="index.php" class="navbar-text white-text" style="text-decoration: none;">
            <?php
            /*
                !   Note: Only updates after page is reloaded.
            */
                $user = $_SESSION["userLoggedIn"];
                $accBal = mysqli_query($con, "SELECT `credits` FROM `user_details` where `email_id` = '$user'");
                $accBal = mysqli_fetch_assoc($accBal);
                echo "Your Points: ". $accBal['credits'] . "";
            ?>
            </a>
            
            </li>
        </ul>
        <span class="navbar-text white-text mr-3">
            <?php
                echo "Hello, ". $_SESSION["userLoggedIn"] . "";
            ?>
        </span>
        <a href="logout.php"><button type="button" class="btn btn-danger">Logout</button></a> 
    </div>
</nav>