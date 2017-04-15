<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_code'] = 0;	// error_code = 0 => for all type of errors except token_mismatch
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

		if($user->isLoggedIn())
		{
			$Validate = new Validate;

			$Validate->check($_POST, array(
				'title' => array(
					'required' => true,
					'min' => 5,
					'max' => 100
					),
				'description' => array(
					'required' => true,
					'min' => 30,
					'max'=> 200
					),
				'blog'=> array(
					'required' => true,
					'min' => 500
					)
				));

			if($Validate->passed())
			{
				$total_words = str_word_count(Input::get('blog'));	// counting the total words in the blog
				$image_occurences = substr_count(Input::get('blog'), '<img');	// counting the occurence of images in blog
				
				$minutes_read = get_minutes_read($total_words, $image_occurences);	// minutes_read is an estimation of duration a user will need to read the whole blog
				
				$blog = DB::getInstance()->insert('blogs', array(
					'title' => Input::get('title'),
					'description' => Input::get('description'),
					'blog' => Input::get('blog'),
					'blog_minutes_read' => $minutes_read,
					'users_id' => $user->data()->id
					));
				$lastInsertId = DB::getInstance()->getLastInsertId();
				$tags = Input::get('blog_tags');
				foreach($tags as $tag)
				{
					DB::getInstance()->insert('blog_tags', array(
						'blog_id' => $lastInsertId,
						'tags' => $tag
					));
				}

			}
			else
			{
				$json['error_status'] = true;
				$json['error'] = $Validate->errors()[0];
			}
		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = "You need to login to write a blog";
		}

		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
	else
	{
		$json['error_code'] = 1;	// error_code = 1 => for token_mismatch error
		$json['error_status'] = true;
		$json['error'] = "Token mismatch error, try again after refreshing the page";
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
}
else
{
	Redirect::to('authors_info.php');
}

function get_minutes_read($total_words, $image_occurences)
{
	$blog_words_without_images = $total_words - $image_occurences;	// eliminating the occurence of <img> as text (it will be treated as separate image)
	$average_words_per_minute = 150;
	$duration = $blog_words_without_images / $average_words_per_minute;	// diving the total_words after eliminating <img> as text with average_words_per_minute to get integer part and decimal part
	$minutes = floor($duration);	// count the minutes by taking integral part of duration
	$fraction_part = (string)round(($duration - $minutes), 2);
	$fraction_part = str_replace('0.', '', $fraction_part);
	$seconds = intval(($fraction_part * 60) / 100);	// count the seconds by taking fractional or decimal part of duration
	$add_image_seconds = 12;	// 12 seconds is the average time a user usually spends while browsing/viewing first image
	while(($add_image_seconds > 3) && ($image_occurences != 0))
	{
		$seconds += $add_image_seconds;	// the average view duration for an image deduces by 1 second every time until average view duration for an image reduces to 3 seconds
		--$add_image_seconds;
		--$image_occurences;
	}
	if($image_occurences != 0)
	{
		$seconds += ($image_occurences * 3);	// for all the remaining images only 3 seconds will be added to the total duration
	}
	$minutes += floor($seconds/60);	// calculating the final minutes
	$seconds = $seconds%60;		// calculating the final seconds
	if($seconds > 0)	
	{
		ceil(++$minutes);		// convert remaining seconds to minutes
	}
	return $minutes;
}

?>