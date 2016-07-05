@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-xs-4 col-xs-offset-4">
				<form method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<div class="input-group">
						<input type="text" name="city" placeholder="Arusha, Tanzania" class="form-control">
						<span class="input-group-btn">
							<button type="submit" class="btn btn-primary">Display output</button>
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div style="margin: 20px 0"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-5 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">Text message 1</div>
					<div class="panel-body">
						<?= nl2br($one) ?>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="panel panel-default">
					<div class="panel-heading">Text message 2</div>
					<div class="panel-body">
						<?= nl2br($two); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
