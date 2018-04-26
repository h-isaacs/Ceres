<?php
	require 'sessionCookieHandlerLib.php';
	require 'queryLib.php';
	require 'userCredentialsValidationLib.php';
	startSession();
?>
<!DOCTYPE html>
<html>
	<head>
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
				callPost("addComment.php", "recipe=" + recipeID + "&comment=" + text)
				location.reload();
			}

		</script>
		<?php
    $recipe = htmlspecialchars($_GET["recipe"]);

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

    echo "<h1>".$recipeName." - serves ".$servings."</h1>\n";
    echo "<p>Uploaded by ".$username." on ".$dateUploaded."</p>\n";
    echo "<p>Views: ".$recipeViews." Uses: ".$uses."</p>\n";
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

    echo "<b>".$totalCalories." calories per serving</b>\n<br><br>\n";

    echo "<h3>Steps:</h3>\n<p style='white-space: pre-line'>".$steps."</p>\n";

		if (isset($_SESSION["userid"])) {
			echo "<b>Leave a review:</b>\n<br>\n";
			echo "<textarea id='txtCommentArea' placeholder='Comment' cols='50' rows='3'></textarea>\n<br>\n";
			echo "<button onclick='submitComment()'>Submit</button>\n<br>\n";
		}

		$stmt = $pdo->query("select userID, reviewText, dateTimePosted from reviews where recipeID=".$recipe.";");
		foreach ($stmt as $row) {
			$userID = $row["userID"];
			$text = $row["reviewText"];
			$datePosted = $row["dateTimePosted"];
			$username = $pdo->query("select displayName from users where userID=".$userID.";");
			$username = $username->fetch()["displayName"];
			echo "<p><b>".$username." </b> at ".$datePosted."</p>\n<p>".$text."</p>\n<br>\n";
		}

		?>
  </body>
</html>
