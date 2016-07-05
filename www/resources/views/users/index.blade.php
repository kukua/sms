@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-8">
				<a href="/users/create" class="btn btn-primary pull-right">Add user</a>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-8 col-xs-offset-2">
				<table class="table table-striped">
					<thead>
						<tr>
							<td>Full name</td>
							<td>E-mail</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						@foreach ($users as $user)
							<tr>
								<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								<td>
									<div class="pull-right">
										@if (\Auth::user()->id != $user->id)
											<a href="/users/delete/{{ $user->id }}/" class="js-confirm" data-confirm="Are you sure you want to delete this user?"><i class="fa fa-btn fa-trash"></i></a>
										@endif
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<a href="/users/create" class="btn btn-primary">Add user</a>
			</div>
		</div>
	</div>
@endsection
