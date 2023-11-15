<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Form Shilpa</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
              integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <link rel="stylesheet" href="../Css/register/register.css">
    </head>

    <body>
        <section class="vh-100 bg-image" style="background-image: url('register_background.jpg');">
            <div class="mask d-flex align-items-center h-100 ">
                <div class="container h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                            <div class="card" style="border-radius: 15px;">
                                <div class="card-body p-5">
                                    <h2 class="text-center mb-5">Create a Shilpa Account</h2>

                                    <?php
                                    if (isset($_POST["submit"])) {
                                        $fullName = $_POST["fullname"];
                                        $email = $_POST["email"];
                                        $password = $_POST["password"];
                                        $passwordRepeat = $_POST["repeat_password"];

                                        $errors = array();

                                        if (empty($fullName) or empty($email) or empty($password) or empty($passwordRepeat)) {
                                            array_push($errors, "All fields are required");
                                        }
                                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                            array_push($errors, "Email is not valid");
                                        }
                                        if (strlen($password) < 8) {
                                            array_push($errors, "Password must be at least 8 charactes long");
                                        }
                                        if ($password !== $passwordRepeat) {
                                            array_push($errors, "Password does not match");
                                        }
                                        require_once "../shilpaDatabase.php";
                                        $conn = getDbConnect();
                                        $sql = "SELECT * FROM users WHERE email = '$email'";
                                        $result = mysqli_query($conn, $sql);
                                        $rowCount = mysqli_num_rows($result);
                                        if ($rowCount > 0) {
                                            array_push($errors, "Email already exists!");
                                        }
                                        if (count($errors) > 0) {
                                            foreach ($errors as $error) {
                                                echo "<div class='alert alert-danger'>$error</div>";
                                            }
                                        } else {

                                            $sql = "INSERT INTO users (full_name, email, password) VALUES ( ?, ?, ? )";
                                            $stmt = mysqli_stmt_init($conn);
                                            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                                            if ($prepareStmt) {
                                                mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                                                mysqli_stmt_execute($stmt);
                                                echo "<div class='alert alert-success'>You are registered successfully.Now login :)</div>";
                                            } else {
                                                die("Something went wrong");
                                            }
                                        }
                                    }
                                    ?>
                                    <form action="register.php" method="post" onsubmit="return validateCheckbox()">

                                        <div class="form-outline mb-3">
                                            <input type="text" class="form-control form-control-lg" name="fullname" placeholder="Full Name" />
                                        </div>
                                        <div class="form-outline mb-3">
                                            <input type="email" class="form-control form-control-lg" name="email" placeholder="Your Email" />
                                        </div>
                                        <div class="form-outline mb-3">
                                            <input type="password" class="form-control form-control-lg" name="password"
                                                   placeholder="Password" />
                                        </div>
                                        <div class="form-outline mb-3">
                                            <input type="password" class="form-control form-control-lg" name="repeat_password"
                                                   placeholder="Repeat your password" />
                                        </div>
                                        <div class="form-check d-flex justify-content-center mb-5">
                                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3cg" />
                                            <label class="form-check-label" for="form2Example3g">
                                                I agree all statements in <a href="terms.html" class="text-body"><u>Terms of service</u></a>
                                            </label>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" value="Register" name="submit"
                                                    class="btn btn-success btn-block btn-lg">Register</button>
                                        </div>
                                        <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="login.php"
                                                                                                                class="fw-bold text-body"><u>Login here</u></a></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            function validateCheckbox() {
                var checkbox = document.getElementById("form2Example3cg");
                if (!checkbox.checked) {
                    alert("Please agree to the Terms of Service.");
                    return false;
                }
                return true;
            }
        </script>
    </body>

</html>