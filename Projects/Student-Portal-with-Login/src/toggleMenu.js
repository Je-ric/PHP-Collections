function toggleMenu() {
    var menu = document.getElementById('menu');
    var checkBox = document.getElementById('responsive-menu');
    var menuIcon = document.querySelector('.menu-icon');

    if (checkBox.checked) {
        menuIcon.innerHTML = "&#10006;";
    } else {
        menuIcon.innerHTML = "&#9776;";
    }
}


