@extends('layouts.master')

@section('title', 'Assignment')

@section('page-head')
@parent
<!-- HTML page's head -->

@endsection


@section('top-bar')
@parent
<!-- Top Menu -->
    @include('_partials.topmenu')
@endsection

@section('content')
<br />

<div class="row">

    <div class="large-12 columns">
        <!-- breadcrumbs. @todo - Convert into React component  -->
        <nav aria-label="You are here:" role="navigation">
          <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li><a href="#">Assignment</a></li>
            <li>Nine Numbers</li>
            <li>
              <span class="show-for-sr">Current: </span> Question 1
            </li>
          </ul>
        </nav>
    </div>
</div>
<!-- Item pane -->
<div class="eli-assignent callout" />

    <!-- Body  -->
    <div class="row">
        <div class="medium-8 columns">
            <div class="row">
                <div class="small-4 small-centered columns">
                    <div class="eli-assignment-placeholder" id="placeholder-content"></div>
                </div>
            </div>
        </div>
        <div class="medium-4 columns">
            <div>
                <select id="activitySelector"></select>
            </div>
            <button id="nextButton" type="button" class="success button" onClick="navigateNext()">Next</button>
            <div class="eli-assignment-placeholder" id="placeholder-scoreboard" ></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('js/eli-externdeps-math.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/eli-externdeps.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/eli-interactives.bundle.js') }}" type="text/javascript" ></script>

<script type="text/javascript">
    var interactives = require('interactives');

    var assignmentProvider = new interactives.AssignmentProvider({baseUrl: 'http://localhost:8000/api/assignments'});
    //var assignmentProvider = null;
    var sequencingConfig = {
        assignmentProvider: assignmentProvider,
    };

    var activityProvider = new interactives.RemoteActivityProvider({assignmentProvider: assignmentProvider});
    var evaluator = new interactives.RemoteEvaluator({assignmentProvider: assignmentProvider});
    var assignemntPlayerConfig = {
        activityProvider: activityProvider,
        evaluator: evaluator,
        sequencingStrategy: new interactives.DefaultSequencingStrategy(sequencingConfig)
    };

    var assignmentPlayer = new interactives.AssignmentPlayer(assignemntPlayerConfig);

    assignmentPlayer.subscribe('next-activity-retrieved', function(activityDescriptor){
        // New activity has been retrieved (in this case generated), add a new entry in the drop down
        if (activityDescriptor && activityDescriptor.uuid) {
            $("#activitySelector").append(
                $("<option></option>")
                .attr("value", activityDescriptor.uuid)
                .text(activityDescriptor.uuid)
                .prop('selected', true)
             );
        } else {
            //alert('End of assignment');
            $("#nextButton").html('Report');
            assignmentPlayer.loadReport();
        }
    });

    var placeholders = {
        content: document.getElementById('placeholder-content'),
        scoreboard: document.getElementById('placeholder-scoreboard')
    }

    assignmentPlayer.startAssignment('{{ $params["outsetNode"] }}')
    .then(function(assignmentDetails){
        assignmentPlayer.render(placeholders);
    })


    function navigateNext()
    {
        assignmentPlayer.handleNavigateEvent_({param: "next"});
    }

    $("#activitySelector").change(function(event) {
        assignmentPlayer.loadItemByActivityId(event.target.value);
    });

</script>
@endsection
