<html>
    <head lang="en">
        <meta charset="UTF-8" />

        <link rel="stylesheet" href="../../css/app.css">

        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/deps.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.bundle.js') }}"></script>

        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>

        <title>EcoLearnia (v0.0.2) Test</title>
    </head>
    <body>
        <h1>Hello, <?php echo $name; ?></h1>
        <input type="text" id="id"></input><br/>
        <textarea id="content" cols="40" rows="8"></textarea>
        <br/>
        <button id="btnGet">Get</button>
        <button id="btnSave">Save</button>
        <button id="btnDelete">Delete</button>

<script type="text/javascript">
    var main = require('main');
    var contenResource = new main.ContentResource({baseUrl: '/api/contents'});

    $("#btnGet").click(function(event) {
        var id = $("#id").val();
        contenResource.get({_id: id})
        .then(function(data){
            alert(data);
        });
    });

    $("#btnSave").click(function(event) {
        var id = $("#id").val();
        var content = $("#content").val();
        var params = {_id: id};
        contenResource.save(params, content)
        .then(function(data){
            alert(data);
        });
    });

    $("#btnDelete").click(function(event) {
        var id = $("#id").val();
        contenResource.delete({_id: id})
        .then(function(data){
            alert(data);
        });
    });

</script>

    </body>
</html>
