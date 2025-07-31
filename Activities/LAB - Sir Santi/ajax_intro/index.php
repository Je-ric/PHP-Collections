<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="js/jquery.mins.js"></script>
</head>
<body>
    <h1 id="demo">Change me!</h1>
    <input type="text">
    <button onclick="changeH1()">Change H1</button>
</body>

<script type="text/javascript">
    function changeH1() {
        // Native / Vanilla JavaScript
        // var xhttp = new XMLHttpRequest();
        // xhttp.open("GET", "sample.txt", true);
        // xhttp.send();
        // xhttp.onreadystatechange = function() {
        //     if (this.readyState == 4 && this.status == 200) {
        //         document.getElementById("demo").innerHTML = this.responseText;
        //     }
        //     console.log(this)
        // };

        // JQuery AJAX 
        $.get('sample.txt', function(data) {
            $('#demo').html(data);
        }, 'text');
    }
</script>

</html>