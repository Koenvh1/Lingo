<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{ BASE_URL }}">
    <meta charset="UTF-8">
    <title>Lingo</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/lingo.css">
</head>
<body>
{{ $_SESSION["word"] }}
<div>
    <table class="lingo" style="margin: auto">
        <tr class="lingo-current">
            @for($i = 0; $i < $letters; $i++)
                <td>
                    <div class="lingo-letter">
                        <div contenteditable="false" tabindex="0">
                            @if($aidLetters[$i] != null)
                                {{ $aidLetters[$i] }}
                            @else
                                .
                            @endif
                        </div>
                    </div>
                </td>
            @endfor
        </tr>
        @for($j = 0; $j < 4; $j++)
            <tr>
                @for($k = 0; $k < $letters; $k++)
                    <td>
                        <div class="lingo-letter">
                            <div></div>
                        </div>
                    </td>
                @endfor
            </tr>
        @endfor
    </table>
</div>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/tether.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/lingo.js"></script>
<script>
    Lingo.init();
</script>
</body>
</html>