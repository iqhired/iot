<nav class="navbar p-0 fixed-top d-flex flex-row">
	<div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
		<a class="navbar-brand brand-logo-mini" href="#"> <img src="<?php echo $iotURL; ?>assets/images/site_logo.png" alt="logo" /></a>
	</div>
	<div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
		<button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
			<i class="fa-sharp fa-solid fa-caret-right"></i>
		</button>
		<ul class="navbar-nav w-100">
			<li class="nav-item w-100">
				<h2><?php echo $device_name;?></h2>
			</li>
		</ul>
		<ul class="navbar-nav navbar-nav-right">
			<li class="nav-item dropdown">
				<a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
					<div class="navbar-profile">
						<?php if(!empty($_SESSION["uu_img"])) {?>
							<img class="img-xs rounded-circle" src="<?php echo $iotURL; ?>user_images/<?php echo $_SESSION["uu_img"]; ?>" alt="">
						<?php }else{?>
							<img class="img-xs rounded-circle" src="<?php echo $iotURL; ?>user_images/user.png" alt="">
						<?php }?>
						<p class="mb-0 d-none d-sm-block navbar-profile-name"><?php echo $_SESSION['fullname']; ?></p>
						<i class="fa-solid fa-chevron-down"></i>
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
					<div class="dropdown-divider"></div>
					<a class="dropdown-item preview-item" href="<?php echo $iotURL; ?>profile.php">
						<div class="preview-thumbnail">
							<div class="preview-icon bg-dark rounded-circle">
								<i class="fa fa-user"></i>
							</div>
						</div>
						<div class="preview-item-content">
							<p class="preview-subject mb-1">My profile</p>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item preview-item" href="<?php echo $iotURL; ?>change_pass.php">
						<div class="preview-thumbnail">
							<div class="preview-icon bg-dark rounded-circle">
								<i class="fa fa-lock"></i>
							</div>
						</div>
						<div class="preview-item-content">
							<p class="preview-subject mb-1">Change Password</p>
						</div>
					</a>
					<a class="dropdown-item preview-item" href="<?php echo $iotURL; ?>index.php">
						<div class="preview-thumbnail">
							<div class="preview-icon bg-dark rounded-circle">
								<i class="fa fa-right-from-bracket"></i>
							</div>
						</div>
						<div class="preview-item-content">
							<p class="preview-subject mb-1">Log out</p>
						</div>
					
					</a>
				</div>
			</li>
		</ul>
		<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
			<i class="fa-solid fa-bars"></i>
		</button>
	</div>
</nav>