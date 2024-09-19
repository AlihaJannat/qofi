function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('hidden');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.add('hidden');
}

function closeSidebarOnClick(event) {
    const sidebar = document.getElementById('sidebar');
    if (event.target === sidebar) {
        sidebar.classList.add('hidden');
    }
}
// Event listener to hide the submenu when clicking outside
document.addEventListener('click', function (event) {
    var isClickInside = document.querySelector('.group').contains(event.target);

    if (!isClickInside) {
        $('.item-submenu').addClass('hidden');
    }
});

var toggleSubMenu;
$(document).ready(function () {
    toggleSubMenu = function (submenuID) {
        // Get all item-submenu elements
        var submenus = document.querySelectorAll('.item-submenu');

        // Check if the clicked submenu is already open
        var isAlreadyOpen = document.getElementById(submenuID).classList.contains('show-submenu');

        // Remove the show-submenu class from all submenus, except the one that is already open
        submenus.forEach(function (submenu) {
            if (submenu.id !== submenuID) {
                submenu.classList.remove('show-submenu');
            }
        });

        // Toggle the show-submenu class on the clicked submenu
        if (!isAlreadyOpen) {
            document.getElementById(submenuID).classList.add('show-submenu');
        } else {
            document.getElementById(submenuID).classList.remove('show-submenu');
        }
    }
})