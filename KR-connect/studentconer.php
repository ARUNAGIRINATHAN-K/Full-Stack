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

    <div class="content">

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
                                    class="fas fa-book tab-icon"></i>Family Details</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="parents-main-tab" data-bs-toggle="tab" href="#parents" role="tab"
                            aria-controls="parents" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"><i
                                    class="fas fa-book tab-icon"></i>Parents Meeting</span>
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
                        <table class="table table-bordered table-striped">
                            <thead class="gradient-header">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Course</th>
                                    <th scope="col">institute Name</th>
                                    <th scope="col">Board/University</th>
                                    <th scope="col">Year of Passing</th>
                                    <th scope="col">Percentage/CGPA</th>
                                    <th scope="col">Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>10th</td>
                                    <td>TNPL</td>
                                    <td>CBSE</td>
                                    <td>2021</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>12th</td>
                                    <td>TNPL</td>
                                    <td>CBSE</td>
                                    <td>2023</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-main-tab">
                        <h5 class="mt-4">Family Details Content</h5>
                        <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#familyModal">
                            Add Details
                        </button>
                        <table class="table table-bordered table-striped">
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
                                <tr>
                                    <th scope="row">1</th>
                                    <td>fathers-name</td>
                                    <td>father</td>
                                    <td>Engineer</td>
                                    <td>Government</td>
                                    <td>123654789</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>mothers-name</td>
                                    <td>Mother</td>
                                    <td>House wife</td>
                                    <td>--</td>
                                    <td>123654789</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="parents" role="tabpanel" aria-labelledby="parents-main-tab">
                        <h5 class="mt-4">Parents Meeting Content</h5>
                        <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#parentsModal">
                            Add details
                        </button>
                        <table class="table table-bordered table-striped">
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
                                <tr>
                                    <th scope="row">1</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">1</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">1</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
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
                        <form>
                            <select class="form-select" aria-label="Default select example">
  <option selected>Course</option>
  <option value="1">SSLC</option>
  <option value="2">HSC</option>
</select>
                            <div class="mb-3">
                                <label for="academicField2" class="form-label">Institution Name</label>
                                <input type="text" class="form-control" id="academicField2">
                            </div>
                            <div class="mb-3">
                                <label for="academicField2" class="form-label">Board/University</label>
                                <input type="text" class="form-control" id="academicField2">
                            </div>
                            <div class="mb-3">
                                <label for="academicField2" class="form-label">Year of Passing</label>
                                <input type="text" class="form-control" id="academicField2">
                            </div>
                            <div class="mb-3">
                                <label for="academicField2" class="form-label">Percentage/CGPA	</label>
                                <input type="text" class="form-control" id="academicField2">
                            </div>
                            <div class="mb-3">
                                <label for="academicField3" class="form-label">Certificate</label>
                                <input type="text" class="form-control" id="academicField3">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
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
                        <form>
                            <div class="mb-3">
                                <label for="familyField1" class="form-label">Name</label>
                                <input type="text" class="form-control" id="familyField1">
                            </div>
                            <div class="mb-3">
                                <label for="familyField3" class="form-label">Relationship</label>
                                <input type="tel" class="form-control" id="familyField3">
                            </div>
                            <div class="mb-3">
                                <label for="familyField2" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="familyField2">
                            </div>
                            <div class="mb-3">
                                <label for="familyField3" class="form-label">Organization</label>
                                <input type="tel" class="form-control" id="familyField3">
                            </div>
                            <div class="mb-3">
                                <label for="familyField3" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="familyField3">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
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
                        <form>
                            <div class="mb-3">
                                <label for="parentsField1" class="form-label">Date</label>
                                <input type="date" class="form-control" id="parentsField1">
                            </div>
                            <div class="mb-3">
                                <label for="parentsField2" class="form-label">Purpose of Meeting</label>
                                <input type="text" class="form-control" id="parentsField2">
                            </div>
                            <div class="mb-3">
                                <label for="parentsField3" class="form-label">Purpose of Meeting</label>
                                <textarea class="form-control" id="parentsField3" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'footer.php'; ?>
    </div>
    <script>
        const loaderContainer = document.getElementById('loaderContainer');

        function showLoader() {
            loaderContainer.classList.add('show');
        }

        function hideLoader() {
            loaderContainer.classList.remove('show');
        }

        //    automatic loader
        document.addEventListener('DOMContentLoaded', function() {
            const loaderContainer = document.getElementById('loaderContainer');
            const contentWrapper = document.getElementById('contentWrapper');
            let loadingTimeout;

            function hideLoader() {
                loaderContainer.classList.add('hide');
                contentWrapper.classList.add('show');
            }

            function showError() {
                console.error('Page load took too long or encountered an error');
                // You can add custom error handling here
            }

            // Set a maximum loading time (10 seconds)
            loadingTimeout = setTimeout(showError, 10000);

            // Hide loader when everything is loaded
            window.onload = function() {
                clearTimeout(loadingTimeout);

                // Add a small delay to ensure smooth transition
                setTimeout(hideLoader, 500);
            };

            // Error handling
            window.onerror = function(msg, url, lineNo, columnNo, error) {
                clearTimeout(loadingTimeout);
                showError();
                return false;
            };
        });

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
        hamburger.addEventListener('click', toggleSidebar);
        mobileOverlay.addEventListener('click', toggleSidebar);
        // Toggle User Menu
        const userMenu = document.getElementById('userMenu');
        const dropdownMenu = userMenu.querySelector('.dropdown-menu');

        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            dropdownMenu.classList.remove('show');
        });

        // Toggle Submenu
        const menuItems = document.querySelectorAll('.has-submenu');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                const submenu = item.nextElementSibling;
                item.classList.toggle('active');
                submenu.classList.toggle('active');
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
    </script>

</body>

</html>