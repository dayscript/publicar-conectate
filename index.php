<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php
        $rest = substr($_SERVER['HTTP_HOST'], 0,6);
        switch ($rest) {
            case 'sumate':
                $variable = 2;
                ?>
    <title>Sumate Publicar</title>

	<style>
		html{
			width: 100%;
			height: 100%;
		}
		body {
		  background-image: url(sumat.jpg);
		  background-position: center center;
		  background-repeat: no-repeat;
		  background-attachment: fixed;
		  background-size: cover;
		  background-color: #464646;
		}
	@media all and (orientation: portrait){
		body {
		  background-image: url(WEBM.png);
		  background-position: center center;
		  background-repeat: no-repeat;
		  background-attachment: fixed;
		  background-size: cover;
		  background-color: #464646;
		}
	}
	</style>
                <?php
            break;
            case 'conect':
                $variable = 1;
                ?>
    <title>Conectate Publicar</title>

	<style>
		html{
			width: 100%;
			height: 100%;
		}
		body {
		  background-image: url(conect.jpg);
		  background-position: center center;
		  background-repeat: no-repeat;
		  background-attachment: fixed;
		  background-size: cover;
		  background-color: #464646;
		}
	@media all and (orientation: portrait){
		body {
		  background-image: url(WEBM.png);
		  background-position: center center;
		  background-repeat: no-repeat;
		  background-attachment: fixed;
		  background-size: cover;
		  background-color: #464646;
		}
	}
	</style>
                <?php
            break;
        }
    ?>
	
</head>
<body>
</body>
</html>