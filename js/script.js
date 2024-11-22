    let shownav=()=>{
        if(document.getElementById("navigation").style.display =="block")
        {
        document.getElementById("navigation").style.display ="none";
        }
        else{

        document.getElementById("navigation").style.display ="block";
        }

    }

document.addEventListener('DOMContentLoaded', function () {
    // Profile Dropdown
    const profileDropdownToggle = document.getElementById('profileDropdownToggle');
    const profileDropdown = document.getElementById('profileDropdown');

    profileDropdownToggle.addEventListener('click', function () {
        profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
        profileDropdown.style.opacity = profileDropdown.style.opacity === '1' ? '0' : '1';
        profileDropdown.style.transform = profileDropdown.style.transform === 'translateY(0)' ? 'translateY(-10px)' : 'translateY(0)';
    });
});



document.addEventListener("DOMContentLoaded", function() {
    const registerBtn = document.getElementById("registerBtn");
    const loginBtn = document.getElementById("loginBtn");
    const registerModal = document.getElementById("registerModal");
    const loginModal = document.getElementById("loginModal");
    const closeRegister = document.getElementById("closeRegister");
    const closeLogin = document.getElementById("closeLogin");
    const switchToRegister = document.getElementById("switchToRegister");
    const switchToLogin = document.getElementById("switchToLogin");
    var forgotPasswordModal = document.getElementById("forgotPasswordModal");
    var forgotPasswordLink = document.getElementById("forgotPasswordLink");
    var closeForgotPassword = document.getElementById("closeForgotPassword");

    // Function to get computed style property
    function isModalHidden(modal) {
        return window.getComputedStyle(modal).display === "none";
    }

    // Open register modal
    registerBtn.addEventListener("click", function(e) {
        e.preventDefault();
        if (isModalHidden(registerModal)) {
            loginModal.style.display = "none";
            registerModal.style.display = "block";
        } else {
            registerModal.style.display = "none";
        }
    });

    // Open login modal
    loginBtn.addEventListener("click", function(e) {
        e.preventDefault();
        registerModal.style.display = "none";
        loginModal.style.display = "block";
    });
    // Open the forgot password modal
forgotPasswordLink.onclick = function() {
    loginModal.style.display = "none";
    forgotPasswordModal.style.display = "block";
}

    // Close register modal
    closeRegister.addEventListener("click", function() {
        registerModal.style.display = "none";
    });

    // Close login modal
    closeLogin.addEventListener("click", function() {
        loginModal.style.display = "none";
    });

    closeForgotPassword.onclick = function() {
        forgotPasswordModal.style.display = "none";
    }
    // Close modal when clicking outside of the modal content
    window.addEventListener("click", function(event) {
        if (event.target == registerModal) {
            registerModal.style.display = "none";
        }
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
    });
    // Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == loginModal) {
        loginModal.style.display = "none";
    }
    if (event.target == forgotPasswordModal) {
        forgotPasswordModal.style.display = "none";
    }
}

    switchToRegister.addEventListener("click", function(e) {
        e.preventDefault();
        loginModal.style.display = "none";
        registerModal.style.display = "block";
    });

    switchToLogin.addEventListener("click", function(e) {
        e.preventDefault();
        registerModal.style.display = "none";
        loginModal.style.display = "block";
    });


});


document.addEventListener('DOMContentLoaded', function () {
    // Get the current page's URL
    const currentUrl = window.location.href;

    // Get all the navigation links
    const navLinks = document.querySelectorAll('.nav-links a');

    // Loop through each link
    navLinks.forEach(link => {
        // If the link's href matches the current URL, add the 'active' class
        if (link.href === currentUrl) {
            link.classList.add('active');
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    // When the accept button is clicked
    document.getElementById("acceptCookie").onclick = function(){
        setCookie("cookieAccepted", "true", 365); // Set cookie for 1 year
        document.getElementById("cookieConsent").style.display = "none";
    };

    // Function to set a cookie
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }
    });
