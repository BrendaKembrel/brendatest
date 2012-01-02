<?php
/**
 * Calculating the difference between two dates
 * @author: Elliott White
 * @author: Jonathan D Eisenhamer.
 * @link: http://www.quepublishing.com/articles/article.asp?p=664657&rl=1
 * @since: Dec 1, 2006.
 */


// Will return the number of days between the two dates passed in
function count_days( $a, $b )
{	
	$aArray = explode("-", $a);
	$bArray = explode("-", $b);
	
	$a = strtotime( $aArray[1]."/".$aArray[2]."/".$aArray[0]." 12:00am" );
	$b = strtotime( $bArray[1]."/".$bArray[2]."/".$bArray[0]." 12:00am" );
	
	if( function_exists( 'date_default_timezone_set' ) )
	{
		// Set the default timezone to US/Eastern
		date_default_timezone_set( 'US/Eastern' );
	}

    // First we need to break these dates into their constituent parts:
    $gd_a = getdate( $a );
    $gd_b = getdate( $b );

    // Now recreate these timestamps, based upon noon on each day
    // The specific time doesn't matter but it must be the same each day
    $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
    $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );

    // Subtract these two numbers and divide by the number of seconds in a
    //  day. Round the result since crossing over a daylight savings time
    //  barrier will cause this time to be off by an hour or two.
    return round( ( $a_new - $b_new ) / 86400 );
}

?>