@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-8">
				<a href="/users" class="btn btn-primary pull-right">Cancel</a>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-8 col-xs-offset-1">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-4">
						@if (count($errors) > 0)
							<div class="alert alert-warning">
								@foreach ($errors->all() as $error)
									<strong>{{ $error }}</strong><br>
								@endforeach
							</div>
						@endif
					</div>
				</div>

				<form method="post" class="form form-horizontal" action="/users/store">
					@include('users/form')
				</form>
			</div>
		</div>
	</div>
@endsection
