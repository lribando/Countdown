<?php

        //Leave all this stuff as it is
        date_default_timezone_set('America/New_York');
        include 'GIFEncoder.class.php';
        include 'php52-fix.php';
        $time = $_GET['time'];
        $future_date = new DateTime(date('r',strtotime($time)));
        $time_now = time();
        $now = new DateTime(date('r', $time_now));
        $frames = array();
        $delays = array();

        $image = imagecreatefrompng('images/city_countdown_halloween_ends_in.png');
        $delay = 100;// milliseconds
        $font = array(
                'size'=>25, // Font size, in pts usually.
                'angle'=>0, // Angle of the text
                'x-offset'=>265, // The larger the number the further the distance from the left hand side, 0 to align to the left.
                'y-offset'=>50, // The vertical alignment, trial and error between 20 and 60.
                'file'=>'./Neutra2Text-Book.otf', // Font path
                'color'=>imagecolorallocate($image, 255, 255, 255), // RGB Colour of the text
        );
        for($i = 0; $i <= 60; $i++){

                $interval = date_diff($future_date, $now);

                if($future_date < $now){
                        // Open the first source image and add the text.
                        $image = imagecreatefromgif('images/city_countdown_halloween_endstate.gif');
                        ;
                        $text = $interval->format('');
                        $font = array('size'=>18, 'angle'=>0,'x-offset'=>7,'y-offset'=>30,'file'=>'./GeorgiaItalic.ttf','color'=>imagecolorallocate(221, 156, 36),);
                        imagettftext ($image, $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
                        ob_start();
                        imagegif($image);
                        $frames[]=ob_get_contents();
                        $delays[]=$delay;
                        $loops = 1;
                        ob_end_clean();
                        break;
                } else {
                        $image = imagecreatefrompng('images/city_countdown_halloween_ends_in.png');
                        ;
                        $text = $interval->format('%h : %I : %S');
                        imagettftext ($image, $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
                        ob_start();
                        imagegif($image);
                        $frames[]=ob_get_contents();
                        $delays[]=$delay;
                        $loops = 0;
                        ob_end_clean();
                }

                $now->modify('+1 second');
        }


	//expire this image instantly
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
	$gif = new AnimatedGif($frames,$delays,$loops);
	$gif->display();
