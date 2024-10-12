<?php 
    if(!isset($_SESSION['user'])){
        $redirection = redirectPage($_SESSION['user']);
        header("Location: $redirection");
    }
?>
<main>
    <div class="container">
        <div class="row pt-5">
            <div class="col-lg-4 mx-auto">
                <div id="register_response_message"></div>
                <form action="#" id="register_form">
                    <div class="mb-2">
                        <label for="first_name" class="mb-2">First name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control mb-2">
                        <div id="first_name_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="last_name" class="mb-2">Last name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control mb-2">
                        <div id="last_name_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="mb-2">Email</label>
                        <input type="email" name="email" id="email" class="form-control mb-2">
                        <div id="email_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="mb-2">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <div id="password_error"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-primary" type="button" id="btnRegister" type="button">Register</button>
                        <div class="d-flex justify-content-center gap-2">
                            <span>Allready have an account?</span>
                            <a href="index.php?page=login">Log in</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>