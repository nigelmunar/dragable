<?php
	try
	{
		require_once __DIR__ . '/../application/config/config.php';
		require_once ROOT_PATH . 'application/includes/init.php';
	}	
	catch(\Exception $ex3)
	{

	}
	catch(\Throwable $ex3)
	{

	}

    http_response_code(404);
?>

<link href="/style.css?v=0.0.1" rel="stylesheet" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

<div class="error-page">

	<img src="/images/logo.png" alt="VegTrug" class="error-page__logo-img" />

	<h1 class="error-page__title">404</h1>

	<div class="error-page__text">Sorry, we are unable to find what you are looking for, the links below should help you.</div>

	<a href="/" class="error-page__link">
		Go back home
	</a>
</div>

<?php
	try
	{
		require_once ROOT_PATH . 'application/factories/LoggingDatabaseFactory.php';

		$loggedToDB = false;
	
		try
		{
			$loggingDB = \LoggingDatabaseFactory::create();
			$pdo       = \PDOFactory::getConnection();

			$requestURI = substr((isset($_SERVER['REQUEST_URI']) && !is_null($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''), 0, 1000);

			$stmt = $pdo->prepare('
				INSERT INTO `error_pages`(`request_url`, `date_time_created`)
				SELECT `new_request_url`, NOW() FROM (SELECT :request_url AS `new_request_url`) AS tbl1 WHERE NOT EXISTS(SELECT 1 FROM `error_pages` AS u WHERE `request_url` = :request_url)');
			
			$stmt->bindValue(':request_url', $requestURI, \PDO::PARAM_STR);

			$stmt->execute();

			$stmt = $pdo->prepare('
				INSERT INTO `errors`(`error_page_id`, `ip_address_id`, `user_agent_id`, `error_code`, `date_time_logged`)
				SELECT `error_page_id`, :ip_address_id, :user_agent_id, 404, NOW()
				FROM `error_pages`
				WHERE `request_url` = :request_url');

			$stmt->bindValue(':request_url',    $requestURI,                    \PDO::PARAM_STR);
			$stmt->bindValue(':ip_address_id',  $loggingDB->getIpAddressID(),   \PDO::PARAM_INT);
			$stmt->bindValue(':user_agent_id',  $loggingDB->getUserAgentID(),   \PDO::PARAM_INT);

			$stmt->execute();

			$loggedToDB = true;
		}
		catch(\Exception $ex)
		{

		}
		catch(\Throwable $ex)
		{

		}
		
		if(!$loggedToDB)
		{
			try
			{
				//Log to file instead
				require_once ROOT_PATH . 'application/logging/FileLogger.php';

				if(!file_exists(ROOT_PATH . 'application/logs/'))
				{
					mkdir(ROOT_PATH . 'application/logs/');
				}

				$fileLogger = new \FileLogger(ROOT_PATH . 'application/logs/404-log', 5);
				$fileLogger->log($requestURI, false);
			}
			catch(\Exception $ex)
			{

			}
			catch(\Throwable $ex)
			{

			}
		}
	}
	catch(\Exception $ex3)
	{

	}
	catch(\Throwable $ex3)
	{

	}

    exit();