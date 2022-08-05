$(document).ready(function () {
    $("#sidebarCollapse").on("click", function () {
        $("#sidebar").toggleClass("active");
    });

    // Check Active Treeview
    $("#sidebar .treeview.active").map((index, e) => {
        const buttonElement = $(e).find("a");
        if (buttonElement.length > 0) {
            // Collapse Sidebar treeview
            $(buttonElement[0].getAttribute("data-bs-target")).addClass("show");
        }
    });
});
