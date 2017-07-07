<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <title>Lingo</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- link rel="stylesheet" href="css/bootstrap.bootswatch.css" -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/lingo.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-12" style="margin-top: 50px;" id="menu">
    <div class="frame">
        <h1>Lingo</h1>
        <form class="form" action="javascript:submitForm();">
            <div class="form-group">
                <h5><?php echo L::menu_language; ?></h5>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="language" value="nl" checked> <?php echo L::menu_dutch; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="language" value="en"> <?php echo L::menu_english; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="language" value="de"> <?php echo L::menu_german; ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <h5><?php echo L::menu_letters; ?></h5>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="letters" value="5"> <?php echo L::menu_letters5; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="letters" value="6" checked> <?php echo L::menu_letters6; ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <h5><?php echo L::menu_aidLetters; ?></h5>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="aidLetters" value="1"> <?php echo L::menu_aidLetters1; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="aidLetters" value="2" checked> <?php echo L::menu_aidLetters2; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="first" checked> <?php echo L::menu_aidFirst; ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <h5><?php echo L::menu_time; ?></h5>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="5"> <?php echo L::menu_sec5; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="15" checked> <?php echo L::menu_sec15; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="30"> <?php echo L::menu_sec30; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="60"> <?php echo L::menu_min1; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="0"> <?php echo L::menu_unlimited; ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="voice"> <?php echo L::menu_voice; ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg"><?php echo L::menu_start; ?></button>
            </div>
        </form>
    </div>
</div>
<div id="game" style="margin: auto; display: none">
    <table class="lingo" style="margin: auto">
        <tbody>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
        </tbody>
    </table>
    <div class="lingo-progress" style="margin: auto">
        <div class="lingo-progress-bar"></div>
    </div>
</div>
<div id="overlay">
    <span>
        <span class="lingo-end-message">The right word was:</span><br>
        <span class="lingo-right"></span><br>
        <button class="btn btn-primary btn-lg" onclick="showMenu();"><?php echo L::overlay_menu; ?></button> <button class="btn btn-primary btn-lg" onclick="submitForm();"><?php echo L::overlay_next; ?></button>
    </span>
</div>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/jquery.color-animation.js"></script>
<script src="js/tether.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/annyang.js"></script>
<script src="js/lingo.js"></script>
<script>
    function submitForm() {
        Lingo.language = $("input[name=language]:checked").val();
        Lingo.time = $("input[name=time]:checked").val();
        Lingo.letters = $("input[name=letters]:checked").val();
        if($("input[name=voice]").is(":checked")) {
            Lingo.activateVoice();
        }
        $.post("play/init", {amount: $("input[name=aidLetters]:checked").val(), first: $("input[name=first]").is(":checked"), language: Lingo.language, letters: Lingo.letters}, function (data) {
            Lingo.rightLetters = JSON.parse(data);
            $("#menu").hide();
            $("#overlay").hide();
            $("#game").show();
            Lingo.init();
        });
    }

    function showMenu() {
        $("#menu").show();
        $("#overlay").hide();
        $("#game").hide();
    }
</script>
</body>
</html>