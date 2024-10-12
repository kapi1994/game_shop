<?php 
    if(!isset($_SESSION['user'])){
        $redirection = redirectPage($_SESSION['user']);
        header("Location: $redirection");
    }
?>
<main>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4">
                <div id="login_response_message"></div>
                <form action="#" id="login_form">
                    <div class="mb-2">
                        <label for="email" class="mb-2">Email</label>
                        <input type="text" name="email" id="email" class="form-control mb-2">
                        <div id="email_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="mb-2">Password</label>
                        <input type="password" name="password" id="password" class="form-control mb-2">
                        <div id="password_error"></div>
                    </div>
                    <div class="d-grid gap-2 ">
                        <button class="btn btn-sm btn-primary" type="button" id="btnLogin">Log in</button>
                        <div class="d-flex justify-content-center gap-2">
                            <span>Don't have an account?</span>
                            <a href="index.php?page=register">Register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>