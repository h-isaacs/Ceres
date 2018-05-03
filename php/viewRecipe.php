<?php
	require 'sessionCookieHandlerLib.php';
	require 'queryLib.php';
	require 'userCredentialsValidationLib.php';
	startSession();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/Reset.css">
		<link rel="stylesheet" type="text/css" href="../css/MainStyle.css">
  </head>
		<form action="homepage.php">
			<input type="submit" value="Return">
		</form>
		<script src="../js/phpFunctions.js"></script>
	  <script src="../js/searchRecipes.js"></script>
		<script>
			function submitComment() {
				var text = document.getElementById("txtCommentArea").value;
				var recipeID = $_GET["recipe"];
				var result = callPost("addComment.php", "recipe=" + recipeID + "&comment=" + text);
				console.log(result);
				location.reload();
			}

			window.onload = loadGet;

		</script>

		<?php
		//$teststmt = 'SELECT protein, fat, sugar, fiber, carbohydrates FROM ingredients WHERE ';
		//need to execute this for each nutritional factor of each ingredient and each ingredient of the recipe
		$teststmt = 'SELECT protein, weight FROM ingredients JOIN recipe_ingredients where ingredientID=ingredientID AND recipeID='.$recipeID.';';
		$teststmt = 'SELECT fat, weight FROM ingredients JOIN recipe_ingredients where ingredientID=ingredientID AND recipeID='.$recipeID.';';
		$teststmt = 'SELECT sugar, weight FROM ingredients JOIN recipe_ingredients where ingredientID=ingredientID AND recipeID='.$recipeID.';';
		$teststmt = 'SELECT fiber, weight FROM ingredients JOIN recipe_ingredients where ingredientID=ingredientID AND recipeID='.$recipeID.';';
		$teststmt = 'SELECT carbohydrates, weight FROM ingredients JOIN recipe_ingredients where ingredientID=ingredientID AND recipeID='.$recipeID.';';


    $recipe = htmlspecialchars ($_GET["recipe"]);

    include 'dbconn.php';

    $stmt = $pdo->query("select userID, recipeName, dateUploaded, pictureURL, steps, uses, recipeViews, servings from recipes where recipeID=".$recipe.";");

    $row = $stmt->fetch();

    $userID = $row["userID"];
    $recipeName = $row["recipeName"];
    $dateUploaded = $row["dateUploaded"];
		$pictureURL = $row["pictureURL"];
    $steps = $row["steps"];
    $uses = $row["uses"];
    $recipeViews = $row["recipeViews"];
    $servings = $row["servings"];
    $totalCalories = 0;

    echo "<title>".$recipeName."</title>\n";
    echo "<body>\n";

    $recipeViews += 1;

    $stmt = $pdo->query("select displayName from users where userID=".$userID.";");
    $username = $stmt->fetch()["displayName"];

    $stmt = $pdo->query("select displayName from users where userID=".$userID.";");

    $stmt = $pdo->query("update recipes set recipeViews=".$recipeViews." where recipeID=".$recipe.";");

    echo "<h2>".$recipeName." - serves ".$servings."</h2>\n";
    echo "<p>Uploaded by <a href='accountPage.php?user=".$userID."'>".$username."</a> on ".$dateUploaded."</p>\n";
    echo "<p>Views: ".$recipeViews."</p>\n";
		echo "<img src=".$pictureURL." width='600' height='auto'></img>";
    echo "<h3>Ingredients:</h3>\n";
    $stmt = $pdo->query("select ingredientID, weight from recipe_ingredients where recipeID=".$recipe.";");
    foreach ($stmt as $row) {
      $ingredient = $pdo->query("select ingredientName, calories from ingredients where ingredientID=".$row["ingredientID"].";");
      $ingredient = $ingredient->fetch();
      $ingName = $ingredient["ingredientName"];
      $calories = $ingredient["calories"];
      $weight = $row["weight"];
      $totalCalories += ($calories * $weight);
      echo "<p>".$ingName." - ".$weight."g</p>\n";
    }

    $totalCalories /= $servings;

    echo "<b>".floor($totalCalories)." calories per serving</b>\n<br><br>\n";

    echo "<h3>Steps:</h3>\n<p style='white-space: pre-line'>".$steps."</p>\n";

		if (isset($_SESSION["userid"])) {
			echo "<br>\n<b>Leave a review:</b>\n<br>\n";
			echo "<textarea id='txtCommentArea' placeholder='Comment' cols='50' rows='3'></textarea>\n<br>\n";
			echo "<button onclick='submitComment()'>Submit</button>\n<br>\n";
		}

		echo "<h3>Comments:</h3>\n";

		$stmt = $pdo->query("select userID, reviewText, dateTimePosted from reviews where recipeID=".$recipe.";");
		foreach ($stmt as $row) {
			$userID = $row["userID"];
			$text = $row["reviewText"];
			$datePosted = $row["dateTimePosted"];
			$username = $pdo->query("select displayName from users where userID=".$userID.";");
			$username = $username->fetch()["displayName"];
			echo "<p><a href='accountPage.php?user=".$userID."'><b>".$username." </b></a> at ".$datePosted."</p>\n<p>".$text."</p>\n<br>\n";
		}

		?>
  </body>
</html>
