@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-xs-4 col-xs-offset-4">
				<form method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="text" name="city" placeholder="Arusha, Tanzania" class="form-control">
					<center><button type="submit" class="btn btn-primary">Gimme info</button></center>
				</form>
			</div>
		</div>
	</div>
	<div style="margin: 20px 0"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">Text message example</div>
					<div class="panel-body">
						{{ ucfirst($city) }} {{ $datetime }}<br />
						Temperature min: {{ $tempMin }} ℃<br />
						Temperature max: {{ $tempMax }} ℃<br />
						Expected rainfall: {{ $rain }} mm
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
