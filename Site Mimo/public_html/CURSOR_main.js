document.addEventListener('DOMContentLoaded', function () {
    var navbar = document.getElementById('mainNavbar');
    function onScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-shrink');
        } else {
            navbar.classList.remove('navbar-shrink');
        }
    }
    window.addEventListener('scroll', onScroll);
    onScroll(); // Run on load
});
