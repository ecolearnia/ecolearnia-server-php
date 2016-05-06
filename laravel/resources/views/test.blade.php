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

        <div clss="container">
            <div>
                <div  style="float:left">Content </br>
                    <textarea id="content" cols="50" rows="8"></textarea>
                </div>
                <div>Log</br>
                    <textarea id="log" cols="50" rows="8"></textarea>
                </div>
            </div>

            ID: <input type="text" id="id"></input><br/>
            <div> Content
                <button id="btnGet">Get</button>
                <button id="btnSave">Save</button>
                <button id="btnDelete">Delete</button>
            </div>
            <div >
                Assignment
                <button id="btnStartAssignment">Start Assignment</button>
                <button id="btnNextActivity">Next Activity</button>
            </div>
        </div>

<script type="text/javascript">
    var main = require('main');
    var contenResource = new main.ContentResource({baseUrl: '/api/contents'});
    var assignmentService = new main.AssignmentService({baseUrl: '/api/assignments'});

    $("#btnGet").click(function(event) {
        var id = $("#id").val();
        contenResource.get({_id: id})
        .then(function(data){
            appendLog('Content ' + id + ' retireved. ' + data);
        })
        .catch(function(error){
            appendLog(error);
        });
    });

    $("#btnSave").click(function(event) {
        var id = $("#id").val();
        var content = $("#content").val();
        var params = {_id: id};
        contenResource.save(params, content)
        .then(function(data){
            appendLog('Content ' + id + ' saved. ');
        })
        .catch(function(error){
            appendLog(error);
        });
    });

    $("#btnDelete").click(function(event) {
        var id = $("#id").val();
        contenResource.delete({_id: id})
        .then(function(data){
            appendLog('Content ' + id + ' deleted. ');
        })
        .catch(function(error){
            appendLog(error);
        });
    });

    $("#btnStartAssignment").click(function(event) {
        var outsetUuid = $("#id").val();
        assignmentService.startAssignment(outsetUuid)
        .then(function(data){
            appendLog('Assignment ' + data.uuid + ' started. ');
        });
    });

    $("#btnNextActivity").click(function(event) {
        var assignmentUuid = $("#id").val();
        assignmentService.createNextActivity(assignmentUuid)
        .then(function(data){
            appendLog('Next Activity ' + data.uuid + ' created. ');
        });
    });

    function appendLog(message)
    {
        var log = $("#log").val();
        log += message + "\n";
        $("#log").val(log);
    }

</script>

    </body>
</html>
