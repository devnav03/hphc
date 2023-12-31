<!DOCTYPE html>
<html lang="en">
	
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf_token" content="{{csrf_token()}}" />
		<title>{{ auth()->user()->name ?? '' }} :: HRMS</title>
		<!-- base:css -->
		
		<link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
		<link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
		<!-- endinject -->
		<!-- plugin css for this page -->
		<!-- End plugin css for this page -->
		<!-- inject:css -->
		<link href="{{asset('css/toastr.min.css')}}" rel="stylesheet"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<link rel="stylesheet" href="{{asset('css/style.css')}}">
		<!-- endinject -->
		<link rel="shortcut icon" href="{{asset('organization/logo')}}/{{$organisation->fab_icon}}" />
		<style>
			.navbar{
            background-image:none !important;
            background-color: #135cbb;
            height: 74px;
			}
			.sidebar .nav .sidebar-category {
            margin: -0.8rem 2.125rem 0.4rem 0.4rem !important;
			}
			.navbar .navbar-menu-wrapper {
            transition: width 0.25s ease;
            -webkit-transition: width 0.25s ease;
            -moz-transition: width 0.25s ease;
            -ms-transition: width 0.25s ease;
            color: #ffffff;
            margin-left: 1.437rem;
            margin-right: 1.437rem;
            width: 100%;
            height: 36px;
			}
			.side-logo{
            width: 4rem !important;
            margin-left: -30px !important;
			}
			@media only screen and (max-width: 768px) {
            .mob-logo {
			display: block !important;
            }
            .clockdate-wrapper{
			display: none;
            }
			}
			.imgresponcive{
            50px !important;
			}
		</style>
	</head>
	
	<body>
		<div id="loader"></div>
		<div class="container-scroller d-flex">
			<!-- partial:./partials/_sidebar.html -->
			<nav class="sidebar sidebar-offcanvas" id="sidebar">
				<ul class="nav">
					<li class="nav-item sidebar-category" style="text-align: center; margin: 0 !important;"><a class="navbar-brand brand-logo" href="{{route('home')}}" id="img_customize"><img src="{{asset('organization/logo')}}/{{$organisation->logo}}" alt="logo" style="width:100px" /></a></li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('home')}}">
							<i class="mdi mdi-view-quilt menu-icon"></i>
							<span class="menu-title">{{ auth()->user()->name ?? '' }} Dashboard</span>
							<div class="badge badge-info badge-pill">2</div>
						</a>
					</li>
					
					<?php 
						
						$user_id = Auth::user()->id;
						
						//$check_role = DB::table('role_user')->select('role_user.*')->get();
						
						$check_role = DB::table('role_user')
						->join('roles', 'role_user.role_id', '=', 'roles.id')
						->select('roles.slug') // You can select specific columns from both tables
						->where('role_user.user_id',$user_id)
						->first();
						
						if(!empty($check_role->slug)){
							$slug = $check_role->slug;
							}else{
							$slug = "";
						}
						
						//print_r($check_role);
						
						if($slug=="super-admin"){
							
						?>
						
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#master-entry" aria-expanded="false"
							aria-controls="master-entry">
								<i class="mdi mdi-account menu-icon"></i>
								<span class="menu-title">User Master</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="master-entry">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('user-list')}}">Users</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('user-role')}}">Roles</a></li>
									
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#master-entry" aria-expanded="false"
							aria-controls="master-entry">
								<i class="mdi mdi-account menu-icon"></i>
								<span class="menu-title">Master Entry</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="master-entry">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('office-master')}}">Office Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('department-master')}}">Department Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-position')}}">Designation Master</a></li>
									<!-- <li class="nav-item"> <a class="nav-link" href="{{url('employee-master')}}">Onboard Employee Master</a></li> -->
									<li class="nav-item"> <a class="nav-link" href="{{url('form-category-master')}}">Form Category Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('emp-type-master')}}">Employee Type Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('leave-master')}}">Leave Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-source')}}">Recruitment Source Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-notice-period')}}">Notice Period Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-educations')}}">Education Master</a></li> 
									<li class="nav-item"> <a class="nav-link" href="{{url('bank-master')}}">Bank Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-project')}}">Task Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('template-master')}}">Template Master</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('header-footer-template-master')}}">Header and Footer Master</a></li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#shift" aria-expanded="false"
							aria-controls="shift">
								<i class="mdi mdi-account menu-icon"></i>
								<span class="menu-title">Shift</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="shift">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('add-shift')}}">Add Shift</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('shift-details')}}">Shift Details</a></li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#form-engine" aria-expanded="false"
							aria-controls="form-engine">
								<i class="mdi mdi-account menu-icon"></i>
								<span class="menu-title">Organisation level</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="form-engine">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('add-form')}}">Add Form</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{route('add.form.engine')}}">Form Engine</a></li>
								</ul>
							</div>
						</li>
						
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
							aria-controls="ui-basic">
								<i class="mdi mdi-account menu-icon"></i>
								<span class="menu-title">Employee Management </span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="ui-basic">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('add-employeess')}}">Add Employee</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{route('employee.details')}}">Employee List</a></li>
									<!--<li class="nav-item"> <a class="nav-link" href="{{url('employee-reporting')}}">Employee Reporting Officer</a></li>-->
								</ul>
							</div>
						</li>
						
						
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#time-and-attendance" aria-expanded="false"
							aria-controls="time-and-attendance">
								<i class="mdi mdi-bookmark-check menu-icon"></i>
								<span class="menu-title">Time & Attendance</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="time-and-attendance">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('manual-mark-attendance')}}">Manual Attendance</a>
									</li>
									<li class="nav-item"> <a class="nav-link" href="{{route('view.employee-attendance')}}">View Employee Attendance</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#leave-management" aria-expanded="false"
							aria-controls="leave-management">
								<i class="mdi mdi-bookmark-check menu-icon"></i>
								<span class="menu-title">Leave Management</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="leave-management">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('approval-flow')}}">Leave Approval Flow</a>
									</li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-leave')}}">Add Leave</a>
									</li>
									<li class="nav-item"> <a class="nav-link" href="{{url('list-leave')}}">List Leave</a>
									</li>
									
									<li class="nav-item"> <a class="nav-link" href="{{url('view-employee-leave-list')}}">View Remaining Employee Leaves</a>
									</li>
									
									<li class="nav-item"> <a class="nav-link" href="{{url('view-employee-short-leaves')}}">View last month Short Leave</a></li>
									<!--<li class="nav-item"> <a class="nav-link" href="{{route('view.employee-leave')}}">Pending Leave</a>-->
									<!--</li>-->
									<!--<li class="nav-item"> <a class="nav-link" href="{{url('approved-leave')}}">Approved Leave</a>-->
									<!--</li>-->
									<!--<li class="nav-item"> <a class="nav-link" href="{{url('reject-leave')}}">Reject Leave</a>-->
									<!--</li>-->
								</ul>
							</div>
						</li>
						
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#template" aria-expanded="false"
							aria-controls="template">
								<i class="mdi mdi-account menu-icon"></i>
								<span class="menu-title">Leave Template</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="template">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('add-email-template')}}">Add Email</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-sms-template')}}">Add SMS</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-notification-template')}}">Add Notification</a></li>
								</ul>
							</div>
						</li>
						
						
						
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#help-desk" aria-expanded="false"
							aria-controls="help-desk">
								<i class="mdi mdi-bookmark-check menu-icon"></i>
								<span class="menu-title">Service Desk</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="help-desk">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('help-manual')}}">Help Manual</a>
									</li>
									<li class="nav-item"> <a class="nav-link" href="{{url('access-to-variouse-form')}}">Access to Variouse Form</a>
									</li>
									<li class="nav-item"> <a class="nav-link" href="{{url('lodging-tracking-suggetions')}}">Lodging & Tracking Suggetions</a>
									</li>
									<li class="nav-item"> <a class="nav-link" href="{{url('suggetions-management')}}">Suggetions Management</a>
									</li>
								</ul>
							</div>
						</li>
						
						<li class="nav-item">
							<a class="nav-link" href="#">
								<i class="mdi mdi-bell menu-icon"></i>
								<span class="menu-title">Announcement</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<i class="mdi mdi-account-alert menu-icon"></i>
								<span class="menu-title">Notification</span>
							</a>
						</li>
						
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#report-master" aria-expanded="false"
							aria-controls="report-master">
								<i class="mdi mdi-bookmark-check menu-icon"></i>
								<span class="menu-title">Report Master</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="report-master">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('report-master')}}">Attendence Report</a></li>
								</ul>
							</div>
						</li>
						<?php }elseif($slug=="leave-authority"){ ?>
						
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#leave-management" aria-expanded="false"
							aria-controls="leave-management">
								<i class="mdi mdi-bookmark-check menu-icon"></i>
								<span class="menu-title">Leave Management</span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="leave-management">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('approval-flow')}}">Leave Approval Flow</a>
									</li>
									<li class="nav-item"> <a class="nav-link" href="{{url('add-leave')}}">Add Leave</a>
									</li>
									<li class="nav-item"> <a class="nav-link" href="{{url('list-leave')}}">List Leave</a>
									</li>
									
									<li class="nav-item"> <a class="nav-link" href="{{url('view-employee-leave-list')}}">View Remaining Employee Leaves</a>
									</li>
									
									<li class="nav-item"> <a class="nav-link" href="{{url('view-employee-short-leaves')}}">View last month Short Leave</a></li>
									<!--<li class="nav-item"> <a class="nav-link" href="{{route('view.employee-leave')}}">Pending Leave</a>-->
									<!--</li>-->
									<!--<li class="nav-item"> <a class="nav-link" href="{{url('approved-leave')}}">Approved Leave</a>-->
									<!--</li>-->
									<!--<li class="nav-item"> <a class="nav-link" href="{{url('reject-leave')}}">Reject Leave</a>-->
									<!--</li>-->
								</ul>
							</div>
						</li>
						
						<?php }elseif($slug=="employee-management"){ ?>
						
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
							aria-controls="ui-basic">
								<i class="mdi mdi-account menu-icon"></i>
								<span class="menu-title">Employee Management </span>
								<i class="menu-arrow"></i>
							</a>
							<div class="collapse" id="ui-basic">
								<ul class="nav flex-column sub-menu">
									<li class="nav-item"> <a class="nav-link" href="{{url('add-employeess')}}">Add Employee</a></li>
									<li class="nav-item"> <a class="nav-link" href="{{route('employee.details')}}">Employee List</a></li>
									<!--<li class="nav-item"> <a class="nav-link" href="{{url('employee-reporting')}}">Employee Reporting Officer</a></li>-->
								</ul>
							</div>
						</li>
						
						<?php }else{ ?>
						
						
					<?php } ?>
					
					
					
				</ul>
			</nav>
			<!-- partial -->
			<div class="container-fluid page-body-wrapper">
				<!-- partial:./partials/_navbar.html -->
				<nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
					<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
						<button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-toggle="minimize">
							<span class="mdi mdi-menu"></span>
						</button>
						<div class="navbar-brand-wrapper">
							<a class="navbar-brand brand-logo" href="{{route('home')}}"><img src="{{asset('organization/logo')}}/{{$organisation->logo}}" alt="logo" class="mob-logo side-logo" style="width:100px;display:none" /></a>
							<a class="navbar-brand brand-logo-mini" href="{{route('home')}}"><img src="{{asset('organization/logo')}}/{{$organisation->logo}}" style="width:100px alt="logo" /></a>
						</div>
						<h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Welcome back, {{ auth()->user()->name ?? '' }}</h4>
						<ul class="navbar-nav navbar-nav-right">
							<li class="nav-item">
								<div id="clockdate">
									<div class="clockdate-wrapper">
										<div id="clock"></div>
										<div id="date"></div>
									</div>
								</div>
							</li>
							
							<!-- <li class="nav-item dropdown mr-1">
								<a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
                                id="messageDropdown" href="#" data-toggle="dropdown">
                                <i class="mdi mdi-calendar mx-0"></i>
                                <span class="count bg-info">2</span>
								</a>
								<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                aria-labelledby="messageDropdown">
                                <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
                                <a class="dropdown-item preview-item">
								<div class="preview-thumbnail">
								<img src="images/faces/face4.jpg" alt="image" class="profile-pic">
								</div>
								<div class="preview-item-content flex-grow">
								<h6 class="preview-subject ellipsis font-weight-normal">David Grey
								</h6>
								<p class="font-weight-light small-text text-muted mb-0">
								The meeting is cancelled
								</p>
								</div>
                                </a>
                                <a class="dropdown-item preview-item">
								<div class="preview-thumbnail">
								<img src="images/faces/face2.jpg" alt="image" class="profile-pic">
								</div>
								<div class="preview-item-content flex-grow">
								<h6 class="preview-subject ellipsis font-weight-normal">Tim Cook
								</h6>
								<p class="font-weight-light small-text text-muted mb-0">
								New product launch
								</p>
								</div>
                                </a>
                                <a class="dropdown-item preview-item">
								<div class="preview-thumbnail">
								<img src="images/faces/face3.jpg" alt="image" class="profile-pic">
								</div>
								<div class="preview-item-content flex-grow">
								<h6 class="preview-subject ellipsis font-weight-normal"> Johnson
								</h6>
								<p class="font-weight-light small-text text-muted mb-0">
								Upcoming board meeting
								</p>
								</div>
                                </a>
								</div>
							</li> -->
							
							<li class="nav-item dropdown mr-2">
								<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
									<!-- <img src="{{asset('organization/logo')}}/{{$organisation->logo}}" alt="profile" /> -->
									<span class="nav-profile-name">{{ auth()->user()->name ?? '' }}</span>
								</a>
								<div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                                aria-labelledby="profileDropdown">
									<a class="dropdown-item">
										<i class="mdi mdi-settings text-primary"></i>
										Settings
									</a>
									<a class="dropdown-item" href="{{ route('logout') }}"
									onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">
										<i class="mdi mdi-logout text-primary"></i>
										Logout
									</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
										@csrf
										<input type="hidden" name="username_redirect" value="{{$organisation->user_name}}">
									</form>
								</div>
								
								<!-- <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center"
									id="notificationDropdown" href="#" data-toggle="dropdown">
									<i class="mdi mdi-email-open mx-0"></i>
									<span class="count bg-danger">1</span>
									</a>
									<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
									aria-labelledby="notificationDropdown">
									<p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
									<a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
									<div class="preview-icon bg-success">
									<i class="mdi mdi-information mx-0"></i>
									</div>
                                    </div>
                                    <div class="preview-item-content">
									<h6 class="preview-subject font-weight-normal">Application Error</h6>
									<p class="font-weight-light small-text mb-0 text-muted">
									Just now
									</p>
                                    </div>
									</a>
									<a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
									<div class="preview-icon bg-warning">
									<i class="mdi mdi-settings mx-0"></i>
									</div>
                                    </div>
                                    <div class="preview-item-content">
									<h6 class="preview-subject font-weight-normal">Settings</h6>
									<p class="font-weight-light small-text mb-0 text-muted">
									Private message
									</p>
                                    </div>
									</a>
									<a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
									<div class="preview-icon bg-info">
									<i class="mdi mdi-account-box mx-0"></i>
									</div>
                                    </div>
                                    <div class="preview-item-content">
									<h6 class="preview-subject font-weight-normal">New user registration</h6>
									<p class="font-weight-light small-text mb-0 text-muted">
									2 days ago
									</p>
                                    </div>
									</a>
								</div> -->
							</li>
						</ul>
						<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
							<span class="mdi mdi-menu"></span>
						</button>
					</div>
					<!-- <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
						<ul class="navbar-nav mr-lg-2">
                        <li class="nav-item nav-search d-none d-lg-block">
						<div class="input-group">
						<input type="text" class="form-control" placeholder="Search Here..." aria-label="search"
						aria-describedby="search">
						</div>
                        </li>
						</ul>
						<ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
						<img src="{{asset('organization/logo')}}/{{$organisation->logo}}" alt="profile" />
						<span class="nav-profile-name">{{ auth()->user()->name ?? '' }}</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown"
						aria-labelledby="profileDropdown">
						<a class="dropdown-item">
						<i class="mdi mdi-settings text-primary"></i>
						Settings
						</a>
						<a class="dropdown-item" href="{{ route('logout') }}"
						onclick="event.preventDefault();
						document.getElementById('logout-form').submit();">
						<i class="mdi mdi-logout text-primary"></i>
						Logout
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
						@csrf
						<input type="hidden" name="username_redirect" value="{{$organisation->user_name}}">
						</form>
						</div>
                        </li>
                        <li class="nav-item">
						<a href="#" class="nav-link icon-link">
						<i class="mdi mdi-plus-circle-outline"></i>
						</a>
                        </li>
                        <li class="nav-item">
						<a href="#" class="nav-link icon-link">
						<i class="mdi mdi-web"></i>
						</a>
                        </li>
                        <li class="nav-item">
						<a href="#" class="nav-link icon-link">
						<i class="mdi mdi-clock-outline"></i>
						</a>
                        </li>
						</ul>
					</div> -->
				</nav>
				@yield('content')
				<!-- content-wrapper ends -->
				<!-- partial:./partials/_footer.html -->
				<footer class="footer">
					<div class="card">
						<div class="card-body">
							<div class="d-sm-flex justify-content-center justify-content-sm-between">
								<span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © {{ auth()->user()->name ?? '' }} 2020</span>
								<span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Distributed By:
								<a href="https://www.themewagon.com/" target="_blank">{{ auth()->user()->name ?? '' }}</a></span>
							</div>
						</div>
					</div>
				</footer>
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
	</div>
    <!-- container-scroller -->
	
    <!-- base:js -->
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="{{asset('vendors/chart.js/Chart.min.js')}}"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{asset('js/off-canvas.js')}}"></script>
    <script src="{{asset('js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
    <!-- endinject -->
    <script src="{{asset('js/dashboard.js')}}"></script>
    <!-- End custom js for this page-->
    <script src="{{asset('js/file-upload.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script src="{{asset('js/custome.js')}}"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
	
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script>
		function autoLoad() {  
			$.ajax({
				url: 'http://localhost:8080/hphc_hrms/reportSync',
				cache: false,
				success: function(data) {
					$('#show').html(data);
					console.log('Report was updated');
				}
			});
		}
		$(document).ready(function() {
			//autoLoad();
			setInterval(autoLoad, 60000);
		});
		
		
	</script>   
	
    <script type="text/javascript">
		$(function() {
			var start = moment().subtract(29, 'days');
			var end = moment();
			function cb(start, end) {
				$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
				$('#from_date_val').val(start.format('YYYY-MM-DD'));
				$('#to_date_val').val(end.format('YYYY-MM-DD'));
			}
			$('#reportrange').daterangepicker({
				startDate: start,
				endDate: end,
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
			},cb);
			cb(start, end);
		});
	</script>
    @if(!empty(session('success')))
    <script type="text/javascript">
        toastr.success("{{session('success')}}");
	</script>
    @endif
    @if(!empty(session('error')))
    <script type="text/javascript">
        toastr.error("{{session('error')}}");
	</script>
    @endif
    <script>
		$(document).ready(function(){
			$(".select2min").select2();
		});
	</script>
    <script>
		$(".navbar-toggler").click(function() {
			$('#img_customize').html('<img src="{{asset('organization/logo')}}/{{$organisation->fab_icon}}" alt="logo" style="width:50px" />');
			if($("body").hasClass('sidebar-icon-only')){
				$('#img_customize').html('<img src="{{asset('organization/logo')}}/{{$organisation->fab_icon}}" alt="logo" style="width:50px" />');
				}else{
				$('#img_customize').removeClass('imgresponcive');
			}
		});
	</script>
    @stack('body-scripts')
</body>
</html>
