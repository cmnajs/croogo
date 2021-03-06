<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title_for_layout; ?></title>
    <?php
        echo $javascript->codeBlock("var baseUrl = '" . $html->url('/') . "';");
        echo $javascript->codeBlock("var params = " . $javascript->object($this->params) . ";");
        echo $html->css(array(
                         'reset',
                         '960',
                         '/ui-themes/smoothness/jquery-ui-1.7.css',
                         'admin'
                        ));
        echo $javascript->link(array(
                                'default',
                                'jquery/jquery-1.3.1.min.js',
                                'jquery/jquery-ui-1.7.min',
                                'jquery/jquery.uuid',
                                'jquery/jquery.cookie',
                                'jquery/jquery.collapsor',
                                //'tinymce/tiny_mce',
                                'admin'
                               ));
        echo $scripts_for_layout;
    ?>
</head>

<body>

	<div id="wrapper">
		<div id="header">
			<div class="container_16">
				<div class="grid_8">
					<div id="logo">
                        <?php echo $this->element('admin/logo'); ?>
                    </div>
				</div>
				<div class="grid_8">
					<?php echo $this->element('admin/quick'); ?>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
		</div>
		
		<div id="main" class="container_16">
			<div class="grid_16">
				<div id="content">
					<?php
						$messages = $session->read('Message'); 
						if (is_array($messages)) {
							foreach (array_keys($messages) AS $key) {
								$session->flash($key);
							}
						}
						
						echo $content_for_layout; 
					?>
				</div>
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		
		<?php echo $this->element('admin/footer'); ?>

	</div>

	
	</body>
</html>