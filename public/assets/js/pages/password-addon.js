Array.from(document.querySelectorAll("form .auth-pass-inputgroup")).forEach(
    function (e) {
        Array.from(e.querySelectorAll(".password-addon")).forEach(function (r) {
            r.addEventListener("click", function (r) {
                var o = e.querySelector(".password-input");

                if ("password" === o.type) {
                    o.type = "text";
                    e.querySelector("i").classList.remove("ti-eye");
                    e.querySelector("i").classList.add("ti-eye-off");
                } else {
                    o.type = "password";
                    e.querySelector("i").classList.add("ti-eye");
                    e.querySelector("i").classList.remove("ti-eye-off");
                }
            });
        });
    }
);
