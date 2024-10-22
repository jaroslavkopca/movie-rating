/**
 * Funkce kontrolujici udaje zadavane do RegistrationForm na registrationpage.php
 * @param event
 * @constructor
 */
function ValidateForm(event) {
    let name = document.RegistrationForm.Name.value;
    let surname = document.RegistrationForm.Surname.value;
    let nickname = document.RegistrationForm.Nickname.value;
    let password = document.RegistrationForm.Password.value;
    let passwordagain = document.RegistrationForm.Passwordagain.value;
    let email = document.RegistrationForm.Email.value;
    let nickpat = RegExp(/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{6,16}$/igm); //    6-15 char, no specialchar.
    let passpat = RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?!.*[\W_]).{6,16}$/gm); //    8-15 characters, One Uppercase, One lowercase, one number
    let namesurnamepat = RegExp(/^([a-zA-Zěščřžýáíéťňďľ]{1,20})$/gi); //  1,20 znaku, Jenom text bez special symbolu. Ceske symboly dovoleny
    let emailpat = RegExp(/\b[\w\.-]{2,30}@[\w-]{3,16}\.{1}\w{2,4}\b/gi);  //Klasicky format emailu
    let podminky = document.getElementById("Podminky").checked;  // Zaskrtnute/NEzaskrtnute policko


    if (namesurnamepat.test(name)) {
        document.getElementById("Jmeno").style.backgroundColor = "#FBE0C3";
        document.getElementsByClassName("Jmeno")[0].textContent = "";
    } else {
        if (RegExp(/^(?=.*[\W_]).{1,20}$/gm).test(name) == true) {
            document.getElementById("Jmeno").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Jmeno")[0].textContent = "Jméno nesmí obsahovat speciální znaky!";
            event.preventDefault();
        }
        if (name.length < 1) {
            document.getElementById("Jmeno").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Jmeno")[0].textContent = "Jméno je povinný údaj!";
            event.preventDefault();
        }
        if (name.length > 20) {
            document.getElementById("Jmeno").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Jmeno")[0].textContent = "Jméno delší jak 20 znaků nebereme!";
            event.preventDefault();
        }
        if (RegExp(/^(?=.*[0-9]).{1,20}$/gm).test(name) == true) {
            document.getElementById("Jmeno").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Jmeno")[0].textContent = "Jméno nesmí obsahovat číslice!";
            event.preventDefault();
        }
    }


    if (namesurnamepat.test(surname)) {
        document.getElementById("Prijmeni").style.backgroundColor = "#FBE0C3";
        document.getElementsByClassName("Prijmeni")[0].textContent = "";
    } else {
        if (RegExp(/^(?=.*[\W_]).{1,20}$/gm).test(surname) == true) {
            document.getElementById("Prijmeni").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Prijmeni")[0].textContent = "Příjmení nesmí obsahovat speciální znaky!";
            event.preventDefault();
        }
        if (surname.length < 1) {
            document.getElementById("Prijmeni").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Prijmeni")[0].textContent = "Příjmení je povinný údaj!";
            event.preventDefault();
        }
        if (surname.length > 20) {
            document.getElementById("Prijmeni").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Prijmeni")[0].textContent = "Příjmení delší jak 20 znaků nebereme!";
            event.preventDefault();
        }
        if (RegExp(/^(?=.*[0-9]).{1,20}$/gm).test(surname) == true) {
            document.getElementById("Prijmeni").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Prijmeni")[0].textContent = "Příjmení nesmí obsahovat číslice!";
            event.preventDefault();
        }
    }


    //Heslo valdiace
    if (passpat.test(password)) {
        document.getElementById("password").style.backgroundColor = "#FBE0C3";
        document.getElementsByClassName("password")[0].textContent = "";
    } else {
        if (password.length < 6) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo nesmí mít méně jak 6 znaků!";
            event.preventDefault();
        }
        if (password.length > 16) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo nesmí mít přes 16 znaků!";
            event.preventDefault();
        }
        if (RegExp(/^(?=.*[\W_]).{6,16}$/gm).test(password) == true) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo nesmí obsahovat speciální znaky!";
            event.preventDefault();
        }
        if (RegExp(/^(?=.*\d)(?=.*[a-z])(?!.*[A-Z])(?=.*[a-zA-Z]).{6,16}$/gm).test(password) == true) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo musí obsahovat alespoň jedno velké písmeno!";
            event.preventDefault();
        }
        if (RegExp(/^(?!.*\d)(?=.*[a-z])(?!.*[A-Z])(?=.*[a-zA-Z]).{6,16}$/gm).test(password) == true) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo musí obsahovat alespoň jedno velké písmeno a jednu číslici!";
            event.preventDefault();
        }
        if (RegExp(/^(?!.*\d)(?!.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{6,16}$/gm).test(password) == true) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo musí obsahovat alespoň jedno malé písmeno a jednu číslici!";
            event.preventDefault();
        }
        if (RegExp(/^(?=.*\d)(?!.*[a-z])(?!.*[A-Z])(?!.*[a-zA-Z]).{6,16}$/gm).test(password) == true) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo musí obsahovat alespoň jedno malé a jedno velké písmeno!";
            event.preventDefault();
        }
        if (RegExp(/^(?=.*\d)(?!.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?!.*[\W_]).{6,16}$/gm).test(password) == true) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo musí obsahovat alespoň jedno malé písmeno!";
            event.preventDefault();
        }
        if (RegExp(/^(?!.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?!.*[\W_]).{6,16}$/gm).test(password) == true) {
            document.getElementById("password").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("password")[0].textContent = "Heslo musí obsahovat alespoň jednu číslici!";
            event.preventDefault();
        }
    }


    if (password != passwordagain) {
        event.preventDefault();
    }


    //Validace nickname pomoci RegExp - 6 - 16 znaku. Bez specialnich znaku krom _
    if (nickpat.test(nickname)) {
        document.getElementById("Nickname").style.backgroundColor = "#FBE0C3";
        document.getElementsByClassName("Nickname")[0].textContent = "";
    } else {
        if (nickname.length < 6) {
            document.getElementById("Nickname").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Nickname")[0].textContent = "Přezdívka nesmí mít méně jak 6 znaků!";
            event.preventDefault();
        }
        if (nickname.length > 16) {
            document.getElementById("Nickname").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Nickname")[0].textContent = "Přezdívka nesmí mít přes 16 znaků!";
            event.preventDefault();
        }
        if (RegExp(/^(?=.*[\W_]).{6,16}$/gm).test(nickname) == true) {
            document.getElementById("Nickname").style.backgroundColor = "#ff706f";
            document.getElementsByClassName("Nickname")[0].textContent = "Přezdívka nesmí obsahovat speciální znaky!";
            event.preventDefault();
        }

    }


    if (emailpat.test(email)) {
        console.log()
        document.getElementById("Email").style.backgroundColor = "#FBE0C3";
        document.getElementsByClassName("Email")[0].textContent = "";
    } else {
        document.getElementById("Email").style.backgroundColor = "#ff706f";
        document.getElementsByClassName("Email")[0].textContent = "Nejedná se o korektní email. Povinný údaj.";
        event.preventDefault();
    }
    console.log(emailpat.test(email) + "sadasdasd")


    if (podminky == true) {
        document.getElementsByClassName("w3docs")[0].textContent = "";
    } else {
        document.getElementsByClassName("w3docs")[0].textContent = "Musíte souhlasit s podmínkami.";
        document.getElementsByClassName("w3docs")[0].style.color = "#ff706f";
        event.preventDefault();
    }
}


/**
 *Kontrola spravnosti hesla
 * @param event
 * @constructor
 */
function ValidatePassword(event) {
    let password = document.RegistrationForm.Password.value;
    let passwordagain = document.RegistrationForm.Passwordagain.value;
    if (passwordagain == password && password.length > 5) {
        document.getElementById("passwordagain").style.backgroundColor = "#6FFF91FF";
    }
    if (passwordagain != password && password.length > 5) {
        document.getElementById("passwordagain").style.backgroundColor = "#ff706f";
    }
    if (password.length < 5) {
        document.getElementById("passwordagain").style.backgroundColor = "#FBE0C3";
    }
}


/**
 * Bere text zadavany do searchbaru v navbaru. Tento string posila do souboru livesearch.php a vraci nalezene vysledky
 * Hledani v databazi filmu podle nazvu v realnem case
 * @param str
 * @constructor
 */
function SearchDatabase(str) {
    if (str.length == 0) {
        document.getElementById("livesearch").innerHTML = "";
        return;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("livesearch").innerHTML = this.responseText;
        }
    }
    xmlhttp.open("GET", "livesearch.php?search=" + str, true);
    xmlhttp.send();
}


/**
 * @param {string} name
 * @param {string} value
 * @param {number} days
 * Nastavuje cookie
 */
function setCookie(name, value, days, samesite) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    var samesiteString = "";
    if (samesite) {
        samesiteString = "; samesite=" + samesite;
    }
    document.cookie = name + "=" + (value || "") + expires + samesiteString + "; path=/";
}

/**
 * Meni dark/light mode. Nastavuje cookie a podle tlacitko meni style.
 * @constructor
 */
function SwitchModes() {
    var span = document.querySelector('.DarkMode span');
    if (this.checked) {
        // Darkmode
        document.documentElement.style.setProperty('--color1', '#000000');
        document.documentElement.style.setProperty('--color2', '#333333');
        document.documentElement.style.setProperty('--color3', '#666666');
        document.documentElement.style.setProperty('--color4', '#999999');
        setCookie("mode", "dark", 365, "Lax");
        span.innerHTML = "LightMode"
    } else {
        // Lightmode
        document.documentElement.style.setProperty('--color1', '#1a2238');
        document.documentElement.style.setProperty('--color2', '#9daaf2');
        document.documentElement.style.setProperty('--color3', '#ff6a3d');
        document.documentElement.style.setProperty('--color4', '#f4db7d');
        setCookie("mode", "light", 365, "Lax");
        span.innerHTML = "DarkMode"
    }
}


/**
 * Zarizuje stejny mod i po reloadu/ zmene page
 */
if (document.getElementById('switch').checked) {
    // Darkmode
    document.documentElement.style.setProperty('--color1', '#000000');
    document.documentElement.style.setProperty('--color2', '#333333');
    document.documentElement.style.setProperty('--color3', '#666666');
    document.documentElement.style.setProperty('--color4', '#999999');

} else {
    // Lightmode
    document.documentElement.style.setProperty('--color1', '#1a2238');
    document.documentElement.style.setProperty('--color2', '#9daaf2');
    document.documentElement.style.setProperty('--color3', '#ff6a3d');
    document.documentElement.style.setProperty('--color4', '#f4db7d');

}


/**
 * Meni zobraazovani dloheho/kratkeho textu pri film detailu
 */
function myFunction() {
    var shortText = document.getElementById("shorttext");
    var moreText = document.getElementById("more");
    var btnText = document.getElementById("myBtn");

    if (myBtn.value === "less") {
        btnText.innerHTML = "Zobrazit méně";
        moreText.style.display = "inline";
        shortText.style.display = "none";
        myBtn.value = 'more';
    } else {
        btnText.innerHTML = "Zobrazit více";
        moreText.style.display = "none";
        shortText.style.display = "inline";
        myBtn.value = 'less';
    }
}


/**
 * Zarizuje funkcnost slidu na mainpagu
 * Zobrazuje slide dle slideIndexu
 */
if (typeof document.getElementsByClassName("mySlides")[0] !== "undefined") {
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        if (n > x.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = x.length
        }
        ;
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        x[slideIndex - 1].style.display = "block";
    }
}


/**
 * EventListenery
 */
if (document.RegistrationForm) {
    document.RegistrationForm.addEventListener("submit", ValidateForm);
    document.RegistrationForm.addEventListener("keyup", ValidatePassword);
}

document.getElementById('switch').addEventListener('change', SwitchModes);

if (document.getElementById('review_form')) {
    const textarea = document.getElementById('review_form');
    textarea.addEventListener('input', updateCharCount);
    /**
     * Pocita znaky u zadavani do textarea
     */

    function updateCharCount() {
        const charCount = textarea.value.length;
        const charCountDisplay = document.querySelector('#charCount');
        if (charCount < 20) {
            charCountDisplay.textContent = ``;
        } else {
            if (charCount > 1000) {
                charCountDisplay.textContent = `Počet charakterů nesmí přesáhnout 1000`;
                textarea.value = textarea.value.substring(0, 1001);
                charCountDisplay.style.color = "red";
            } else {
                charCountDisplay.textContent = `Počet charakterů: ${charCount}`;
                charCountDisplay.style.color = "white";
            }
        }
    }
}












