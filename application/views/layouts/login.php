<!DOCTYPE html>
<html lang="<?=lang('lang_code')?>">
	<head>
		<meta charset="utf-8">
		
		<!-- Favicons -->
		<?php $favicon = config_item('site_favicon'); $ext = substr($favicon, -4); ?>
		<?php if ( $ext == '.ico') : ?>
		<link rel="shortcut icon" href="<?=base_url()?>assets/images/<?=config_item('site_favicon')?>">
		<?php endif; ?>
		<?php if ($ext == '.png') : ?>
		<link rel="icon" type="image/png" href="<?=base_url()?>assets/images/<?=config_item('site_favicon')?>">
		<?php endif; ?>
		<?php if ($ext == '.jpg' || $ext == 'jpeg') : ?>
		<link rel="icon" type="image/jpeg" href="<?=base_url()?>assets/images/<?=config_item('site_favicon')?>">
		<?php endif; ?>
		<!-- /Favicons -->
		
		<!-- Apple Icons -->
		<?php if (config_item('site_appleicon') != '') : ?>
		<link rel="apple-touch-icon" href="<?=base_url()?>assets/images/<?=config_item('site_appleicon')?>">
		<link rel="apple-touch-icon" sizes="72x72" href="<?=base_url()?>assets/images/<?=config_item('site_appleicon')?>">
		<link rel="apple-touch-icon" sizes="114x114" href="<?=base_url()?>assets/images/<?=config_item('site_appleicon')?>">
		<link rel="apple-touch-icon" sizes="144x144" href="<?=base_url()?>assets/images/<?=config_item('site_appleicon')?>">
		<?php endif; ?>
		<!-- Apple Icons -->
		
		<title><?php echo $template['title'];?></title>
		<meta name="description" content="<?=config_item('site_desc')?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="<?=base_url()?>assets/plugins/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?=base_url()?>assets/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/select2.min.css">
		<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/select2-bootstrap.min.css">
		<link rel="stylesheet" href="<?=base_url()?>assets/css/style.css">
		<link rel="stylesheet" href="<?=base_url()?>assets/css/app.css">
		<?php
		$family = 'Lato';
		$font = config_item('system_font');
		switch ($font) {
			case "open_sans": $family="Open Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,latin-ext,greek-ext,cyrillic-ext' rel='stylesheet'>"; break;
			case "open_sans_condensed": $family="Open Sans Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet'>"; break;
			case "roboto": $family="Roboto";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet'>"; break;
			case "roboto_condensed": $family="Roboto Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet'>"; break;
			case "ubuntu": $family="Ubuntu";  echo "<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet'>"; break;
			case "lato": $family="Lato";  echo "<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700&subset=latin,latin-ext' rel='stylesheet'>"; break;
			case "oxygen": $family="Oxygen";  echo "<link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700&subset=latin,latin-ext' rel='stylesheet'>"; break;
			case "pt_sans": $family="PT Sans";  echo "<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet'>"; break;
			case "source_sans": $family="Source Sans Pro";  echo "<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet'>"; break;
			case "montserrat": $family="Montserrat";  echo "<link href='https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet'>"; break;
			case "fira_sans": $family="Fira Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700' rel='stylesheet'>"; break;
			case "circularstd": $family="CircularStd"; break;
		}
        ?>
		<style>
				body { font-family: '<?=$family?>'; }
		</style>         
		
		
	</head>
	<body class="theme-<?=config_item('top_bar_color')?> account-page">

		<div class="main-wrapper">
			<!-- Main Content -->
			<?php  echo $template['body'];?>
			<!-- /Main Content -->
		</div>

		<script>
		var base_url = '<?php echo base_url();?>';
		</script>
		<script src="<?php echo base_url(); ?>assets/js/jquery-3.2.1.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
	    <script src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/app.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/libs/toastr.min.js"></script>
        <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
		</script>

		<?php if ($this->session->flashdata('tokbox_success')  != ''){ ?>
		<script>
			toastr.success("<?php echo $this->session->flashdata('tokbox_success'); ?>");
		</script>

		<?php } ?>

		<?php if ($this->session->flashdata('tokbox_error')  != ''){ ?>
		<script>
			toastr.error("<?php echo $this->session->flashdata('tokbox_error'); ?>");
		</script>
		<?php } ?>

        <script>
		$(document).ready(function(){
			$(".dropdown-toggle").click(function(){
				$(".dropdown-menu").toggle();
			});
		});
        </script>
	</body>
</html>
