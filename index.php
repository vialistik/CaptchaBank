<?php
use App\Captcha\Captcha;
require 'app/Captcha/Captcha.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Captcha</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>


<main class="captcha">




    <?php
    $numbers    = new Captcha();
    $question   = $numbers->question(rand(2, 3));
    foreach($numbers->nbShuffle() as $numbShow): ?>

        <button type="button" onClick="ident_addNumber(<?= $numbShow; ?>, '<?= $numbers->crypt($numbShow); ?>')"><?= $numbShow; ?></button>

    <?php endforeach; ?>

    <br><br>
    <form name="captcha" action="" method="post">

        <label for="question"><?= $question; ?></label>
        <br><br>
        <div id="answer" class="captcha_answer"></div>
        <input type="hidden" id="answer_value" name="answer_value" value="">

        <input type="hidden" name="question" value="<?= $question; ?>">

        <br><br>

         <input type="hidden" name="token" id="token" value="<?= uniqid(rand(), true); ?>">
         <input type="hidden" name="token_time" id="token_time" value="<?= time(); ?>">

        <button type="submit">Envoyer</button>
        <button id="answer_cancel" onclick="ident_reset();" type="button">Effacer</button>

    </form>



    <?php
        $captcha    = new Captcha();
        if($captcha->verify() === true){
            echo "ok";
        }
    ?>



</main>



<script type="text/javascript" src="js/jquery-3.1.1.slim.min.js"></script>
<script type="text/javascript" src="js/captcha.js"></script>




</body>
</html>
