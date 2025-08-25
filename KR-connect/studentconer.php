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
            justify-content-center;
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
        .col-md-4.mb-3:hover {
            transform: scale(1.05); 
      transition: transform 0.3s ease;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">

        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <!-- Topbar -->
        <?php include 'topbar.php'; ?>


        <div class="breadcrumb-area custom-gradient">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile Information</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
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
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="od-main-tab" data-bs-toggle="tab" href="#od" role="tab"
                            aria-controls="od" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;"><i
                                    class="fas fa-file-alt tab-icon"></i>OD Apply</span>
                        </a>
                    </li>
                </ul>

                        <div class="tab-content">
                    <div class="tab-pane fade show active" id="academic" role="tabpanel" aria-labelledby="academic-main-tab">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mt-4">Academic Details Content</h5>
                                <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#academicModal">Add details</button>
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
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="academicTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-main-tab">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mt-4">Family Details Content</h5>
                                <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#familyModal">Add Details</button>
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
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="familyTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="parents" role="tabpanel" aria-labelledby="parents-main-tab">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mt-4">Parents Meeting Content</h5>
                                <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#parentsModal">Add details</button>
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
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="parentsTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="od" role="tabpanel" aria-labelledby="od-main-tab">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mt-4">OD Apply</h5>
                                <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#odModal">Apply OD</button>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="odTable">
                                        <thead class="gradient-header">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Reason</th>
                                                <th scope="col">Duration (Hours)</th>
                                                <th scope="col">Applied At</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="odTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
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
                        <form id="academicForm" method="POST">
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
                        <div id="academicLoader" style="display:none;text-align:center;">
                            <span class="spinner-border text-primary"></span> Saving...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="academicEditModal" tabindex="-1" aria-labelledby="academicEditModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="academicEditModalLabel">Edit Academic Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="academicEditForm" method="POST">
                            <input type="hidden" name="id" id="academicEditId">
                            <div class="mb-3">
                                <label for="academicEditCourse" class="form-label">Course</label>
                                <select class="form-select" id="academicEditCourse" name="course" required>
                                    <option value="SSLC">SSLC</option>
                                    <option value="HSC">HSC</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="UG">Undergraduate</option>
                                    <option value="PG">Postgraduate</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="academicEditInstitute" class="form-label">Institution Name</label>
                                <input type="text" class="form-control" id="academicEditInstitute" name="institute_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="academicEditBoard" class="form-label">Board/University</label>
                                <input type="text" class="form-control" id="academicEditBoard" name="board_university" required>
                            </div>
                            <div class="mb-3">
                                <label for="academicEditYear" class="form-label">Year of Passing</label>
                                <input type="number" class="form-control" id="academicEditYear" name="year_of_passing" required min="1900" max="2100">
                            </div>
                            <div class="mb-3">
                                <label for="academicEditPercentage" class="form-label">Percentage/CGPA</label>
                                <input type="text" class="form-control" id="academicEditPercentage" name="percentage_cgpa">
                            </div>
                            <div class="mb-3">
                                <label for="academicEditCertificate" class="form-label">Certificate (e.g., URL or Filename)</label>
                                <input type="text" class="form-control" id="academicEditCertificate" name="certificate">
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
                        <form id="familyForm" method="POST" action="#">
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

        <div class="modal fade" id="familyEditModal" tabindex="-1" aria-labelledby="familyEditModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="familyEditModalLabel">Edit Family Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="familyEditForm" method="POST">
                            <input type="hidden" name="id" id="familyEditId">
                            <div class="mb-3">
                                <label for="familyEditName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="familyEditName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="familyEditRelationship" class="form-label">Relationship</label>
                                <input type="text" class="form-control" id="familyEditRelationship" name="relationship" required>
                            </div>
                            <div class="mb-3">
                                <label for="familyEditOccupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="familyEditOccupation" name="occupation">
                            </div>
                            <div class="mb-3">
                                <label for="familyEditOrganization" class="form-label">Organization</label>
                                <input type="text" class="form-control" id="familyEditOrganization" name="organization">
                            </div>
                            <div class="mb-3">
                                <label for="familyEditMobile" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="familyEditMobile" name="mobile_number">
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
                        <form id="parentsForm" method="POST" action="#">
                            <div class="mb-3">
                                <label for="meetingDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="meetingDate" name="meeting_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="meetingPurpose" class="form-label">Purpose of Meeting</label>
                                <input type="text" class="form-control" id="meetingPurpose" name="purpose_of_meeting" required>
                            </div>
                            <div class="mb-3">
                                <label for="pointsDiscussed" class="form-label">Points Discussed</label>
                                <textarea class="form-control" id="pointsDiscussed" name="points_discussed" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="meetingAction" class="form-label">Action</label>
                                <input type="text" class="form-control" id="meetingAction" name="action">
                            </div>
                            <div class="mb-3">
                                <label for="meetingStatus" class="form-label">Status</label>
                                <select class="form-select" id="meetingStatus" name="status" required>
                                    <option selected disabled>Select Status</option>
                                    <option value="Scheduled">Scheduled</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
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

        <div class="modal fade" id="parentsEditModal" tabindex="-1" aria-labelledby="parentsEditModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="parentsEditModalLabel">Edit Parents Meeting Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="parentsEditForm" method="POST">
                            <input type="hidden" name="id" id="parentsEditId">
                            <div class="mb-3">
                                <label for="parentsEditDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="parentsEditDate" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="parentsEditPurpose" class="form-label">Purpose of Meeting</label>
                                <input type="text" class="form-control" id="parentsEditPurpose" name="purpose_of_meeting" required>
                            </div>
                            <div class="mb-3">
                                <label for="parentsEditPointsDiscussed" class="form-label">Points Discussed</label>
                                <textarea class="form-control" id="parentsEditPointsDiscussed" name="points_discussed" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="parentsEditAction" class="form-label">Action</label>
                                <input type="text" class="form-control" id="parentsEditAction" name="action">
                            </div>
                            <div class="mb-3">
                                <label for="parentsEditStatus" class="form-label">Status</label>
                                <select class="form-select" id="parentsEditStatus" name="status" required>
                                    <option value="Scheduled">Scheduled</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
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

    </div>

    <script>
        // Function to load Academic Details table
        function loadAcademicTable() {
            $.ajax({
                url: 'student_api.php',
                type: 'POST',
                data: $.param({ action: 'read', table: 'academic_details' }),
                dataType: 'json',
                success: function(response) {
                    let rows = '';
                    if (response.success && response.data.length > 0) {
                        response.data.forEach(function(item, idx) {
                            rows += `<tr>
                                <td>${idx + 1}</td>
                                <td>${item.course}</td>
                                <td>${item.institute_name}</td>
                                <td>${item.board_university}</td>
                                <td>${item.year_of_passing}</td>
                                <td>${item.percentage_cgpa}</td>
                                <td>${item.certificate}</td>
                                <td>
                                    <button class='btn btn-sm btn-warning academic-edit-btn' data-id='${item.id}' data-bs-toggle='modal' data-bs-target='#academicEditModal'>Edit</button>
                                    <button class='btn btn-sm btn-danger academic-delete-btn' data-id='${item.id}'>Delete</button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = '<tr><td colspan="8" class="text-center">No academic details found.</td></tr>';
                    }
                    if ($.fn.DataTable.isDataTable('#academicTable')) {
                        $('#academicTable').DataTable().destroy();
                    }
                    $('#academicTableBody').html(rows);
                    $('#academicTable').DataTable();
                },
                error: function(xhr) {
                    $('#academicTableBody').html('<tr><td colspan="8" class="text-center text-danger">Error loading data.</td></tr>');
                }
            });
        }

        // Function to load Family Details table
        function loadFamilyTable() {
            $.ajax({
                url: 'student_api.php',
                type: 'POST',
                data: $.param({ action: 'read', table: 'family_details' }),
                dataType: 'json',
                success: function(response) {
                    let rows = '';
                    if (response.success && response.data.length > 0) {
                        response.data.forEach(function(item, idx) {
                            rows += `<tr>
                                <td>${idx + 1}</td>
                                <td>${item.name}</td>
                                <td>${item.relationship}</td>
                                <td>${item.occupation}</td>
                                <td>${item.organization}</td>
                                <td>${item.mobile_number}</td>
                                <td>
                                    <button class='btn btn-sm btn-warning family-edit-btn' data-id='${item.id}' data-bs-toggle='modal' data-bs-target='#familyEditModal'>Edit</button>
                                    <button class='btn btn-sm btn-danger family-delete-btn' data-id='${item.id}'>Delete</button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = '<tr><td colspan="7" class="text-center">No family details found.</td></tr>';
                    }
                    if ($.fn.DataTable.isDataTable('#familyTable')) {
                        $('#familyTable').DataTable().destroy();
                    }
                    $('#familyTableBody').html(rows);
                    $('#familyTable').DataTable();
                },
                error: function(xhr) {
                    $('#familyTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading data.</td></tr>');
                }
            });
        }

        // Function to load Parents Meeting table
        function loadParentsTable() {
            $.ajax({
                url: 'student_api.php',
                type: 'POST',
                data: $.param({ action: 'read', table: 'parents_meeting' }),
                dataType: 'json',
                success: function(response) {
                    let rows = '';
                    if (response.success && response.data.length > 0) {
                        response.data.forEach(function(item, idx) {
                            rows += `<tr>
                                <td>${idx + 1}</td>
                                <td>${item.meeting_date}</td>
                                <td>${item.purpose_of_meeting}</td>
                                <td>${item.points_discussed}</td>
                                <td>${item.action}</td>
                                <td>${item.status}</td>
                                <td>
                                    <button class='btn btn-sm btn-warning parents-edit-btn' data-id='${item.id}' data-bs-toggle='modal' data-bs-target='#parentsEditModal'>Edit</button>
                                    <button class='btn btn-sm btn-danger parents-delete-btn' data-id='${item.id}'>Delete</button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = '<tr><td colspan="7" class="text-center">No parents meeting details found.</td></tr>';
                    }
                    if ($.fn.DataTable.isDataTable('#parentsTable')) {
                        $('#parentsTable').DataTable().destroy();
                    }
                    $('#parentsTableBody').html(rows);
                    $('#parentsTable').DataTable();
                },
                error: function(xhr) {
                    $('#parentsTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading data.</td></tr>');
                }
            });
        }

        // Function to load OD Apply table
        function loadODTable() {
            $.ajax({
                url: 'student_api.php',
                type: 'POST',
                data: $.param({ action: 'read', table: 'od_apply' }),
                dataType: 'json',
                success: function(response) {
                    let rows = '';
                    if (response.success && response.data.length > 0) {
                        response.data.forEach(function(item, idx) {
                            let statusBadge;
                            switch (item.status) {
                                case 'Approved':
                                    statusBadge = '<span class="badge bg-success">Approved</span>';
                                    break;
                                case 'Rejected':
                                    statusBadge = '<span class="badge bg-danger">Rejected</span>';
                                    break;
                                default:
                                    statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
                            }
                            rows += `<tr>
                                <td>${idx + 1}</td>
                                <td>${item.od_date}</td>
                                <td>${item.od_reason}</td>
                                <td>${item.od_duration}</td>
                                <td>${item.created_at}</td>
                                <td>${statusBadge}</td>
                            </tr>`;
                        });
                    } else {
                        rows = '<tr><td colspan="6" class="text-center">No OD applications found.</td></tr>';
                    }
                    if ($.fn.DataTable.isDataTable('#odTable')) {
                        $('#odTable').DataTable().destroy();
                    }
                    $('#odTableBody').html(rows);
                    $('#odTable').DataTable();
                },
                error: function(xhr) {
                    $('#odTableBody').html('<tr><td colspan="6" class="text-center text-danger">Error loading data.</td></tr>');
                }
            });
        }

        // Main document ready function
        $(document).ready(function() {
            
            // Show loader on page load
            $(window).on('load', function() {
                $('.loader-container').fadeOut('slow');
            });

            // Sidebar toggle
            $('.sidebar-toggle').on('click', function() {
                $('.sidebar').toggleClass('collapsed');
                $('.content').toggleClass('expanded');
            });

            // Mobile menu toggle
            $('.mobile-menu-toggle').on('click', function() {
                $('.sidebar').toggleClass('mobile-show');
                $('.mobile-overlay').toggleClass('show');
            });

            // Close mobile menu on overlay click
            $('.mobile-overlay').on('click', function() {
                $('.sidebar').removeClass('mobile-show');
                $(this).removeClass('show');
            });

            // Handle form submissions with AJAX
            $('#academicForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                $('#academicLoader').show();
                $.ajax({
                    url: 'student_api.php',
                    type: 'POST',
                    data: form.serialize() + '&action=create&table=academic_details',
                    dataType: 'json',
                    success: function(response) {
                        $('#academicLoader').hide();
                        if (response.success) {
                            $('#academicModal').modal('hide');
                            form[0].reset();
                            Swal.fire({ icon: 'success', title: 'Success!', text: 'Academic details saved successfully!', timer: 1500, showConfirmButton: false });
                            loadAcademicTable();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error!', text: response.message || 'Failed to save academic details.', showConfirmButton: true });
                        }
                    },
                    error: function(xhr) {
                        $('#academicLoader').hide();
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'An error occurred during submission.', showConfirmButton: true });
                        $('#academicModal').modal('hide');
                    }
                });
            });

            $('#academicEditForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'student_api.php',
                    type: 'POST',
                    data: $(this).serialize() + '&action=update&table=academic_details',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#academicEditModal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Success!', text: 'Academic details updated successfully!', timer: 1500, showConfirmButton: false });
                            loadAcademicTable();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error!', text: response.message || 'Failed to update academic details.', showConfirmButton: true });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'AJAX error occurred.', showConfirmButton: true });
                    }
                });
            });

            $('#familyForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                $.ajax({
                    url: 'student_api.php',
                    type: 'POST',
                    data: form.serialize() + '&action=create&table=family_details',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#familyModal').modal('hide');
                            form[0].reset();
                            Swal.fire({ icon: 'success', title: 'Success!', text: 'Family details saved successfully!', timer: 1500, showConfirmButton: false });
                            loadFamilyTable();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error!', text: response.message || 'Failed to save family details.', showConfirmButton: true });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'AJAX error occurred.', showConfirmButton: true });
                        $('#familyModal').modal('hide');
                    }
                });
            });

            $('#familyEditForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'student_api.php',
                    type: 'POST',
                    data: $(this).serialize() + '&action=update&table=family_details',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#familyEditModal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Success!', text: 'Family details updated successfully!', timer: 1500, showConfirmButton: false });
                            loadFamilyTable();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error!', text: response.message || 'Failed to update family details.', showConfirmButton: true });
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'An error occurred during update.', 'error');
                    }
                });
            });

            $('#parentsForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                $.ajax({
                    url: 'student_api.php',
                    type: 'POST',
                    data: form.serialize() + '&action=create&table=parents_meeting',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#parentsModal').modal('hide');
                            form[0].reset();
                            Swal.fire({ icon: 'success', title: 'Success!', text: 'Parents meeting details saved successfully!', timer: 1500, showConfirmButton: false });
                            loadParentsTable();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error!', text: response.message || 'Failed to save parents meeting details.', showConfirmButton: true });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'AJAX error occurred.', showConfirmButton: true });
                        $('#parentsModal').modal('hide');
                    }
                });
            });

            $('#parentsEditForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'student_api.php',
                    type: 'POST',
                    data: $(this).serialize() + '&action=update&table=parents_meeting',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#parentsEditModal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Success!', text: 'Parents meeting details updated successfully!', timer: 1500, showConfirmButton: false });
                            loadParentsTable();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error!', text: response.message || 'Failed to update parents meeting details.', showConfirmButton: true });
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'An error occurred during update.', 'error');
                    }
                });
            });

            $('#odForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                $('#odLoader').show();
                $.ajax({
                    url: 'student_api.php',
                    type: 'POST',
                    data: form.serialize() + '&action=create&table=od_apply',
                    dataType: 'json',
                    success: function(response) {
                        $('#odLoader').hide();
                        if (response.success) {
                            $('#odModal').modal('hide');
                            form[0].reset();
                            Swal.fire({ icon: 'success', title: 'Success!', text: 'OD application submitted!', timer: 1500, showConfirmButton: false });
                            loadODTable();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error!', text: response.message || 'Failed to submit OD application.', showConfirmButton: true });
                        }
                    },
                    error: function(xhr) {
                        $('#odLoader').hide();
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'AJAX error occurred.', showConfirmButton: true });
                        $('#odModal').modal('hide');
                    }
                });
            });

            // Edit button handlers
            $(document).on('click', '.academic-edit-btn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: 'student_api.php', type: 'POST', data: $.param({ action: 'read', table: 'academic_details' }), dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            const item = response.data.find(row => row.id == id);
                            if (item) {
                                $('#academicEditId').val(item.id);
                                $('#academicEditCourse').val(item.course);
                                $('#academicEditInstitute').val(item.institute_name);
                                $('#academicEditBoard').val(item.board_university);
                                $('#academicEditYear').val(item.year_of_passing);
                                $('#academicEditPercentage').val(item.percentage_cgpa);
                                $('#academicEditCertificate').val(item.certificate);
                            }
                        }
                    }
                });
            });

            $(document).on('click', '.family-edit-btn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: 'student_api.php', type: 'POST', data: $.param({ action: 'read', table: 'family_details' }), dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            const item = response.data.find(row => row.id == id);
                            if (item) {
                                $('#familyEditId').val(item.id);
                                $('#familyEditName').val(item.name);
                                $('#familyEditRelationship').val(item.relationship);
                                $('#familyEditOccupation').val(item.occupation);
                                $('#familyEditOrganization').val(item.organization);
                                $('#familyEditMobile').val(item.mobile_number);
                            }
                        }
                    }
                });
            });

            $(document).on('click', '.parents-edit-btn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: 'student_api.php', type: 'POST', data: $.param({ action: 'read', table: 'parents_meeting' }), dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            const item = response.data.find(row => row.id == id);
                            if (item) {
                                $('#parentsEditId').val(item.id);
                                $('#parentsEditDate').val(item.meeting_date);
                                $('#parentsEditPurpose').val(item.purpose_of_meeting);
                                $('#parentsEditPoints').val(item.points_discussed);
                                $('#parentsEditAction').val(item.action);
                                $('#parentsEditStatus').val(item.status);
                            }
                        }
                    }
                });
            });

            // Delete button handlers
            $(document).on('click', '.academic-delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({ title: 'Are you sure?', text: 'This will permanently delete the academic detail.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'student_api.php', type: 'POST', data: $.param({ id: id, action: 'delete', table: 'academic_details' }), dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Deleted!', 'Academic detail deleted.', 'success');
                                    loadAcademicTable();
                                } else {
                                    Swal.fire('Error!', response.message || 'Failed to delete.', 'error');
                                }
                            },
                            error: function(xhr) { Swal.fire('Error!', 'AJAX error occurred.', 'error'); }
                        });
                    }
                });
            });

            $(document).on('click', '.family-delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({ title: 'Are you sure?', text: 'This will permanently delete the family detail.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'student_api.php', type: 'POST', data: $.param({ id: id, action: 'delete', table: 'family_details' }), dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Deleted!', 'Family detail deleted.', 'success');
                                    loadFamilyTable();
                                } else {
                                    Swal.fire('Error!', response.message || 'Failed to delete.', 'error');
                                }
                            },
                            error: function(xhr) { Swal.fire('Error!', 'AJAX error occurred.', 'error'); }
                        });
                    }
                });
            });

            $(document).on('click', '.parents-delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({ title: 'Are you sure?', text: 'This will permanently delete the parents meeting detail.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'student_api.php', type: 'POST', data: $.param({ id: id, action: 'delete', table: 'parents_meeting' }), dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Deleted!', 'Parents meeting detail deleted.', 'success');
                                    loadParentsTable();
                                } else {
                                    Swal.fire('Error!', response.message || 'Failed to delete.', 'error');
                                }
                            },
                            error: function(xhr) { Swal.fire('Error!', 'AJAX error occurred.', 'error'); }
                        });
                    }
                });
            });

            // Tab persistence and initial data loading
            var lastTab = localStorage.getItem('lastTab');
            if (lastTab) {
                $('[href="' + lastTab + '"]').tab('show');
            } else {
                $('#academic-main-tab').tab('show');
            }
            
            loadAcademicTable();
            loadFamilyTable();
            loadParentsTable();
            loadODTable();

            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('lastTab', $(e.target).attr('href'));
                // Optional: If you want to reload the table every time you switch to it
                var targetTab = $(e.target).attr('href');
                if (targetTab === '#academic') loadAcademicTable();
                if (targetTab === '#family') loadFamilyTable();
                if (targetTab === '#parents') loadParentsTable();
                if (targetTab === '#od') loadODTable();
            });
        });
    </script>
    <!-- Footer -->
        <?php include 'footer.php'; ?>

</body>

<!-- OD Apply Modal (moved outside .container for correct Bootstrap behavior) -->
<div class="modal fade" id="odModal" tabindex="-1" aria-labelledby="odModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="odModalLabel">OD Apply Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="odForm" method="POST">
                    <div class="mb-3">
                        <label for="odDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="odDate" name="od_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="odReason" class="form-label">Reason</label>
                        <textarea class="form-control" id="odReason" name="od_reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="odDuration" class="form-label">Duration (Days)</label>
                        <input type="number" class="form-control" id="odDuration" name="od_duration" min="1" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </form>
                <div id="odLoader" style="display:none;text-align:center;">
                    <span class="spinner-border text-primary"></span> Submitting...
                </div>
            </div>
        </div>
    </div>
</div>

</html>