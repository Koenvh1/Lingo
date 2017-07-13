<?php include "header.php"; ?>
<body>
<div class="menu-container">
    <div class="menu-header">
        <div class="row menu-row">
            <div class="col-4 text-left">
                <a class="btn btn-secondary" href="start">Menu</a>
            </div>
            <div class="col-4 text-center">
                <img src="img/logo.svg" alt="Logo" style="height: 40px; width: auto">
            </div>
            <div class="col-4 text-right">
                <button class="btn btn-secondary">A</button>
            </div>
        </div>
        <div class="whitespace"></div>
        <div class="row menu-row">
            <button class="btn btn-primary btn-block menu-new-game" data-toggle="modal" data-target="#newGame">Nieuw spel</button>
        </div>
    </div>
    <div class="row menu-row" id="content">
        <div>
            <h6 class="text-primary menu-title">Jouw beurt</h6>
        </div>
        <div class="menu-opponent">
            <img class="rounded-circle" src="https://placehold.it/50x50">
            <label>Hans de Jong</label>
        </div>
        <div class="menu-opponent">
            <img class="rounded-circle" src="https://placehold.it/50x50">
            <label>Peter Gerards</label>
        </div>
        <div>
            <h6 class="text-primary menu-title">Beurt tegenstander</h6>
        </div>
        <div class="menu-opponent">
            <img class="rounded-circle" src="https://placehold.it/50x50">
            <label>Hans de Jong</label>
        </div>
        <div class="menu-opponent">
            <img class="rounded-circle" src="https://placehold.it/50x50">
            <label>Peter Gerards</label>
        </div>
    </div>
    <div class="whitespace"></div>
</div>

<div class="modal fade" id="newGame" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nieuw spel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h5>Taal:</h5>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="language" value="nl" checked> Nederlands
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="language" value="en"> Engels
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="language" value="de"> Duits
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <h5>Letters:</h5>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="letters" value="5"> 5 letters
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="letters" value="6" checked> 6 letters
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <h5>Hulpletters:</h5>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="aidLetters" value="1"> 1 hulpletter
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="aidLetters" value="2" checked> 2 hulpletters
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="first" checked> Eerste letter altijd gegeven
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <h5>Tijd:</h5>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="time" value="5"> 5 sec
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="time" value="15" checked> 15 sec
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="time" value="30"> 30 sec
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="time" value="60"> 1 min
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="time" value="0"> onbeperkt
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="voice"> Spraakherkenning activeren (experimenteel)
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Start</button>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.2.1.js"></script>
<script src="js/jquery.color-animation.js"></script>
<script src="js/tether.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/annyang.js"></script>
<script src="js/lingo.js"></script>
<script>

</script>
</body>
</html>