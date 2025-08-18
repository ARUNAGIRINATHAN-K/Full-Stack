<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIC</title>
    <link rel="icon" type="image/png" sizes="32x32" href="image/icons/mkce_s.png">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --footer-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #1a1c23;
            --light-bg: #f8f9fc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* General Styles with Enhanced Typography */

        /* Content Area Styles */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Content Navigation */
        .content-nav {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .content-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            overflow-x: auto;
        }

        .content-nav li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .content-nav li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar.collapsed+.content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .breadcrumb-area {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }



        /* Table Styles */



        .gradient-header {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            text-align: center;
            font-size: 0.9em;


        }


        td {
            text-align: left;
            font-size: 0.9em;
            vertical-align: middle;
            /* For vertical alignment */
        }






        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .topbar {
                left: 0 !important;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0 !important;
            }

            .brand-logo {
                display: block;
            }

            .user-profile {
                margin-left: 0;
            }

            .sidebar .logo {
                justify-content: center;
            }

            .sidebar .menu-item span,
            .sidebar .has-submenu::after {
                display: block !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .footer {
                left: 0 !important;
            }

            .content-nav ul {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .content-nav ul::-webkit-scrollbar {
                height: 4px;
            }

            .content-nav ul::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
        }

        .container-fluid {
            padding: 20px;
        }


        /* loader */
        .loader-container {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: var(--topbar-height);
            bottom: var(--footer-height);
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            /* Changed from 'none' to show by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed+.content .loader-container {
            left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 768px) {
            .loader-container {
                left: 0;
            }
        }

        /* Hide loader when done */
        .loader-container.hide {
            display: none;
        }

        /* Loader Animation */
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid var(--primary-color);
            border-right: 5px solid var(--success-color);
            border-bottom: 5px solid var(--primary-color);
            border-left: 5px solid var(--success-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .breadcrumb-area {
            background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }

        /* Custom modal slide-in animation */
        .modal.fade .modal-dialog {
            transform: translate(0, -50px);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translate(0, 0);
            opacity: 1;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="content" id="contentWrapper">

        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <?php include 'topbar.php'; ?>

        <div class="breadcrumb-area custom-gradient">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile Information</li>
                </ol>
            </nav>
        </div>

        <div class="container-fluid">
            <div class="custom-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="academic-main-tab" data-bs-toggle="tab" href="#academic" role="tab"
                            aria-controls="academic" aria-selected="true">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"><i
                                    class="fas fa-book tab-icon"></i>Academic Details</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="family-main-tab" data-bs-toggle="tab" href="#family" role="tab"
                            aria-controls="family" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"><i
                                    class="fas fa-users tab-icon"></i>Family Details</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="parents-main-tab" data-bs-toggle="tab" href="#parents" role="tab"
                            aria-controls="parents" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"><i
                                    class="fas fa-handshake tab-icon"></i>Parents Meeting</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="academic" role="tabpanel"
                        aria-labelledby="academic-main-tab">
                        <h5 class="mt-4">Academic Details Content</h5>
                        <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#academicModal">
                            Add details
                        </button>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="academicTable">
                                <thead class="gradient-header">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Course</th>
                                        <th scope="col">Institute Name</th>
                                        <th scope="col">Board/University</th>
                                        <th scope="col">Year of Passing</th>
                                        <th scope="col">Percentage/CGPA</th>
                                        <th scope="col">Certificate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'db_connect.php'; // Include the database connection
                                    $sql = "SELECT * FROM academic_details ORDER BY id DESC"; // Order by ID to show newest first
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $i = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<th scope='row'>" . $i++ . "</th>";
                                            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['institute_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['board_university']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['year_of_passing']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['percentage_cgpa']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['certificate']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No academic details found.</td></tr>";
                                    }
                                    $conn->close(); // Close the connection after fetching
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-main-tab">
                        <h5 class="mt-4">Family Details Content</h5>
                        <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#familyModal">
                            Add Details
                        </button>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="familyTable">
                                <thead class="gradient-header">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Relationship</th>
                                        <th scope="col">Occupation</th>
                                        <th scope="col">Organization</th>
                                        <th scope="col">Mobile number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'db_connect.php'; // Re-include as this is a separate block that might be processed independently
                                    $sql = "SELECT * FROM family_details ORDER BY id DESC";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $i = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<th scope='row'>" . $i++ . "</th>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['relationship']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['occupation']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['organization']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['mobile_number']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No family details found.</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="parents" role="tabpanel" aria-labelledby="parents-main-tab">
                        <h5 class="mt-4">Parents Meeting Content</h5>
                        <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#parentsModal">
                            Add details
                        </button>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="parentsTable">
                                <thead class="gradient-header">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Purpose of Meeting</th>
                                        <th scope="col">Points Discussed</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'db_connect.php'; // Re-include
                                    $sql = "SELECT * FROM parents_meeting ORDER BY id DESC";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $i = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<th scope='row'>" . $i++ . "</th>";
                                            echo "<td>" . htmlspecialchars($row['meeting_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['purpose_of_meeting']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['points_discussed']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['action']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No parent meeting details found.</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="academicModal" tabindex="-1" aria-labelledby="academicModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="academicModalLabel">Academic Details Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="academicForm" method="POST" action="save_academic_details.php">
                            <div class="mb-3">
                                <label for="academicCourse" class="form-label">Course</label>
                                <select class="form-select" id="academicCourse" name="course" required>
                                    <option selected disabled>Select Course</option>
                                    <option value="SSLC">SSLC</option>
                                    <option value="HSC">HSC</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="UG">Undergraduate</option>
                                    <option value="PG">Postgraduate</option>
                                    </select>
                            </div>
                            <div class="mb-3">
                                <label for="academicInstitute" class="form-label">Institution Name</label>
                                <input type="text" class="form-control" id="academicInstitute" name="institute_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="academicBoard" class="form-label">Board/University</label>
                                <input type="text" class="form-control" id="academicBoard" name="board_university" required>
                            </div>
                            <div class="mb-3">
                                <label for="academicYear" class="form-label">Year of Passing</label>
                                <input type="number" class="form-control" id="academicYear" name="year_of_passing" required min="1900" max="2100">
                            </div>
                            <div class="mb-3">
                                <label for="academicPercentage" class="form-label">Percentage/CGPA</label>
                                <input type="text" class="form-control" id="academicPercentage" name="percentage_cgpa">
                            </div>
                            <div class="mb-3">
                                <label for="academicCertificate" class="form-label">Certificate (e.g., URL or Filename)</label>
                                <input type="text" class="form-control" id="academicCertificate" name="certificate">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="familyModal" tabindex="-1" aria-labelledby="familyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="familyModalLabel">Family Details Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="familyForm" method="POST" action="save_family_details.php">
                            <div class="mb-3">
                                <label for="familyName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="familyName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="familyRelationship" class="form-label">Relationship</label>
                                <input type="text" class="form-control" id="familyRelationship" name="relationship" required>
                            </div>
                            <div class="mb-3">
                                <label for="familyOccupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="familyOccupation" name="occupation">
                            </div>
                            <div class="mb-3">
                                <label for="familyOrganization" class="form-label">Organization</label>
                                <input type="text" class="form-control" id="familyOrganization" name="organization">
                            </div>
                            <div class="mb-3">
                                <label for="familyMobile" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="familyMobile" name="mobile_number">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="parentsModal" tabindex="-1" aria-labelledby="parentsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="parentsModalLabel">Parents Meeting Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="parentsForm" method="POST" action="save_parents_meeting.php">
                            <div class="mb-3">
                                <label for="parentsDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="parentsDate" name="meeting_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="parentsPurpose" class="form-label">Purpose of Meeting</label>
                                <input type="text" class="form-control" id="parentsPurpose" name="purpose_of_meeting" required>
                            </div>
                            <div class="mb-3">
                                <label for="parentsDiscussed" class="form-label">Points Discussed</label>
                                <textarea class="form-control" id="parentsDiscussed" name="points_discussed" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="parentsAction" class="form-label">Action</label>
                                <input type="text" class="form-control" id="parentsAction" name="action">
                            </div>
                            <div class="mb-3">
                                <label for="parentsStatus" class="form-label">Status</label>
                                <input type="text" class="form-control" id="parentsStatus" name="status">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>
    </div>
    <script>
        // Loader logic (from your original code)
        const loaderContainer = document.getElementById('loaderContainer');

        function showLoader() {
            loaderContainer.classList.add('show');
        }

        function hideLoader() {
            loaderContainer.classList.remove('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const contentWrapper = document.getElementById('contentWrapper');
            let loadingTimeout;

            function hideLoaderOnLoad() { // Renamed to avoid conflict
                loaderContainer.classList.add('hide');
                contentWrapper.classList.add('show');
            }

            function showError() {
                console.error('Page load took too long or encountered an error');
                // You can add custom error handling here (e.g., show an error message on the page)
            }

            loadingTimeout = setTimeout(showError, 10000); // Set a maximum loading time

            window.onload = function() {
                clearTimeout(loadingTimeout);
                setTimeout(hideLoaderOnLoad, 500); // Add a small delay for smooth transition
            };

            window.onerror = function(msg, url, lineNo, columnNo, error) {
                clearTimeout(loadingTimeout);
                showError();
                return false;
            };

            // Toggle Sidebar
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const body = document.body;
            const mobileOverlay = document.getElementById('mobileOverlay');

            function toggleSidebar() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('mobile-show');
                    mobileOverlay.classList.toggle('show');
                    body.classList.toggle('sidebar-open');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            }
            // Ensure hamburger and mobileOverlay elements exist before adding listeners
            if (hamburger) {
                hamburger.addEventListener('click', toggleSidebar);
            }
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', toggleSidebar);
            }


            // Toggle User Menu
            const userMenu = document.getElementById('userMenu');
            // Check if userMenu exists before trying to access its children
            if (userMenu) {
                const dropdownMenu = userMenu.querySelector('.dropdown-menu');
                userMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (dropdownMenu) { // Check if dropdownMenu exists
                        dropdownMenu.classList.toggle('show');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', () => {
                    if (dropdownMenu) { // Check if dropdownMenu exists
                        dropdownMenu.classList.remove('show');
                    }
                });
            }

            // Toggle Submenu
            const menuItems = document.querySelectorAll('.has-submenu');
            menuItems.forEach(item => {
                item.addEventListener('click', () => {
                    const submenu = item.nextElementSibling;
                    item.classList.toggle('active');
                    if (submenu) { // Check if submenu exists
                        submenu.classList.toggle('active');
                    }
                });
            });

            // Handle responsive behavior
            window.addEventListener('resize', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('collapsed');
                    sidebar.classList.remove('mobile-show');
                    mobileOverlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                } else {
                    sidebar.style.transform = '';
                    mobileOverlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                }
            });

            // AJAX Form Submissions and SweetAlert Integration
            // Academic Form
            $('#academicForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const form = $(this);
                showLoader(); // Show loader on form submission

                $.ajax({
                    url: 'save_academic_details.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // The PHP script save_academic_details.php now directly includes SweetAlert JS and
                        // the Swal.fire calls. So, we'll let the response handle the alert.
                        // However, for a smooth AJAX experience, we should not reload the page
                        // and instead update the table dynamically.
                        // For this complete code, I'm sticking to a full page reload after SweetAlert
                        // as a simplified approach, similar to what your PHP scripts are doing.
                        // In a real application, you'd process the PHP response here and
                        // update the #academicTable content.
                        hideLoader(); // Hide loader after response
                        $('#academicModal').modal('hide'); // Close the modal
                        form[0].reset(); // Clear the form
                        // To update the table dynamically, you would:
                        // 1. Fetch updated data from a PHP endpoint (e.g., get_academic_data.php that returns JSON)
                        // 2. Clear existing table rows in #academicTable
                        // 3. Iterate over the fetched JSON data and append new rows.
                        location.reload(); // Simple reload for demonstration
                    },
                    error: function(xhr, status, error) {
                        hideLoader(); // Hide loader on error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred during academic details submission.',
                            showConfirmButton: true
                        });
                        $('#academicModal').modal('hide'); // Close the modal
                    }
                });
            });

            // Family Form
            $('#familyForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                showLoader();

                $.ajax({
                    url: 'save_family_details.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        hideLoader();
                        $('#familyModal').modal('hide');
                        form[0].reset();
                        location.reload(); // Simple reload for demonstration
                    },
                    error: function(xhr, status, error) {
                        hideLoader();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred during family details submission.',
                            showConfirmButton: true
                        });
                        $('#familyModal').modal('hide');
                    }
                });
            });

            // Parents Meeting Form
            $('#parentsForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                showLoader();

                $.ajax({
                    url: 'save_parents_meeting.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        hideLoader();
                        $('#parentsModal').modal('hide');
                        form[0].reset();
                        location.reload(); // Simple reload for demonstration
                    },
                    error: function(xhr, status, error) {
                        hideLoader();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred during parents meeting submission.',
                            showConfirmButton: true
                        });
                        $('#parentsModal').modal('hide');
                    }
                });
            });

            // Tab persistence using localStorage
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('lastTab', $(e.target).attr('href'));
            });

            var lastTab = localStorage.getItem('lastTab');
            if (lastTab) {
                // Ensure the tab content is loaded before trying to show the tab
                setTimeout(function() {
                    $('[href="' + lastTab + '"]').tab('show');
                }, 100); // Small delay to ensure tabs are initialized
            } else {
                // If no last tab is stored, show the first tab by default
                $('#academic-main-tab').tab('show');
            }
        });
    </script>

</body>

</html>