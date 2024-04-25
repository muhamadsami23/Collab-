<?php
session_start();
include 'assets/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("location: login.php?error=empty_fields");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("location: user/index.php");
            exit();
        } else {
            header("location: login.php?error=invalid_credentials");
            exit();
        }
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Login | Collab</title>
  </head>
  <body>
  <div class="container bg-light">
    <div class="row">
        <div class="col-md-6 col-12">
            <div id="over">
                <div class="green-label text-uppercase">Now Available</div>
                <div id="payment">
                    <div class="text-white pt-2">Project Management Platform</div>
                    <div class="text-justify text-lighter">Empower your team to collaborate, manage projects, and succeed with Collab.</div>
                </div>
                <div id="rupee">
                    <span>$</span>
                </div>
            </div>

            <div class="text-justify py-3">
                Start managing your projects efficiently and collaboratively with Collab. No coding needed.
            </div>
            <div class="d-flex align-items-center pb-4">
                <a href="#">Learn More
                    <span class="fas fa-arrow-right text-primary"></span>
                </a>
            </div>
            <div class="h6 py-2 pt-4 font-weight-bold">Unlock Your Team's Potential with Collab</div>
            <div class="text-justify pb-3">
                Collaborate seamlessly, organize tasks effectively, and achieve your project goals with Collab's intuitive platform.
            </div>
            <div class="d-flex align-items-center pb-4">
                <a href="#">Learn More
                    <span class="fas fa-arrow-right text-primary"></span>
                </a>
            </div>
        </div>

        <div class="col-md-6 col-12" id="loginForm">
            <!-- Login Form -->
            <div class="wrapper bordered bg-md-white d-flex flex-column align-items-between">
                <div class="form">
                    <div class="h4 font-weight-bold text-center mb-4">Login to Collab Dashboard</div>
                    <form action="" method="post">
    <button type="submit" class="btn btn-block rounded-0">
        <img src="https://www.freepnglogos.com/uploads/google-logo-png/google-logo-png-suite-everything-you-need-know-about-google-newest-0.png" alt="">
        <span class="px-5">Login with Google</span>
    </button>
</form>

                    <div class="border-top my-4"></div>
                    <form id="loginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="form-group">
                            <label for="email">Your email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password <span class="px-0.2"><a href="#">(forgot?)</a></span></label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary btn-block rounded-0" type="submit">Log in</button>
                            <div class="mt-2"><a href="#" onclick="showRegisterForm()">Register Now</a></div> <!-- Wrapped in a div -->
                        </div>
                    </form>
                    <div class="form-group">
                        <div class="text-muted caption">Protected by reCAPTCHA Google <a href="#">Privacy Policy</a> & <a href="#">Terms of Service</a> apply.</div>
                    </div>
                </div>
                <div class="text-center text-muted mt-auto">
                    Need help? <span><a href="#">Contact Us</a></span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12" id="registerForm" style="display: none;">
            <!-- Registration Form -->
            <div class="wrapper bordered bg-md-white d-flex flex-column align-items-between">
                <div class="form">
                    <div class="h4 font-weight-bold text-center mb-4">Register on Collab</div>
                    <form action="" method="post">
    <button type="submit" class="btn btn-block rounded-0">
        <img src="https://www.freepnglogos.com/uploads/google-logo-png/google-logo-png-suite-everything-you-need-know-about-google-newest-0.png" alt="">
        <span class="px-5">Sign-up with Google</span>
    </button>
</form>
                    </div>
                    <form id="registerForm" action="register.php" method="post">
                        <div class="border-top my-4"></div>
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        
                        <div class="form-group text-center">
                            <button class="btn btn-primary btn-block rounded-0" type="submit">Register</button>
                            <div class="mt-2"><a href="#" onclick="showLoginForm()">Login</a></div> <!-- Wrapped in a div -->
                        </div>
                    </form>
                    <div class="form-group">
                        <div class="text-muted caption">By clicking "Register", you agree to our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    function showLoginForm() {
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('registerForm').style.display = 'none';
}

function showRegisterForm() {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('registerForm').style.display = 'block';
}
</script> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const error = urlParams.get('error');

if (success === 'true') {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Registration Success. Login to Verify Your Account.',
        timer: 5000, 
        timerProgressBar: true,
        toast: true,
        position: 'top-end',
        showConfirmButton: false
    });
} else if (success === 'false' && error === 'email_exists') {
    Swal.fire({
        icon: 'error',
        title: 'Registration Failed!',
        text: 'Email already exists. Please try with a different email.',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000 
    });
} else if (success === 'false' && error === 'db_error') {
    Swal.fire({
        icon: 'error',
        title: 'Registration Failed!',
        text: 'There was an error processing your request. Please try again later.',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000 
    });
}
else if (success === 'false' && error === 'password_validation') {
    Swal.fire({
        icon: 'error',
        title: 'Registration Failed!',
        text: 'Your Passwords dont match or Password Length Should be more then 6 digits.',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000 
    });
}
else if (error === 'invalid_credentials') {
    Swal.fire({
        icon: 'error',
        title: 'Registration Failed!',
        text: 'Please Enter Correct Credientials to Login.',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
}

    </script>    
</body>
</html> 