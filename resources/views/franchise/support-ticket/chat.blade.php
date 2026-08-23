@extends('franchise.layouts.master')

@section('title') Support Ticket Chats @endsection



@section('content')
<style>
    .page-wrapper .content {
        padding: 30px;
        padding-bottom: 0;
    }
    .ml-38{
        margin-left: 38px;
    }
    .content-full .display-table .table-row .table-body .table-content .chat-profile-img .edit-profile-img img {
    margin: 0;
    width: 120px;
    border-radius: 50%;
    height: 120px;
    object-fit: cover;
}
    </style>
<div class="main-wrapper">
    <!-- Two Col Sidebar -->
    <div class="two-col-bar" id="two-col-bar">
        <div class="sidebar sidebar-twocol">
            <div class="sidebar-left slimscroll">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link" id="v-pills-dashboard-tab" title="Dashboard" data-bs-toggle="pill" href="#v-pills-dashboard" role="tab" aria-controls="v-pills-dashboard" aria-selected="true">
                        <span class="material-icons-outlined">
                            home
                        </span>
                    </a>
                    <a class="nav-link active" id="v-pills-apps-tab" title="Apps" data-bs-toggle="pill" href="#v-pills-apps" role="tab" aria-controls="v-pills-apps" aria-selected="false">
                        <span class="material-icons-outlined">
                            dashboard
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-employees-tab" title="Employees" data-bs-toggle="pill" href="#v-pills-employees" role="tab" aria-controls="v-pills-employees" aria-selected="false">
                        <span class="material-icons-outlined">
                            people
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-clients-tab" title="Clients" data-bs-toggle="pill" href="#v-pills-clients" role="tab" aria-controls="v-pills-clients" aria-selected="false">
                        <span class="material-icons-outlined">
                            person
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-projects-tab" title="Projects" data-bs-toggle="pill" href="#v-pills-projects" role="tab" aria-controls="v-pills-projects" aria-selected="false">
                        <span class="material-icons-outlined">
                            topic
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-leads-tab" title="Leads" data-bs-toggle="pill" href="#v-pills-leads" role="tab" aria-controls="v-pills-leads" aria-selected="false">
                        <span class="material-icons-outlined">
                            leaderboard
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-tickets-tab" title="Tickets" data-bs-toggle="pill" href="#v-pills-tickets" role="tab" aria-controls="v-pills-tickets" aria-selected="false">
                        <span class="material-icons-outlined">
                            confirmation_number
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-sales-tab" title="Sales" data-bs-toggle="pill" href="#v-pills-sales" role="tab" aria-controls="v-pills-sales" aria-selected="false">
                        <span class="material-icons-outlined">
                            shopping_bag
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-accounting-tab" title="Accounting" data-bs-toggle="pill" href="#v-pills-accounting" role="tab" aria-controls="v-pills-accounting" aria-selected="false">
                        <span class="material-icons-outlined">
                            account_balance_wallet
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-payroll-tab" title="Payroll" data-bs-toggle="pill" href="#v-pills-payroll" role="tab" aria-controls="v-pills-payroll" aria-selected="false">
                        <span class="material-icons-outlined">
                            request_quote
                        </span>
                    </a>

                    <a class="nav-link" id="v-pills-policies-tab" title="Policies" data-bs-toggle="pill" href="#v-pills-policies" role="tab" aria-controls="v-pills-policies" aria-selected="false">
                        <span class="material-icons-outlined">
                            verified_user
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-reports-tab" title="Reports" data-bs-toggle="pill" href="#v-pills-reports" role="tab" aria-controls="v-pills-reports" aria-selected="false">
                        <span class="material-icons-outlined">
                            report_gmailerrorred
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-performance-tab" title="Performance" data-bs-toggle="pill" href="#v-pills-performance" role="tab" aria-controls="v-pills-performance" aria-selected="false">
                        <span class="material-icons-outlined">
                            shutter_speed
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-goals-tab" title="Goals" data-bs-toggle="pill" href="#v-pills-goals" role="tab" aria-controls="v-pills-goals" aria-selected="false">
                        <span class="material-icons-outlined">
                            track_changes
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-training-tab" title="Training" data-bs-toggle="pill" href="#v-pills-training" role="tab" aria-controls="v-pills-training" aria-selected="false">
                        <span class="material-icons-outlined">
                            checklist_rtl
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-promotion-tab" title="Promotions" data-bs-toggle="pill" href="#v-pills-promotion" role="tab" aria-controls="v-pills-promotion" aria-selected="false">
                        <span class="material-icons-outlined">
                            auto_graph
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-resignation-tab" title="Resignation" data-bs-toggle="pill" href="#v-pills-resignation" role="tab" aria-controls="v-pills-resignation" aria-selected="false">
                        <span class="material-icons-outlined">
                            do_not_disturb_alt
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-termination-tab" title="Termination" data-bs-toggle="pill" href="#v-pills-termination" role="tab" aria-controls="v-pills-termination" aria-selected="false">
                        <span class="material-icons-outlined">
                            indeterminate_check_box
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-assets-tab" title="Assets" data-bs-toggle="pill" href="#v-pills-assets" role="tab" aria-controls="v-pills-assets" aria-selected="false">
                        <span class="material-icons-outlined">
                            web_asset
                        </span>
                    </a>
                    <a class="nav-link " id="v-pills-jobs-tab" title="Jobs" data-bs-toggle="pill" href="#v-pills-jobs" role="tab" aria-controls="v-pills-jobs" aria-selected="false">
                        <span class="material-icons-outlined">
                            work_outline
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-knowledgebase-tab" title="Knowledgebase" data-bs-toggle="pill" href="#v-pills-knowledgebase" role="tab" aria-controls="v-pills-knowledgebase" aria-selected="false">
                        <span class="material-icons-outlined">
                            school
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-activities-tab" title="Activities" data-bs-toggle="pill" href="#v-pills-activities" role="tab" aria-controls="v-pills-activities" aria-selected="false">
                        <span class="material-icons-outlined">
                            toggle_off
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-users-tab" title="Users" data-bs-toggle="pill" href="#v-pills-users" role="tab" aria-controls="v-pills-users" aria-selected="false">
                        <span class="material-icons-outlined">
                            group_add
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-settings-tab" title="Settings" data-bs-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                        <span class="material-icons-outlined">
                            settings
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-profile-tab" title="Profile" data-bs-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                        <span class="material-icons-outlined">
                            manage_accounts
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-authentication-tab" title="Authentication" data-bs-toggle="pill" href="#v-pills-authentication" role="tab" aria-controls="v-pills-authentication" aria-selected="false">
                        <span class="material-icons-outlined">
                            perm_contact_calendar
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-errorpages-tab" title="Error Pages" data-bs-toggle="pill" href="#v-pills-errorpages" role="tab" aria-controls="v-pills-errorpages" aria-selected="false">
                        <span class="material-icons-outlined">
                            announcement
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-subscriptions-tab" title="Subscriptions" data-bs-toggle="pill" href="#v-pills-subscriptions" role="tab" aria-controls="v-pills-subscriptions" aria-selected="false">
                        <span class="material-icons-outlined">
                            loyalty
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-pages-tab" title="Pages" data-bs-toggle="pill" href="#v-pills-pages" role="tab" aria-controls="v-pills-pages" aria-selected="false">
                        <span class="material-icons-outlined">
                            layers
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-forms-tab" title="Forms" data-bs-toggle="pill" href="#v-pills-forms" role="tab" aria-controls="v-pills-forms" aria-selected="false">
                        <span class="material-icons-outlined">
                            view_day
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-tables-tab" title="Tables" data-bs-toggle="pill" href="#v-pills-tables" role="tab" aria-controls="v-pills-tables" aria-selected="false">
                        <span class="material-icons-outlined">
                            table_rows
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-documentation-tab" title="Documentation" data-bs-toggle="pill" href="#v-pills-documentation" role="tab" aria-controls="v-pills-documentation" aria-selected="false">
                        <span class="material-icons-outlined">
                            description
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-changelog-tab" title="Changelog" data-bs-toggle="pill" href="#v-pills-changelog" role="tab" aria-controls="v-pills-changelog" aria-selected="false">
                        <span class="material-icons-outlined">
                            sync_alt
                        </span>
                    </a>
                    <a class="nav-link" id="v-pills-multilevel-tab" title="Multilevel" data-bs-toggle="pill" href="#v-pills-multilevel" role="tab" aria-controls="v-pills-multilevel" aria-selected="false">
                        <span class="material-icons-outlined">
                            library_add_check
                        </span>
                    </a>
                </div>
            </div>

            <div class="sidebar-right">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                        <p>Dashboard</p>
                        <ul>
                            <li>
                                <a href="admin-dashboard.html">Admin Dashboard</a>
                            </li>
                            <li>
                                <a href="employee-dashboard.html">Employee Dashboard</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade show active" id="v-pills-apps" role="tabpanel" aria-labelledby="v-pills-apps-tab">
                        <p>App</p>
                        <ul>
                            <li>
                                <a href="chat.html" class="active">Chat</a>
                            </li>
                            <li class="sub-menu">
                                <a href="#">Calls <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="voice-call.html">Voice Call</a></li>
                                    <li><a href="video-call.html">Video Call</a></li>
                                    <li><a href="outgoing-call.html">Outgoing Call</a></li>
                                    <li><a href="incoming-call.html">Incoming Call</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="calender.html">Calendar</a>
                            </li>
                            <li>
                                <a href="contacts.html">Contacts</a>
                            </li>
                            <li>
                                <a href="inbox.html">Email</a>
                            </li>
                            <li>
                                <a href="file-manager.html">File Manager</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-employees" role="tabpanel" aria-labelledby="v-pills-employees-tab">
                        <p>Employees</p>
                        <ul>
                            <li><a href="employees.html">All Employees</a></li>
                            <li><a href="holidays.html">Holidays</a></li>
                            <li><a href="leaves.html">Leaves (Admin) <span class="badge rounded-pill bg-primary float-end">1</span></a></li>
                            <li><a href="leaves-employee.html">Leaves (Employee)</a></li>
                            <li><a href="leave-settings.html">Leave Settings</a></li>
                            <li><a href="attendance.html">Attendance (Admin)</a></li>
                            <li><a href="attendance-employee.html">Attendance (Employee)</a></li>
                            <li><a href="departments.html">Departments</a></li>
                            <li><a href="designations.html">Designations</a></li>
                            <li><a href="timesheet.html">Timesheet</a></li>
                            <li><a href="shift-scheduling.html">Shift & Schedule</a></li>
                            <li><a href="overtime.html">Overtime</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-clients" role="tabpanel" aria-labelledby="v-pills-clients-tab">
                        <p>Clients</p>
                        <ul>
                            <li><a href="clients.html">Clients</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-projects" role="tabpanel" aria-labelledby="v-pills-projects-tab">
                        <p>Projects</p>
                        <ul>
                            <li><a href="projects.html">Projects</a></li>
                            <li><a href="tasks.html">Tasks</a></li>
                            <li><a href="task-board.html">Task Board</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-leads" role="tabpanel" aria-labelledby="v-pills-leads-tab">
                        <p>Leads</p>
                        <ul>
                            <li><a href="leads.html">Leads</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-tickets" role="tabpanel" aria-labelledby="v-pills-tickets-tab">
                        <p>Tickets</p>
                        <ul>
                            <li><a href="tickets.html">Tickets</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-sales" role="tabpanel" aria-labelledby="v-pills-sales-tab">
                        <p>Sales</p>
                        <ul>
                            <li><a href="estimates.html">Estimates</a></li>
                            <li><a href="invoices.html">Invoices</a></li>
                            <li><a href="payments.html">Payments</a></li>
                            <li><a href="expenses.html">Expenses</a></li>
                            <li><a href="provident-fund.html">Provident Fund</a></li>
                            <li><a href="taxes.html">Taxes</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-accounting" role="tabpanel" aria-labelledby="v-pills-accounting-tab">
                        <p>Accounting</p>
                        <ul>
                            <li><a href="categories.html">Categories</a></li>
                            <li><a href="budgets.html">Budgets</a></li>
                            <li><a href="budget-expenses.html">Budget Expenses</a></li>
                            <li><a href="budget-revenues.html">Budget Revenues</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-payroll" role="tabpanel" aria-labelledby="v-pills-payroll-tab">
                        <p>Payroll</p>
                        <ul>
                            <li><a href="salary.html"> Employee Salary </a></li>
                            <li><a href="salary-view.html"> Payslip </a></li>
                            <li><a href="payroll-items.html"> Payroll Items </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-policies" role="tabpanel" aria-labelledby="v-pills-policies-tab">
                        <p>Policies</p>
                        <ul>
                            <li><a href="policies.html"> Policies </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-reports" role="tabpanel" aria-labelledby="v-pills-reports-tab">
                        <p>Reports</p>
                        <ul>
                            <li><a href="expense-reports.html"> Expense Report </a></li>
                            <li><a href="invoice-reports.html"> Invoice Report </a></li>
                            <li><a href="payments-reports.html"> Payments Report </a></li>
                            <li><a href="project-reports.html"> Project Report </a></li>
                            <li><a href="task-reports.html"> Task Report </a></li>
                            <li><a href="user-reports.html"> User Report </a></li>
                            <li><a href="employee-reports.html"> Employee Report </a></li>
                            <li><a href="payslip-reports.html"> Payslip Report </a></li>
                            <li><a href="attendance-reports.html"> Attendance Report </a></li>
                            <li><a href="leave-reports.html"> Leave Report </a></li>
                            <li><a href="daily-reports.html"> Daily Report </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-performance" role="tabpanel" aria-labelledby="v-pills-performance-tab">
                        <p>Performance</p>
                        <ul>
                            <li><a href="performance-indicator.html"> Performance Indicator </a></li>
                            <li><a href="performance.html"> Performance Review </a></li>
                            <li><a href="performance-appraisal.html"> Performance Appraisal </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-goals" role="tabpanel" aria-labelledby="v-pills-goals-tab">
                        <p>Goals</p>
                        <ul>
                            <li><a href="goal-tracking.html"> Goal List </a></li>
                            <li><a href="goal-type.html"> Goal Type </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-training" role="tabpanel" aria-labelledby="v-pills-training-tab">
                        <p>Training</p>
                        <ul>
                            <li><a href="training.html"> Training List </a></li>
                            <li><a href="trainers.html"> Trainers</a></li>
                            <li><a href="training-type.html"> Training Type </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-promotion" role="tabpanel" aria-labelledby="v-pills-promotion-tab">
                        <p>Promotion</p>
                        <ul>
                            <li><a href="promotion.html"> Promotion </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-resignation" role="tabpanel" aria-labelledby="v-pills-resignation-tab">
                        <p>Resignation</p>
                        <ul>
                            <li><a href="resignation.html"> Resignation </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-termination" role="tabpanel" aria-labelledby="v-pills-termination-tab">
                        <p>Termination</p>
                        <ul>
                            <li><a href="termination.html"> Termination </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-assets" role="tabpanel" aria-labelledby="v-pills-assets-tab">
                        <p>Assets</p>
                        <ul>
                            <li><a href="assets.html"> Assets </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade " id="v-pills-jobs" role="tabpanel" aria-labelledby="v-pills-jobs-tab">
                        <p>Jobs</p>
                        <ul>
                            <li><a href="user-dashboard.html" class="active"> User Dasboard </a></li>
                            <li><a href="jobs-dashboard.html"> Jobs Dasboard </a></li>
                            <li><a href="jobs.html"> Manage Jobs </a></li>
                            <li><a href="job-applicants.html"> Applied Jobs </a></li>
                            <li><a href="manage-resumes.html"> Manage Resumes </a></li>
                            <li><a href="shortlist-candidates.html"> Shortlist Candidates </a></li>
                            <li><a href="interview-questions.html"> Interview Questions </a></li>
                            <li><a href="offer_approvals.html"> Offer Approvals </a></li>
                            <li><a href="experiance-level.html"> Experience Level </a></li>
                            <li><a href="candidates.html"> Candidates List </a></li>
                            <li><a href="schedule-timing.html"> Schedule timing </a></li>
                            <li><a href="apptitude-result.html"> Aptitude Results </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-knowledgebase" role="tabpanel" aria-labelledby="v-pills-knowledgebase-tab">
                        <p>Knowledgebase</p>
                        <ul>
                            <li><a href="knowledgebase.html"> Knowledgebase </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-activities" role="tabpanel" aria-labelledby="v-pills-activities-tab">
                        <p>Activities</p>
                        <ul>
                            <li><a href="activities.html"> Activities </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-activities-tab">
                        <p>Users</p>
                        <ul>
                            <li><a href="users.html"> Users </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <p>Settings</p>
                        <ul>
                            <li><a href="settings.html"> Settings </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <p>Profile</p>
                        <ul>
                            <li><a href="profile.html"> Employee Profile </a></li>
                            <li><a href="client-profile.html"> Client Profile </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-authentication" role="tabpanel" aria-labelledby="v-pills-authentication-tab">
                        <p>Authentication</p>
                        <ul>
                            <li><a href="index.html"> Login </a></li>
                            <li><a href="register.html"> Register </a></li>
                            <li><a href="forgot-password.html"> Forgot Password </a></li>
                            <li><a href="otp.html"> OTP </a></li>
                            <li><a href="lock-screen.html"> Lock Screen </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-errorpages" role="tabpanel" aria-labelledby="v-pills-errorpages-tab">
                        <p>Error Pages</p>
                        <ul>
                            <li><a href="error-404.html">404 Error </a></li>
                            <li><a href="error-500.html">500 Error </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-subscriptions" role="tabpanel" aria-labelledby="v-pills-subscriptions-tab">
                        <p>Subscriptions</p>
                        <ul>
                            <li><a href="subscriptions.html"> Subscriptions (Admin) </a></li>
                            <li><a href="subscriptions-company.html"> Subscriptions (Company) </a></li>
                            <li><a href="subscribed-companies.html"> Subscribed Companies</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-pages" role="tabpanel" aria-labelledby="v-pills-pages-tab">
                        <p>Pages</p>
                        <ul>
                            <li><a href="search.html"> Search </a></li>
                            <li><a href="faq.html"> FAQ </a></li>
                            <li><a href="terms.html"> Terms </a></li>
                            <li><a href="privacy-policy.html"> Privacy Policy </a></li>
                            <li><a href="blank-page.html"> Blank Page </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-forms" role="tabpanel" aria-labelledby="v-pills-forms-tab">
                        <p>Forms</p>
                        <ul>
                            <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                            <li><a href="form-input-groups.html">Input Groups </a></li>
                            <li><a href="form-horizontal.html">Horizontal Form </a></li>
                            <li><a href="form-vertical.html"> Vertical Form </a></li>
                            <li><a href="form-mask.html"> Form Mask </a></li>
                            <li><a href="form-validation.html"> Form Validation </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-tables" role="tabpanel" aria-labelledby="v-pills-tables-tab">
                        <p>Tables</p>
                        <ul>
                            <li><a href="tables-basic.html">Basic Tables </a></li>
                            <li><a href="data-tables.html">Data Table </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-documentation" role="tabpanel" aria-labelledby="v-pills-documentation-tab">
                        <p>Documentation</p>
                        <ul>
                            <li><a href="#">Documentation </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-changelog" role="tabpanel" aria-labelledby="v-pills-changelog-tab">
                        <p>Change Log</p>
                        <ul>
                            <li><a href="#"><span>Change Log</span> <span class="badge badge-primary ms-auto">v3.4</span> </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-multilevel" role="tabpanel" aria-labelledby="v-pills-multilevel-tab">
                        <p>Multi Level</p>
                        <ul>
                            <li class="sub-menu">
                                <a href="javascript:void(0);">Level 1 <span class="menu-arrow"></span></a>
                                <ul class="ms-3">
                                    <li class="sub-menu">
                                        <a href="javascript:void(0);">Level 1 <span class="menu-arrow"></span></a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Level 2</a></li>
                                            <li><a href="javascript:void(0);">Level 3</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0);">Level 2</a></li>
                            <li><a href="javascript:void(0);">Level 3</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Two Col Sidebar -->
    <!-- Page Wrapper -->
    <div class="page-wrapper p-0" style="margin: 0;">

        <!-- Chat Main Row -->
        <div class="chat-main-row">

            <!-- Chat Main Wrapper -->
            <div class="chat-main-wrapper">

                <!-- Chats View -->
                <div class="col-lg-9 message-view task-view">
                    <div class="chat-window">
                        <div class="fixed-header">
                            <div class="navbar">
                                <div class="user-details me-auto d-flex align-items-center">
                                    <div class="float-start user-img">
                                        <a class="avatar" href="profile.html" title="Mike Litorus">
                                            @if ($senderDetail)
                                                <img src="{{ asset('admin/profileImage/'.$senderDetail->image)}}" alt="User Image" class="rounded-circle">
                                                <span class="status online"></span>
                                            @else
                                                <img src="{{ asset('admin/assets/img/profiles/avatar-05.jpg')}}" alt="User Image" class="rounded-circle">
                                                <span class="status online"></span>
                                            @endif
                                        </a>                                        
                                    </div>
                                    <div class="user-info float-start">
                                        @if ($senderDetail)
                                        <a href="profile.html" title="Mike Litorus"><span>{{$senderDetail->name}}</span> <i class="typing-text"></i></a>                                             
                                        @endif
                                                                              
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-contents">
                            <div class="chat-content-wrap">
                                <div class="chat-wrap-inner">
                                    <div class="chat-box">
                                        <div class="chats">
                                            @for($i = 0; $i < count($supportTicketsMessages); $i++)
                                                @if ($supportTicketsMessages[$i]->messageable_type=='App\Models\Franchise')
                                                @if ($i==0 || $i-1>=0 && $supportTicketsMessages[$i-1]->created_at->format('Y-m-d')!=$supportTicketsMessages[$i]->created_at->format('Y-m-d'))
                                                <div class="chat-line d-inline-block">
                                                    <span class="chat-date">{{$supportTicketsMessages[$i]->created_at->format('F jS, Y')}}</span>
                                                </div>  
                                                @endif
                                                <div class="chat chat-right">
                                                    <div class="chat-body">
                                                        <div class="chat-bubble m-0">
                                                            <div class="chat-content mb-2">
                                                                <p>{{$supportTicketsMessages[$i]->body}}</p>
                                                                <span class="chat-time">{{ $supportTicketsMessages[$i]->created_at->format('H:i') }}</span>
                                                            </div>
                                                            <div class="chat-action-btns">
                                                                <ul>
                                                                    
                                                                    <li>
                                                                        <a href="#" class="del-msg" data-bs-toggle="modal" data-bs-target="#delete_role" onclick="delete_modal('{{$supportTicketsMessages[$i]->id}}')">
                                                                            <i class="fa-regular fa-trash-can"></i>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div id="edit_role{{$supportTicketsMessages[$i]->id}}" class="modal custom-modal fade" role="dialog">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content modal-md">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Edit Message</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="{{ route('franchise.support-ticket.updateChat', ['id' => $supportTicketsMessages[$i]->id]) }}" method="POST">
                                                                                @csrf
                                                                                <div class="input-block mb-3">
                                                                                    <label class="col-form-label">Message<span class="text-danger">*</span></label>
                                                                                    <textarea class="form-control pac-target-input" id="address" name="message" required="" autocomplete="off">{{$supportTicketsMessages[$i]->body}}</textarea>
                                                                                </div>
                                                                                <div class="submit-section">
                                                                                    <button class="btn btn-primary submit-btn">Save</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="chat chat-left ">
                                                    
                                                    @if ($i-1>=0 && $supportTicketsMessages[$i - 1]->messageable_type == 'App\Models\Franchise')
                                                    <div class="chat-avatar">
                                                        <a class="avatar" href="profile.html" title="Mike Litorus">
                                                            @if ($senderDetail)
                                                                <img src="{{ asset('admin/profileImage/'.$senderDetail->image)}}" alt="User Image" class="rounded-circle">
                                                                <span class="status online"></span>
                                                            @else
                                                            <img src="{{ asset('admin/assets/img/profiles/avatar-05.jpg')}}" alt="User Image" class="rounded-circle">
                                                            <span class="status online"></span>
                                                            @endif
                                                        </a> 
                                                        </div>
                                                    @endif
                                                    <div class="chat-body">
                                                        <div class="chat-bubble m-0">
                                                            <div class="chat-content mb-2 @if($i-1>=0 && $supportTicketsMessages[$i - 1]->messageable_type != 'App\Models\Franchise') ml-38 @endif">                                        
                                                                <p>{{$supportTicketsMessages[$i]->body}}</p>
                                                                <span class="chat-time">{{$supportTicketsMessages[$i]->created_at->format('H:i')}}</span>
                                                            </div>
                                                        </div>                      
                                                    </div>
                                                </div>
                                                @endif
                                            @endfor
                                                                                    
                                            {{-- <div class="chat-line">
                                                <span class="chat-date">October 8th, 2018</span>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-footer">
                            <div class="message-bar">
                                <div class="message-inner">
                                    <div class="message-area">
                                        <div class="input-group">
                                            @php
                                                $currentUrl = url()->current();
                                                $parts = explode('/', $currentUrl);
                                                $lastPart = end($parts);
                                            @endphp
                                            <textarea class="form-control chat-input" placeholder="Type message..." data-id='{{$lastPart}}'></textarea>
                                            <button onclick="sendMessage()" class="btn btn-custom chat-send" type="button"><i class="fa-solid fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Chats View -->

                <!-- Chat Right Sidebar -->
                <div class="col-lg-3 message-view chat-profile-view chat-sidebar" id="task_window">
                    <div class="chat-window video-window">
                        <div class="fixed-header">
                            <ul class="nav nav-tabs nav-tabs-bottom">
                             
                                <li class="nav-item"><a class="nav-link active" href="#profile_tab" data-bs-toggle="tab">Profile</a></li>
                            </ul>
                        </div>
                        <div class="tab-content chat-contents">
                            <div class="content-full tab-pane" id="calls_tab">
                                <div class="chat-wrap-inner">
                                    <div class="chat-box">
                                        <div class="chats">
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="profile.html" class="avatar">
                                                        <img src="assets/img/profiles/avatar-02.jpg" alt="User Image">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">You</span> <span class="chat-time">8:35 am</span>
                                                            <div class="call-details">
                                                                <i class="material-icons">phone_missed</i>
                                                                <div class="call-info">
                                                                    <div class="call-user-details">
                                                                        <span class="call-description">Jeffrey Warden missed the call</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="profile.html" class="avatar">
                                                        <a class="avatar" href="profile.html" title="Mike Litorus">
                                                            @if ($senderDetail)
                                                                <img src="{{ asset('admin/profileImage/'.$senderDetail->image)}}" alt="User Image" class="rounded-circle">
                                                                <span class="status online"></span>
                                                            @else
                                                            <img src="{{ asset('admin/assets/img/profiles/avatar-05.jpg')}}" alt="User Image" class="rounded-circle">
                                                            <span class="status online"></span>
                                                            @endif
                                                        </a>
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">John Doe</span> <span class="chat-time">8:35 am</span>
                                                            <div class="call-details">
                                                                <i class="material-icons">call_end</i>
                                                                <div class="call-info">
                                                                    <div class="call-user-details"><span class="call-description">This call has ended</span></div>
                                                                    <div class="call-timing">Duration: <strong>5 min 57 sec</strong></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat-line">
                                                <span class="chat-date">January 29th, 2019</span>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="profile.html" class="avatar">
                                                        <a class="avatar" href="profile.html" title="Mike Litorus">
                                                            @if ($senderDetail)
                                                                <img src="{{ asset('admin/profileImage/'.$senderDetail->image)}}" alt="User Image" class="rounded-circle">
                                                                <span class="status online"></span>
                                                            @else
                                                            <img src="{{ asset('admin/assets/img/profiles/avatar-05.jpg')}}" alt="User Image" class="rounded-circle">
                                                            <span class="status online"></span>
                                                                <span class="status online"></span>
                                                            @endif
                                                        </a>
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">Richard Miles</span> <span class="chat-time">8:35 am</span>
                                                            <div class="call-details">
                                                                <i class="material-icons">phone_missed</i>
                                                                <div class="call-info">
                                                                    <div class="call-user-details">
                                                                        <span class="call-description">You missed the call</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="profile.html" class="avatar">
                                                        <a class="avatar" href="profile.html" title="Mike Litorus">
                                                            @if ($senderDetail)
                                                                <img src="{{ asset('admin/profileImage/'.$senderDetail->image)}}" alt="User Image" class="rounded-circle">
                                                                <span class="status online"></span>
                                                            @else
                                                            <img src="{{ asset('admin/assets/img/profiles/avatar-05.jpg')}}" alt="User Image" class="rounded-circle">
                                                            <span class="status online"></span>
                                                            @endif
                                                        </a>
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">You</span> <span class="chat-time">8:35 am</span>
                                                            <div class="call-details">
                                                                <i class="material-icons">ring_volume</i>
                                                                <div class="call-info">
                                                                    <div class="call-user-details">
                                                                        <a href="#" class="call-description call-description--linked" data-qa="call_attachment_link">Calling John Smith ...</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content-full tab-pane show active" id="profile_tab">
                                <div class="display-table">
                                    <div class="table-row">
                                        <div class="table-body">
                                            <div class="table-content">
                                                <div class="chat-profile-img">
                                                    <div class="edit-profile-img">
                                                        <a  href="profile.html" title="Mike Litorus">
                                                            @if ($senderDetail)
                                                                <img src="{{ asset('admin/profileImage/'.$senderDetail->image)}}" alt="User Image" class="rounded-circle">
                                                                <span class="status online"></span>
                                                            @else
                                                            <img src="{{ asset('admin/assets/img/profiles/avatar-05.jpg')}}" alt="User Image" class="rounded-circle">
                                                            <span class="status online"></span>
                                                                <span class="status online"></span>
                                                            @endif
                                                        </a>
                                                    </div>
                                                    @if ($senderDetail)
                                                    <h3 class="user-name m-t-10 mb-0">{{$senderDetail->name}}</h3>
                                                    <small class="text-muted">{{$senderDetail->name}}</small>                              
                                                     @endif          
                                                   
                                                </div>
                                                <div class="chat-profile-info">
                                                    <ul class="user-det-list">
                                                        <li>
                                                            <span>Email:</span>
                                                            @if ($senderDetail)
                                                            <span class="float-end text-muted">{{$senderDetail->email}}</span>
                                                            @endif
                                                           
                                                        </li>
                                                        <li>
                                                            <span>Phone:</span>
                                                            @if ($senderDetail)
                                                            <span class="float-end text-muted">{{$senderDetail->phone}}</span>
                                                            @endif
                                                            
                                                        </li>
                                                    </ul>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Chat Right Sidebar -->

            </div>
            <!-- /Chat Main Wrapper -->

        </div>
        <!-- /Chat Main Row -->

        <!-- Drogfiles Modal -->
        <div id="drag_files" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Drag and drop files upload</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="js-upload-form">
                            <div class="upload-drop-zone" id="drop-zone">
                                <i class="fa fa-cloud-upload fa-2x"></i> <span class="upload-text">Just drag and drop files here</span>
                            </div>
                            <h4>Uploading</h4>
                            <ul class="upload-list">
                                <li class="file-list">
                                    <div class="upload-wrap">
                                        <div class="file-name">
                                            <i class="fa fa-photo"></i>
                                            photo.png
                                        </div>
                                        <div class="file-size">1.07 gb</div>
                                        <button type="button" class="file-close">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </div>
                                    <div class="progress progress-xs progress-striped">
                                        <div class="progress-bar bg-success w-65" role="progressbar"></div>
                                    </div>
                                    <div class="upload-process">37% done</div>
                                </li>
                                <li class="file-list">
                                    <div class="upload-wrap">
                                        <div class="file-name">
                                            <i class="fa fa-file"></i>
                                            task.doc
                                        </div>
                                        <div class="file-size">5.8 kb</div>
                                        <button type="button" class="file-close">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </div>
                                    <div class="progress progress-xs progress-striped">
                                        <div class="progress-bar bg-success w-65" role="progressbar"></div>
                                    </div>
                                    <div class="upload-process">37% done</div>
                                </li>
                                <li class="file-list">
                                    <div class="upload-wrap">
                                        <div class="file-name">
                                            <i class="fa fa-photo"></i>
                                            dashboard.png
                                        </div>
                                        <div class="file-size">2.1 mb</div>
                                        <button type="button" class="file-close">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </div>
                                    <div class="progress progress-xs progress-striped">
                                        <div class="progress-bar bg-success w-65" role="progressbar"></div>
                                    </div>
                                    <div class="upload-process">Completed</div>
                                </li>
                            </ul>
                        </form>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Drogfiles Modal -->

        <!-- Add Group Modal -->
        <div id="add_group" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create a group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Groups are where your team communicates. They’re best when organized around a topic — #leads, for example.</p>
                        <form>
                            <div class="input-block mb-3">
                                <label class="col-form-label">Group Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text">
                            </div>
                            <div class="input-block mb-3">
                                <label class="col-form-label">Send invites to: <span class="text-muted-light">(optional)</span></label>
                                <input class="form-control" type="text">
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Group Modal -->

        <!-- Add Chat User Modal -->
        <div id="add_chat_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Direct Chat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group m-b-30">
                            <input placeholder="Search to start a chat" class="form-control search-input" type="text">
                            <button class="btn btn-primary">Search</button>
                        </div>
                        <div>
                            <h5>Recent Conversations</h5>
                            <ul class="chat-user-list">
                                <li>
                                    <a href="#">
                                        <div class="chat-block d-flex">
                                            <span class="avatar align-self-center flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-16.jpg" alt="User Image">
                                            </span>
                                            <div class="media-body align-self-center text-nowrap flex-grow-1">
                                                <div class="user-name">Jeffery Lalor</div>
                                                <span class="designation">Team Leader</span>
                                            </div>
                                            <div class="text-nowrap align-self-center">
                                                <div class="online-date">1 day ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="chat-block d-flex">
                                            <span class="avatar align-self-center flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-13.jpg" alt="User Image">
                                            </span>
                                            <div class="media-body align-self-center text-nowrap flex-grow-1">
                                                <div class="user-name">Bernardo Galaviz</div>
                                                <span class="designation">Web Developer</span>
                                            </div>
                                            <div class="align-self-center text-nowrap">
                                                <div class="online-date">3 days ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="chat-block d-flex">
                                            <span class="avatar align-self-center flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-02.jpg" alt="User Image">
                                            </span>
                                            <div class="media-body text-nowrap align-self-center flex-grow-1">
                                                <div class="user-name">John Doe</div>
                                                <span class="designation">Web Designer</span>
                                            </div>
                                            <div class="align-self-center text-nowrap">
                                                <div class="online-date">7 months ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Chat User Modal -->

        <!-- Share Files Modal -->
        <div id="share_files" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Share File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="files-share-list">
                            <div class="files-cont">
                                <div class="file-type">
                                    <span class="files-icon"><i class="fa-regular fa-file-pdf"></i></span>
                                </div>
                                <div class="files-info">
                                    <span class="file-name text-ellipsis">AHA Selfcare Mobile Application Test-Cases.xls</span>
                                    <span class="file-author"><a href="#">Bernardo Galaviz</a></span> <span class="file-date">May 31st at 6:53 PM</span>
                                </div>
                            </div>
                        </div>
                        <div class="input-block mb-3">
                            <label class="col-form-label">Share With</label>
                            <input class="form-control" type="text">
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Share</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Share Files Modal -->

    </div>
    <!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->

<div class="settings-icon">
    <span data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas"><i class="las la-cog"></i></span>
</div>
<div class="settings-icon">
    <span data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas"><i class="las la-cog"></i></span>
</div>
<div class="offcanvas offcanvas-end border-0 " tabindex="-1" id="theme-settings-offcanvas">
    <div class="sidebar-headerset">
        <div class="sidebar-headersets">
            <h2>Customizer</h2>
            <h3>Customize your overview Page layout</h3>
        </div>
        <div class="sidebar-headerclose">
            <a data-bs-dismiss="offcanvas" aria-label="Close"><img src="assets/img/close.png" alt="Close Icon"></a>
        </div>
    </div>
    <div class="offcanvas-body p-0">
        <div data-simplebar class="h-100">
            <div class="settings-mains">
                <div class="layout-head">
                    <h5>Layout</h5>
                    <h6>Choose your layout</h6>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input id="customizer-layout01" name="data-layout" type="radio" value="vertical" class="form-check-input">
                            <label class="form-check-label avatar-md w-100" for="customizer-layout01">
                                <img src="assets/img/vertical.png" alt="Layout Image">
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Vertical</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input id="customizer-layout02" name="data-layout" type="radio" value="horizontal" class="form-check-input">
                            <label class="form-check-label  avatar-md w-100" for="customizer-layout02">
                                <img src="assets/img/horizontal.png" alt="Layout Image">
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Horizontal</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input id="customizer-layout03" name="data-layout" type="radio" value="twocolumn" class="form-check-input">
                            <label class="form-check-label  avatar-md w-100" for="customizer-layout03">
                                <img src="assets/img/two-col.png" alt="Layout Image">
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Two Column</h5>
                    </div>
                </div>
                <div class="layout-head pt-3">
                    <h5>Color Scheme</h5>
                    <h6>Choose Light or Dark Scheme.</h6>
                </div>
                <div class="colorscheme-cardradio">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-orange" value="orange">
                                <label class="form-check-label  avatar-md w-100 " for="layout-mode-orange">
                                    <img src="assets/img/vertical.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Orange</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-light" value="light">
                                <label class="form-check-label  avatar-md w-100" for="layout-mode-light">
                                    <img src="assets/img/vertical.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Light</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio dark  p-0 ">
                                <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-dark" value="dark">
                                <label class="form-check-label avatar-md w-100 " for="layout-mode-dark">
                                    <img src="assets/img/vertical.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Dark</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio blue  p-0 ">
                                <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-blue" value="blue">
                                <label class="form-check-label  avatar-md w-100" for="layout-mode-blue">
                                    <img src="assets/img/vertical.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Blue</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio maroon p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-maroon" value="maroon">
                                <label class="form-check-label  avatar-md w-100 " for="layout-mode-maroon">
                                    <img src="assets/img/vertical.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Maroon</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio purple p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-purple" value="purple">
                                <label class="form-check-label  avatar-md w-100 " for="layout-mode-purple">
                                    <img src="assets/img/vertical.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Purple</h5>
                        </div>
                    </div>
                </div>

                <div id="layout-width">
                    <div class="layout-head pt-3">
                        <h5>Layout Width</h5>
                        <h6>Choose Fluid or Boxed layout.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-width" id="layout-width-fluid" value="fluid">
                                <label class="form-check-label avatar-md w-100" for="layout-width-fluid">
                                    <img src="assets/img/vertical.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Fluid</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio p-0 ">
                                <input class="form-check-input" type="radio" name="data-layout-width" id="layout-width-boxed" value="boxed">
                                <label class="form-check-label avatar-md w-100 px-2" for="layout-width-boxed">
                                    <img src="assets/img/boxed.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Boxed</h5>
                        </div>
                    </div>
                </div>

                <div id="layout-position">
                    <div class="layout-head pt-3">
                        <h5>Layout Position</h5>
                        <h6>Choose Fixed or Scrollable Layout Position.</h6>
                    </div>
                    <div class="btn-group bor-rad-50 overflow-hidden radio" role="group">
                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed">
                        <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                        <label class="btn btn-light w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                    </div>
                </div>
                <div class="layout-head pt-3">
                    <h5>Topbar Color</h5>
                    <h6>Choose Light or Dark Topbar Color.</h6>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio  p-0">
                            <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-light" value="light">
                            <label class="form-check-label avatar-md w-100" for="topbar-color-light">
                                <img src="assets/img/vertical.png" alt="Layout Image">
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Light</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-dark" value="dark">
                            <label class="form-check-label  avatar-md w-100" for="topbar-color-dark">
                                <img src="assets/img/dark.png" alt="Layout Image">
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Dark</h5>
                    </div>
                </div>

                <div id="sidebar-size">
                    <div class="layout-head pt-3">
                        <h5>Sidebar Size</h5>
                        <h6>Choose a size of Sidebar.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio  p-0 ">
                                <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-default" value="lg">
                                <label class="form-check-label avatar-md w-100" for="sidebar-size-default">
                                    <img src="assets/img/vertical.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Default</h5>
                        </div>

                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-compact" value="md">
                                <label class="form-check-label  avatar-md w-100" for="sidebar-size-compact">
                                    <img src="assets/img/compact.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Compact</h5>
                        </div>

                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0 ">
                                <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-small-hover" value="sm-hover">
                                <label class="form-check-label avatar-md w-100" for="sidebar-size-small-hover">
                                    <img src="assets/img/small-hover.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Small Hover View</h5>
                        </div>
                    </div>
                </div>

                <div id="sidebar-view">
                    <div class="layout-head pt-3">
                        <h5>Sidebar View</h5>
                        <h6>Choose Default or Detached Sidebar view.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio  p-0">
                                <input class="form-check-input" type="radio" name="data-layout-style" id="sidebar-view-default" value="default">
                                <label class="form-check-label avatar-md w-100" for="sidebar-view-default">
                                    <img src="assets/img/compact.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Default</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-style" id="sidebar-view-detached" value="detached">
                                <label class="form-check-label  avatar-md w-100" for="sidebar-view-detached">
                                    <img src="assets/img/detached.png" alt="Layout Image">
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Detached</h5>
                        </div>
                    </div>
                </div>
                <div id="sidebar-color">
                    <div class="layout-head pt-3">
                        <h5>Sidebar Color</h5>
                        <h6>Choose a color of Sidebar.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-light" value="light">
                                <label class="form-check-label  avatar-md w-100" for="sidebar-color-light">
                                    <span class="bg-light bg-sidebarcolor"></span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Light</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-dark" value="dark">
                                <label class="form-check-label  avatar-md w-100" for="sidebar-color-dark">
                                    <span class="bg-darks bg-sidebarcolor"></span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Dark</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient" value="gradient">
                                <label class="form-check-label avatar-md w-100" for="sidebar-color-gradient">
                                    <span class="bg-gradients bg-sidebarcolor"></span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Gradient</h5>
                        </div>
                        <div class="col-4 d-none">
                            <button class="btn btn-link avatar-md w-100 p-0 overflow-hidden border collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient" aria-expanded="false" aria-controls="collapseBgGradient">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-vertical-gradient d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </button>
                            <h5 class="fs-13 text-center mt-2">Gradient</h5>
                        </div>
                    </div>
                    <div class="collapse d-none" id="collapseBgGradient">
                        <div class="d-flex gap-2 flex-wrap img-switch p-2 px-3 bg-light rounded">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-1" value="gradient">
                                <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient">
                                    <span class="avatar-title rounded-circle bg-vertical-gradient"></span>
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-2" value="gradient-2">
                                <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-2">
                                    <span class="avatar-title rounded-circle bg-vertical-gradient-2"></span>
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-3" value="gradient-3">
                                <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-3">
                                    <span class="avatar-title rounded-circle bg-vertical-gradient-3"></span>
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-4" value="gradient-4">
                                <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-4">
                                    <span class="avatar-title rounded-circle bg-vertical-gradient-4"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="offcanvas-footer border-top p-3 text-center">
        <div class="row">
            <div class="col-6">
                <button type="button" class="btn btn-light w-100 bor-rad-50" id="reset-layout">Reset</button>
            </div>
            <div class="col-6">
                <a href="https://themeforest.net/item/smarthr-bootstrap-admin-panel-template/21153150" target="_blank" class="btn btn-primary w-100 bor-rad-50">Buy Now</a>
            </div>
        </div>
    </div>
</div>


<!-- Delete  Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="" id="delete_button" class="btn btn-primary continue-btn">Delete</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete  Modal -->


@push('page-javascript')
<script>

function scrollToBottom(className) {
    var chatWrapInner = document.querySelector(className);

    if (chatWrapInner.scrollHeight > chatWrapInner.clientHeight) {
        chatWrapInner.scrollTop = chatWrapInner.scrollHeight - chatWrapInner.clientHeight;
    }
}
scrollToBottom(".chat-wrap-inner"); 

    function delete_modal(id) {
        event.preventDefault();
        const deleteUrl = "{{ route('franchise.support-ticket.deleteChat', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }
    $(document).ready(function() {
        $('.chat-input').keypress(function(event) {
            if (event.which === 13) { 
                event.preventDefault(); 
                sendMessage(); 
            }
        });
    });

    function sendMessage() {
        var message = $('.chat-input').val();
        var id = $('.chat-input').data('id'); 
        console.log("reached here",id)
        console.log("reached here",message)
        $.ajax({
            url: '{{ route("franchise.support-ticket.send") }}',
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
                message: message,
            },   
            success: function(data) {
                console.log("reached here",data)
                const editUrl = "{{ route('franchise.support-ticket.updateChat', ['id' => ':id']) }}".replace(':id', data.messageData.id);
                let token="{{ csrf_token() }}";
                $('.chat-input').val('');
                $('.chats').append(`
                    <div class="chat chat-right">
                        <div class="chat-body">
                            <div class="chat-bubble m-0">
                                <div class="chat-content mb-2">
                                    <p>${data.messageData.body}</p>
                                    <span class="chat-time">18:25</span>
                                </div>
                                <div class="chat-action-btns">
                                    <ul>
                                        <li>
                                            <a href="#" class="edit-msg">
                                                <i class="fa-solid fa-pencil" data-bs-toggle="modal" data-bs-target="#edit_role${data.messageData.id}"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="del-msg" data-bs-toggle="modal" data-bs-target="#delete_role" onclick="delete_modal('${data.messageData.id}')">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div id="edit_role${data.messageData.id}" class="modal custom-modal fade" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content modal-md">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Message</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="${editUrl}" method="POST">
                                                    <input type="hidden" name="_token" value="${token}" autocomplete="off">
                                                    <div class="input-block mb-3">
                                                        <label class="col-form-label">Message<span class="text-danger">*</span></label>
                                                        <textarea class="form-control pac-target-input" id="address" name="message" required="" autocomplete="off">${data.messageData.body}</textarea>
                                                    </div>
                                                    <div class="submit-section">
                                                        <button class="btn btn-primary submit-btn">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                scrollToBottom(".chat-wrap-inner");                 
            }
        });
    }
</script>
@endpush

@endsection