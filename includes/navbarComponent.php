<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Digital Wallet</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
            <?php
                $user = $_SESSION["userLoggedIn"];
                $albumQuery = mysqli_query($con, "SELECT `credits` FROM `user_details` where `email_id` = '$user'");
                $resultarr = mysqli_fetch_assoc($albumQuery);
                echo "<a class='nav-link' href='#'>Your Points: " . $resultarr['credits'] . "</a>";
                
            ?>
            </li>
        </ul>
        <span class="navbar-text white-text mr-3">
            Hello, <?php echo $_SESSION["userLoggedIn"]; ?>
        </span>
        <a href="logout.php"><button type="button" class="btn btn-danger">Logout</button></a> 
    </div>
</nav>