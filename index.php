<?
	include("prepend.php");

	class Post{
		public $randomID        = "";
		public $nextID          = "";
		public $previousID      = "";
		public $title           = "";
		public $text            = "";
		public $shouldUpdateURL = false;
		public $newURL          = "";

		public function getLastPost($conn){
			//Get last post & number
			$getLastPostQuery = "SELECT * FROM Posts ORDER BY id DESC LIMIT 1";

			$getLastPostResult = $conn->prepare($getLastPostQuery);
			$getLastPostResult->execute();
			$post = $getLastPostResult->fetchAll(PDO::FETCH_ASSOC);

			$this->title = $post[0]['title'];
			$this->text = $post[0]['text'];

			$this->randomID = rand(1,$post[0]['id'] - 1);

			//Is there a post before this?
			if ($post[0]['id'] > 1){
				$this->previousID = $post[0]['id'] - 1;
			}
			else{
				$this->previousID = $post[0]['id'];
			}

			$this->shouldUpdateURL = true;
			$this->newURL = "moregrey.com/".$post[0]['id'];
		}

		public function getSpecificPost($conn, $postID){

			//Get last fact & number
			$getPostQuery = "SELECT * FROM Posts WHERE id = :id";

			$getLastPostResult = $conn->prepare($getPostQuery);
			$getLastPostResult->bindParam(":id", $_GET['number']);
			$getLastPostResult->execute();

			$post = $getLastPostResult->fetchAll(PDO::FETCH_ASSOC);

			$this->title = $post[0]['title'];
			$this->text = $post[0]['text'];

			//Is there a post before this?
			if ($post[0]['id'] > 1){
				$this->previousID = $post[0]['id'] - 1;
			}
			else{
				$this->previousID = $post[0]['id'];
			}


			$this->nextID = $post[0]['id'] + 1;
			
			$amountOfRecords = $this->howManyPosts($conn);
			$this->randomID = rand(1, $amountOfRecords[0]);
		}

		public function howManyPosts($conn){
			$query = "SELECT COUNT(*) FROM Posts";
			$howManyRecords = $conn->prepare($query);
			$howManyRecords->execute();
			return $howManyRecords->fetch(PDO::FETCH_NUM);
		}

		public function createTweetText(){
			$text = substr($this->text, 0, 95);
			$htmlSafeText = addslashes($text);
			$htmlSafeText.="...";
			print('"'.$htmlSafeText.'"');
		}
	}

	$post = new POST();

	

	//Are we linking to a specific fact?
	if (isset($_GET['number'])){

		//Check that we're in range
		$amountOfRecords = $post->howManyPosts($conn);

		//Are we in range?
		if ($_GET['number']>$amountOfRecords[0] || $_GET['number'] < 1){
			//Nope, just load the last one
			$post->getLastPost($conn);
		}
		else{
			//Ya we are, so lets load that specific post
			$post->getSpecificPost($conn,$_GET['number']);
		}

		
	}

	//No, load the latest
	else{
		//Get last fact & number
		$post->getLastPost($conn);
	}


	

?>

<!doctype html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<title>More Grey - Learn about the world</title>

	<link rel="stylesheet" href="main.css">

	<script src="js/jquery.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

	<meta name="viewport" content="width=device-width; initial-scale=1.0">

	<!-- ****** faviconit.com favicons ****** -->
		<link rel="shortcut icon" href="favicon/favicon.ico">
		<link rel="icon" sizes="16x16 32x32 64x64" href="/favicon.ico">
		<link rel="icon" type="image/png" sizes="196x196" href="/favicon-196.png">
		<link rel="icon" type="image/png" sizes="160x160" href="/favicon-160.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96.png">
		<link rel="icon" type="image/png" sizes="64x64" href="/favicon-64.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/favicon-152.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/favicon-144.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/favicon-120.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/favicon-114.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/favicon-76.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/favicon-72.png">
		<link rel="apple-touch-icon" href="/favicon-57.png">
		<meta name="msapplication-TileColor" content="#FFFFFF">
		<meta name="msapplication-TileImage" content="/favicon-144.png">
		<meta name="msapplication-config" content="/browserconfig.xml">
	<!-- ****** faviconit.com favicons ****** -->

</head>
<body>

	<div class="container">

		<a href="/"><div class="title"><!-- Makes Me Smarter --></div></a>

		<div class="buttons">
			<a href= <? echo('"/'.$post->previousID.'"'); ?> >Previous</a>
			<a href= <? echo('"/'.$post->randomID.'"'); ?> >Random </a>
			<a href= <? echo('"/'.$post->nextID.'"'); ?> >Next </a>
		</div>

		<div class="content">
			<div class="contentTitle">
				<? echo($post->title) ?>
			</div>
			<div class="contentBody">
				<? echo($post->text); ?>
			</div>

			<a href="https://twitter.com/share" id="tweet" class="twitter-share-button" data-lang="en" data-text="Here is some text!" data-via="MoreGreyFacts" <? if ($post->shouldUpdateURL){echo("data-url='http://".$post->newURL."'");} ?> >Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

		</div>
		
	</div> 


	<script type="text/javascript">
	  var tweet = document.getElementById("tweet"); 
	  tweet.setAttribute("data-text", <? $post->createTweetText() ?>); 
	</script>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-51070207-1', 'moregrey.com');
	  ga('send', 'pageview');

	</script>
	

</body>
</html>











