<?php 

// Includes
require_once('Config.php');
require_once('CVKAPI.php');
require_once('Tools.php');

// Init VK API Class
$UserVK = new CVKAPI($UserAccessToken);

// Init request
$input = file_get_contents('php://input');
$update = json_decode($input);

// Wrong request
if (!isset($update)) 
	die('No update set.');

// Init WhiteList File to Array
$WhiteListWords = file_get_contents('WhiteListWords.txt');
$WhiteListWords = explode("\n", $WhiteListWords);

// Check WhiteList words
for ($i = 0; $i < sizeof($WhiteListWords); $i++) 
{ 
	if (!$WhiteListWords[$i] || empty($WhiteListWords[$i]) || strlen($WhiteListWords[$i]) < 3) 
		continue;

	if (StrContains($update->object->text, $WhiteListWords[$i])) 
	{
		file_put_contents('WhiteListLog.txt', '---------- ['.date("Y-m-d H:i:s").'] WhiteListed ----------'.PHP_EOL.''.$update->object->text.''.PHP_EOL.''.PHP_EOL.'', FILE_APPEND);
		die('ok');
	}
}

// Init BlackList File to Array
$BlackListWords = file_get_contents('BlackListWords.txt');
$BlackListWords = explode("\n", $BlackListWords);

// Check callback
switch ($update->type) 
{
	case 'confirmation':
		//print_r($GroupConfirmCode);
		die($GroupConfirmCode);
		
		break;

	case 'wall_reply_new':
	case 'wall_reply_edit':
		for ($i = 0; $i < sizeof($BlackListWords); $i++) 
		{ 
			if (!$BlackListWords[$i] || empty($BlackListWords[$i]) || strlen($BlackListWords[$i]) < 3) 
				continue;

			if (StrContains($update->object->text, $BlackListWords[$i])) 
			{
				$UserVK->Call('wall.deleteComment', ['owner_id' => $update->object->post_owner_id, 'comment_id' => $update->object->id]);
				file_put_contents('BlackListLog.txt', '---------- ['.date("Y-m-d H:i:s").'] wall.deleteComment ----------'.PHP_EOL.''.$update->object->text.''.PHP_EOL.''.PHP_EOL.'', FILE_APPEND);
				break;
			}
		}

		break;

	case 'market_comment_new':
	case 'market_comment_edit':
		for ($i = 0; $i < sizeof($BlackListWords); $i++) 
		{ 
			if (!$BlackListWords[$i] || empty($BlackListWords[$i]) || strlen($BlackListWords[$i]) < 3) 
				continue;

			if (StrContains($update->object->text, $BlackListWords[$i])) 
			{
				$UserVK->Call('market.deleteComment', ['owner_id' => $update->object->market_owner_id, 'comment_id' => $update->object->id]);
				file_put_contents('BlackListLog.txt', '---------- ['.date("Y-m-d H:i:s").'] market.deleteComment ----------'.PHP_EOL.''.$update->object->text.''.PHP_EOL.''.PHP_EOL.'', FILE_APPEND);
				break;
			}
		}

		break;

	case 'board_post_new':
	case 'board_post_edit':
		for ($i = 0; $i < sizeof($BlackListWords); $i++) 
		{ 
			if (!$BlackListWords[$i] || empty($BlackListWords[$i]) || strlen($BlackListWords[$i]) < 3) 
				continue;

			if (StrContains($update->object->text, $BlackListWords[$i])) 
			{
				$UserVK->Call('board.deleteComment', ['group_id' => $update->group_id, 'topic_id' => $update->object->topic_id, 'comment_id' => $update->object->id]);
				file_put_contents('BlackListLog.txt', '---------- ['.date("Y-m-d H:i:s").'] board.deleteComment ----------'.PHP_EOL.''.$update->object->text.''.PHP_EOL.''.PHP_EOL.'', FILE_APPEND);
				break;
			}
		}

		break;
		
	case 'photo_comment_new':
	case 'photo_comment_edit':
		for ($i = 0; $i < sizeof($BlackListWords); $i++) 
		{ 
			if (!$BlackListWords[$i] || empty($BlackListWords[$i]) || strlen($BlackListWords[$i]) < 3) 
				continue;

			if (StrContains($update->object->text, $BlackListWords[$i])) 
			{
				$UserVK->Call('photos.deleteComment', ['owner_id' => $update->object->photo_owner_id, 'comment_id' => $update->object->id]);
				file_put_contents('BlackListLog.txt', '---------- ['.date("Y-m-d H:i:s").'] photos.deleteComment ----------'.PHP_EOL.''.$update->object->text.''.PHP_EOL.''.PHP_EOL.'', FILE_APPEND);
				break;
			}
		}

		break;
		
	case 'video_comment_new':
	case 'video_comment_edit':
		for ($i = 0; $i < sizeof($BlackListWords); $i++) 
		{ 
			if (!$BlackListWords[$i] || empty($BlackListWords[$i]) || strlen($BlackListWords[$i]) < 3) 
				continue;

			if (StrContains($update->object->text, $BlackListWords[$i])) 
			{
				$UserVK->Call('video.deleteComment', ['owner_id' => $update->object->video_owner_id, 'comment_id' => $update->object->id]);
				file_put_contents('BlackListLog.txt', '---------- ['.date("Y-m-d H:i:s").'] video.deleteComment ----------'.PHP_EOL.''.$update->object->text.''.PHP_EOL.''.PHP_EOL.'', FILE_APPEND);
				break;
			}
		}

		break;

}

// Exit
die('ok');

?>
