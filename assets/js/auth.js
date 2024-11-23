const registerButton = document.querySelector('#btnRegister')
const registerForm = document.querySelector('#register_form')
const loginButton = document.querySelector('#btnLogin')
const loginForm = document.querySelector('#login_form')

registerButton ? registerButton.addEventListener('click', (e) => {
    e.preventDefault()
    
    const formRegisterData = Object.fromEntries(new FormData(registerForm))
    formValidation(formRegisterData, registerForm.id).length === 0 ? sendData("models/auth/register.php","register_response_message", formRegisterData) : ""

}) : ''

loginButton ? loginButton.addEventListener('click', (e) => {
    e.preventDefault()
    const loginFormData = Object.fromEntries(new FormData(loginForm))
    formValidation(loginFormData, loginForm.id).length === 0 ? sendData("models/auth/login.php", "login_response_message", loginFormData) :  ""
}) : ''