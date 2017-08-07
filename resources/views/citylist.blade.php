<!DOCTYPE html>
<html>
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    </head>
    <body>
    <div class="container">
        <button type="button" class="btn btn btn-warning" id="update">刷新一下</button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>城市</th>
                @foreach($dateHeads as $dateHead)
                        <th>{{$dateHead}}</th>
                @endforeach
            </tr>
        </thead>
        @foreach($cities as $city => $cityName)
                <tr>
                    <td>{{$cityName}}</td>
                    @foreach($dateHeads as $dateHead)
                        @if(isset($cityInfo[$city][$dateHead]))
                            <td>{{$cityInfo[$city][$dateHead]->totalClick}} / {{$cityInfo[$city][$dateHead]->totalDisplay}}</td>
                        @else
                            <td>未知</td>
                        @endif
                    @endforeach
                </tr>
        @endforeach
    </table>
    <script type="text/javascript">
        $(document).ready(function(){
        	$('#update').click(function () {
				$.ajax({
					url: "/ajax/update",
					type: "GET",
					error: function(){
					},
					success: function(){
					}
				});
			});
        	$.ajax()
        });
    </script>
    </body>
</html>
