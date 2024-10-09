//Definição das variáveis correspondente aos elementos
var password = document.getElementById("password"),
    confirm_password = document.getElementById("password-confirmation"),
    password_equal = document.getElementById("password-equal");

var letter = document.getElementById("password-lower"),
    capital = document.getElementById("password-upper"),
    number = document.getElementById("password-number"),
    passLength = document.getElementById("password-length");
symbol = document.getElementById("password-symbol");

(password.onkeyup = function () {
    password.value.match(/[a-z]/g)
        ? (letter.classList.remove("invalid"), letter.classList.add("valid"))
        : (letter.classList.remove("valid"), letter.classList.add("invalid")),
        password.value.match(/[A-Z]/g)
            ? (capital.classList.remove("invalid"),
              capital.classList.add("valid"))
            : (capital.classList.remove("valid"),
              capital.classList.add("invalid")),
        password.value.match(/[0-9]/g)
            ? (number.classList.remove("invalid"),
              number.classList.add("valid"))
            : (number.classList.remove("valid"),
              number.classList.add("invalid")),
        password.value.match(/[!-/:-@[-`{-~]/g)
            ? (symbol.classList.remove("invalid"),
              symbol.classList.add("valid"))
            : (symbol.classList.remove("valid"),
              symbol.classList.add("invalid")),
        password.value.length >= 8
            ? (passLength.classList.remove("invalid"),
              passLength.classList.add("valid"))
            : (passLength.classList.remove("valid"),
              passLength.classList.add("invalid"));

    password_equal.classList.add("d-none");
    if (confirm_password.value != "") {
        if (password.value != confirm_password.value) {
            password_equal.classList.remove("d-none");
        }
    }
}),
    (confirm_password.onkeyup = function () {
        password_equal.classList.add("d-none");
        if (password.value != "") {
            if (password.value != confirm_password.value) {
                password_equal.classList.remove("d-none");
            }
        }
    });
