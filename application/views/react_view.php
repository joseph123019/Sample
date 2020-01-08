<!DOCTYPE html>
<html>
  	<head>
	  	<meta charset="UTF-8">
	    <title>Sample</title>
	    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css" />
	    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
	  	<script type="text/javascript">
		    var baseURL = "<?php echo base_url(); ?>";
		</script>
  	</head>
	<body data-ng-controller="root">
		<div id="root"></div>
		<script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script>
		<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script>
		<script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>
		<script type="text/babel" src="assets/js/like_button.js"></script>
	    <script src="<?php echo base_url(); ?>assets/dist/opensourcepos.min.js"></script>
	</body>
</html>