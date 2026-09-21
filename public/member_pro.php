<?php
// Standalone PHP: set the live MySQL credentials here; no db.php or Laravel needed.
$dbHost = 'localhost';
$dbPort = 3306;
$dbUsername = 'himrishteymain_admin';
$dbPassword = '!nnF?U0%}xi@WCup';

// Example: member_pro.php?database=gallpakki&uid=Nw== (member ID 7).
// database accepts only the four suffixes below (or their full database names).
$databases = [
    'base' => 'himrishteymain_base',
    'gallpakki' => 'himrishteymain_gallpakki',
    'devbhoomi' => 'himrishteymain_devbhoomi',
    'dogririshtey' => 'himrishteymain_dogririshtey',
];
$hostDatabases = [
    'himrishtey.com' => 'base',
    'gallpakki.com' => 'gallpakki',
    'devbhoomirishtey.com' => 'devbhoomi',
    'dogririshtey.com' => 'dogririshtey',
];
$host = strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? '')[0]);
$host = preg_replace('/^www\./', '', $host);
$database = $_GET['database'] ?? ($hostDatabases[$host] ?? null);
if (is_string($database) && str_starts_with($database, 'himrishteymain_')) {
    $database = substr($database, strlen('himrishteymain_'));
}
if (! is_string($database) || ! isset($databases[$database])) {
    http_response_code(400);
    exit('Choose a valid database: base, gallpakki, devbhoomi, or dogririshtey.');
}
$uid = $_GET['uid'] ?? '';
$sid = is_string($uid) ? base64_decode($uid, true) : false;
if ($sid === false || ! preg_match('/^[1-9][0-9]*$/D', $sid)
    || filter_var($sid, FILTER_VALIDATE_INT) === false) {
    http_response_code(400);
    exit('A valid base64-encoded member ID is required.');
}
$sid = (int) $sid;
$dbName = 'himrishteymain_'.$database;

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $con = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName, $dbPort);
    $con->set_charset('utf8mb4');
    $statement = $con->prepare("SELECT
        members.*,
        DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i %p') AS birthdatetime,
        DATE_FORMAT(members.plan_activation_date, '%d-%m-%Y') AS planactivationdate,
        membership_plans.plan_name,
        height_from.height AS partner_height_from_label,
        height_to.height AS partner_height_to_label
        FROM members
        LEFT JOIN membership_plans ON members.plan_id = membership_plans.id
        LEFT JOIN heights AS height_from ON height_from.height_value = members.partner_height_from
        LEFT JOIN heights AS height_to ON height_to.height_value = members.partner_height_to
        WHERE members.id = ? LIMIT 1");
    $statement->bind_param('i', $sid);
    $statement->execute();
    $row = $statement->get_result()->fetch_assoc();
    $statement->close();
    if (! $row) {
        http_response_code(404);
        exit('Member not found.');
    }
} catch (Throwable $exception) {
    error_log('Standalone member profile: '.$exception->getMessage());
    http_response_code(500);
    exit('Unable to load the member profile. Check the server database configuration.');
}

function profileEscape($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
    
	
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Gallpakki Rishtey</title>
		<!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=AW-305607407"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
            
              gtag('config', 'AW-305607407');
            </script>
        <!-- Icon css link -->
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="vendors/stroke-icon/style.css" rel="stylesheet">
        <link href="vendors/flat-icon/flaticon.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Rev slider css -->
        <link href="vendors/revolution/css/settings.css" rel="stylesheet">
        <link href="vendors/revolution/css/layers.css" rel="stylesheet">
        <link href="vendors/revolution/css/navigation.css" rel="stylesheet">
        <link href="vendors/animate-css/animate.css" rel="stylesheet">
        
        <!-- Extra plugin css -->
        <link href="vendors/magnify-popup/magnific-popup.css" rel="stylesheet">
        <link href="vendors/owl-carousel/owl.carousel.min.css" rel="stylesheet">
        <link href="vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <link href="vendors/bootstrap-selector/bootstrap-select.css" rel="stylesheet">
        <link href="vendors/lightbox/simpleLightbox.css" rel="stylesheet">
		
		<link rel="stylesheet" href="bundles/slimcropper/css/slim.min.css">
		
		<link rel="stylesheet" type="text/css" href="bundles/confirm/css/jquery-confirm.css" />
		
		<link rel="stylesheet" href="bundles/xdsoft/css/jquery.datetimepicker.min.css">
		<link rel="stylesheet" href="bundles/timepicker/mdtimepicker.min.css">
        
        <link href="css/style.css" rel="stylesheet">
        <link href="css/responsive.css" rel="stylesheet">
        <link href="css/tab.css" rel="stylesheet">
		<link rel="stylesheet" href="bundles/cropimage/croppie.css">
		
		<link rel="stylesheet" href="bundles/izitoast/css/iziToast.min.css">
		
		<link rel="stylesheet" href="bundles/select2/dist/css/select2.min.css">
		
		<!--link rel="stylesheet" type="text/css" href="bundles/masonry/css/default.css" /-->
		<link rel="stylesheet" type="text/css" href="bundles/masonry/css/component.css" />
		
		<link rel="stylesheet" type="text/css" href="bundles/fancybox/jquery.fancybox.min.css" />
		
		
		<link href="css/custom.css" rel="stylesheet">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap" rel="stylesheet">
		
		<link href="https://fonts.googleapis.com/css2?family=Kadwa&display=swap" rel="stylesheet">
		
		<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
		
		<script src="js/jquery-2.2.4.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="bundles/izitoast/js/iziToast.min.js"></script>
		<script type="text/javascript" src="bundles/confirm/js/jquery-confirm.js"></script>
		
		<script src="bundles/masonry/js/modernizr.custom.js"></script>
		<script src="bundles/masonry/js/masonry.pkgd.min.js"></script>
		<script src="bundles/masonry/js/imagesloaded.js"></script>
		<script src="bundles/masonry/js/classie.js"></script>
		<script src="bundles/masonry/js/AnimOnScroll.js"></script>
		
		<script src="js/custom.js"></script>
		
		<!-- Chosen -->
		
		<link rel="stylesheet" href="bundles/chosen/prism.css">
		<link rel="stylesheet" href="bundles/chosen/chosen.css">
		
		<link rel="stylesheet" href="bundles/multiselectcb/css/jquery.multiselect.css">
		
		<script src="bundles/multiselectcb/js/jquery.multiselect.js"></script>
		
		
		<script src="bundles/slimcropper/js/slim.kickstart.min.js"></script>
		
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<meta name="google-site-verification" content="c1DNmfGxS1Ed975bHyOYE2H9plxem1wEddH2Pz0Jpqc" />
	</head>
	<body>
<?php
// 	echo '<pre>'; print_r($row);die;
	$plan_name = $row['plan_name'];
	$plan_activation_date = $row['planactivationdate'];
	$profile_id = $row['profile_id'];
	$full_name = $row['full_name'];
	// $username = $row['username'];
	$email = $row['email'];
	$mobile_number = $row['mobile_number'];
	$whatsapp_number = $row['whatsapp_number'];
	$birth_date_time = $row['birthdatetime'];
	$height = $row['height'];
// 	$hnresult = mysqli_query($con, "select height from heights where height_value = $height");
// 	$hnrow = mysqli_fetch_assoc($hnresult);
// 	$height = $hnrow['height'];
	$weight = $row['weight'];
	$gender = $row['gender'];
	$birth_place = $row['birth_place'];
	$religion = $row['religion'];
	$mother_tongue = $row['mother_tongue'];
	$cast = $row['cast'];
	$sub_cast = $row['sub_cast'];
	$gotra = $row['gotra'];
	$manglik = $row['manglik'];
	$marital_status = $row['marital_status'];
	$education = $row['education'];
	$employed_in = $row['employed_in'];
	$occupation = $row['occupation'];
	$designation = $row['designation'];
	$annual_income = $row['annual_income'];
	$country_living_in = $row['country_living_in'];
	$state_living_in = $row['state_living_in'];
	$city_living_in = $row['city_living_in'];
	$address_living_in = $row['address_living_in'];
	$family_type = $row['family_type'];
	$family_status = $row['family_status'];
	$father_name = $row['father_name'];
	$mother_name = $row['mother_name'];
	$father_occupation = $row['father_occupation'];
	$mother_occupation = $row['mother_occupation'];
	$no_of_brothers = $row['no_of_brothers'];
	$no_of_sisters = $row['no_of_sisters'];
	$married_brothers = $row['married_brothers'];
	$married_sisters = $row['married_sisters'];
	$about_family = $row['about_family'];
	$about_me = $row['about_me'];
	$any_disability = $row['any_disability'];
	$referral_code = $row['referral_code'];
	$looking_for = $row['looking_for'];
	$partner_age_from = $row['partner_age_from'];
	$partner_age_to = $row['partner_age_to'];
	$partner_country = $row['partner_country'];
	$partner_religion = $row['partner_religion'];
	$partner_mothertongue = $row['partner_mothertongue'];
	$partner_cast = $row['partner_cast'];
	$partner_height_from = $row['partner_height_from_label'] ?? $row['partner_height_from'];
	$partner_height_to = $row['partner_height_to_label'] ?? $row['partner_height_to'];
	$partner_education = $row['partner_education'];
	$partner_annual_income = $row['partner_annual_income'];
	$photo = $row['photo'];
	$remarks = $row['remarks'];
	
    $age_years = '';
    $age_months = '';
    if (! empty($row['birth_date_time'])) {
        try {
            $age = (new DateTime($row['birth_date_time']))->diff(new DateTime());
            $age_years = $age->y;
            $age_months = $age->m;
        } catch (Exception $exception) {
            // Legacy records may have an invalid birth date.
        }
    }
	$any_other_qualification = $row['any_other_qualifications'];
	$about_my_career = $row['about_my_career'];
?>
<div class="container">
<div class="col-sm-6" style="margin-top:20px">
	<div class="member-profile-pic" style="width:50%">
	<?php if($photo != '')
	{ 
	?>
	<img class="w100" src="/public/photos/photo/<?php echo rawurlencode(basename($photo)); ?>" alt=""/>
	<?php 
	}
	else
	{ 
	if($gender == 'Male')
	{
	?>
	<img class="w100" src="/public/photos/photo/boy.jpg" />
	<?php 
	}
	if($gender == 'Female')
	{
	?>
	<img class="w100" src="/public/photos/photo/girl.jpg" />
	<?php
	}
	} 
	?>
	
	</div>
	</div>
	<div class="col-sm-6">
	<div class="profile-vbox">
	<span class="profile-vbox-caption"><i class="fa fa-address-card" aria-hidden="true"></i> Basic Details</span>
	<!-- <p><b>Profile ID:</b> <?php //echo $profile_id; ?><br>
	<b>Username:</b> <?php //echo $username; ?><br>
	<b>Password:</b> <?php //echo $password; ?><br> -->
	<b>Name : </b><?php echo profileEscape($full_name); ?><br>
	<b>Height:</b> <?php echo profileEscape($height); ?><br>
	<b>Gender:</b> <?php echo profileEscape($gender); ?><br>
	<b>Age:</b> <?php echo profileEscape($age_years); ?> Years <?php echo profileEscape($age_months); ?> Months<br>
	<b>City:</b><?php echo profileEscape($city_living_in); ?><br>
	
	</p>
	</div>
	</div>
	</div>
	<div class="container">
	<div class="row">
	<div class="col-sm-6">
	
	<div class="profile-vbox">
	<span class="profile-vbox-caption"><i class="fa fa-user" aria-hidden="true"></i> About Me</span>
	<p><b>About Me:</b> <?php echo profileEscape($about_me); ?><br>
	<b>Any Disability:</b> <?php echo profileEscape($any_disability); ?>
	</p>
	</div>
	
	<div class="profile-vbox">
	<span class="profile-vbox-caption"><i class="fa fa-globe" aria-hidden="true"></i> Religious Information</span>
	<p>
	<b>Religion:</b> <?php echo profileEscape($religion); ?><br>
	<b>Mother Tongue:</b> <?php echo profileEscape($mother_tongue); ?><br>
	<b>Cast: </b><?php echo profileEscape($cast); ?> (<?php echo profileEscape($sub_cast); ?>)<br>
	<b>Gotra:</b> <?php echo profileEscape($gotra); ?><br>
	<b>Manglik:</b> <?php echo profileEscape($manglik); ?><br>
	<b>Marital Status: </b><?php echo profileEscape($marital_status); ?><br>
	</p>
	</div>
	
<!-- 	<div class="profile-vbox">
	<span class="profile-vbox-caption"><i class="fa fa-map-marker" aria-hidden="true"></i> Local Information</span>
	<p>
	<b>Country: </b> <?php echo profileEscape($country_living_in); ?><br>
	<b>State: </b> <?php echo profileEscape($state_living_in); ?><br>
	<b>City: </b> <?php echo profileEscape($city_living_in); ?><br>
	<b>Address: </b> <?php echo profileEscape($address_living_in); ?><br>
	</p>
	</div> -->
	
	<div class="profile-vbox">
	<span class="profile-vbox-caption"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Education / Profession Information</span>
	<p>
	<b>Education: </b><?php echo profileEscape($education); ?><br>
	<b>Employed In:</b> <?php echo profileEscape($employed_in); ?><br>
	<b>Occupation:</b> <?php echo profileEscape($occupation); ?><br>
	<b>Designation: </b><?php echo profileEscape($designation); ?><br>
	<b>Annual Income:</b> <?php echo profileEscape($annual_income); ?><br>
	<b>Other Qualification:</b><?= profileEscape($any_other_qualification); ?><br>
	<b>About My Career:</b><?= profileEscape($about_my_career); ?>
	</p>
	</div>
	
	
	
	
	</div>
	<div class="col-sm-6">
	<div class="profile-vbox">
	<span class="profile-vbox-caption"><i class="fa fa-users" aria-hidden="true"></i> Family Detail</span>
	<p>
	<b>Family Status:</b> <?php echo profileEscape($family_status); ?><br>
	<b>Family Type:</b> <?php echo profileEscape($family_type); ?><br>
	<b>Father Name : </b><?php echo profileEscape($father_name); ?><br>
	<b>Father Occupation : </b><?php echo profileEscape($father_occupation); ?><br>
	<b>Mother Name : </b><?php echo profileEscape($mother_name); ?><br>
	<b>Mother Occupation : </b><?php echo profileEscape($mother_occupation); ?><br>
	<b>No of Brothers : </b><?php echo profileEscape($no_of_brothers); ?><br>
	<b>No of Sisters : </b><?php echo profileEscape($no_of_sisters); ?><br>
	<b>Married Brothers : </b><?php echo profileEscape($married_brothers); ?><br>
	<b>Married Sisters : </b><?php echo profileEscape($married_sisters); ?><br>
	<b>About Family : </b><?php echo profileEscape($about_family); ?>
	</p>
	</div>
	<div class="profile-vbox">
	<span class="profile-vbox-caption"><i class="fa fa-heart" aria-hidden="true"></i> Partner Preference</span>
	<p>
	<b>Looking For :</b> <?php echo profileEscape($looking_for); ?><br>
	<b>Partner Age From :</b> <?php echo profileEscape($partner_age_from); ?><br>
	<b>Partner Age To :</b> <?php echo profileEscape($partner_age_to); ?><br>
	<b>Partner Country :</b> <?php echo profileEscape($partner_country); ?><br>
	<b>Partner Religion :</b> <?php echo profileEscape($partner_religion); ?><br>
	<b>Partner Mother Tongue :</b> <?php echo profileEscape($partner_mothertongue); ?><br>
	<b>Partner Cast :</b> <?php echo profileEscape($partner_cast); ?><br>
	<b>Partner Height From :</b> <?php echo profileEscape($partner_height_from); ?><br>
	<b>Partner Height To :</b> <?php echo profileEscape($partner_height_to); ?><br>
	<b>Partner Education :</b> <?php echo profileEscape($partner_education); ?><br>
	<b>Partner Annual Income :</b> <?php echo profileEscape($partner_annual_income); ?><br>
	<b>About Me:</b> <?php echo profileEscape($about_me); ?><br>
	</p>
	</div>
	
	</div>
	</div>
	</div>

<script src="vendors/revolution/js/jquery.themepunch.tools.min.js"></script>
<script src="vendors/revolution/js/jquery.themepunch.revolution.min.js"></script>
<script src="vendors/revolution/js/extensions/revolution.extension.video.min.js"></script>
<script src="vendors/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<script src="vendors/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="vendors/revolution/js/extensions/revolution.extension.navigation.min.js"></script>

<script src="vendors/magnify-popup/jquery.magnific-popup.min.js"></script>
<script src="vendors/isotope/imagesloaded.pkgd.min.js"></script>
<script src="vendors/isotope/isotope.pkgd.min.js"></script>
<script src="vendors/counterup/waypoints.min.js"></script>
<script src="vendors/counterup/jquery.counterup.min.js"></script>
<script src="vendors/owl-carousel/owl.carousel.min.js"></script>
<script src="vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="bundles/xdsoft/js/jquery.datetimepicker.full.js"></script>
<script src="vendors/bootstrap-selector/bootstrap-select.js"></script>
<script src="vendors/lightbox/simpleLightbox.min.js"></script>

<script src="bundles/timepicker/mdtimepicker.js"></script>
<script src="bundles/cropimage/croppie.js"></script>
<script src="bundles/select2/dist/js/select2.full.min.js"></script>

<script src="js/bootstrap-tabcollapse.js"></script>

<script src="js/theme.js"></script>


<!-- Chosen -->
<script src="bundles/chosen/chosen.jquery.js" type="text/javascript"></script>
<script src="bundles/chosen/prism.js" type="text/javascript" charset="utf-8"></script>
<script src="bundles/chosen/init.js" type="text/javascript" charset="utf-8"></script>

<script src="bundles/fancybox/jquery.fancybox.min.js" type="text/javascript"></script>
</body>
</html>
