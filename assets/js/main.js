/* ======================================
   Dantechdevs IT Consultancy
   Main JavaScript Logic
   ====================================== */

// Sidebar Toggle (For Mobile)
$(document).on("click", "#menu-toggle", function () {
    $(".sidebar").toggleClass("active");
});

// Fetch Dashboard Stats
function loadDashboardStats() {
    $.ajax({
        url: "api/fetch_dashboard_stats.php",
        method: "GET",
        dataType: "json",
        success: function (res) {
            $("#projectsCount").text(res.projects);
            $("#clientsCount").text(res.clients);
            $("#tasksCount").text(res.tasks);
            $("#ticketsCount").text(res.tickets);
        },
        error: function () {
            console.log("Failed to load dashboard stats.");
        }
    });
}

// Render Chart (Projects Overview)
function loadProjectsChart() {
    const ctx = document.getElementById("projectsChart");

    if (!ctx) return;

    new Chart(ctx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [{
                label: "Projects",
                data: [3, 7, 4, 9, 12, 15],
                borderWidth: 3,
                borderColor: "#031b4e",
                fill: false
            }]
        },
        options: {
            responsive: true,
        }
    });
}

// Page Loader
$(document).ready(function () {
    loadDashboardStats();
    loadProjectsChart();
});
