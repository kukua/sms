@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-8">
				<a href="/clients/add" class="btn btn-primary pull-right">Add client</a>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-8 col-xs-offset-2">
				<table class="table table-striped">
					<thead>
						<tr>
							<td>Full name</td>
							<td>City</td>
							<td>Phone number</td>
							<td>Type</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						@foreach ($clients as $client)
							<tr>
								<td>{{ $client->name }}</td>
								<td>{{ $client->city }}</td>
								<td>{{ $client->phone }}</td>
								<td>{{ $client->type }}</td>
								<td>
									<div class="pull-right">
										<a href="/clients/edit/{{ $client->id }}/"><i class="fa fa-btn fa-edit"></i></a>
										<a href="/clients/delete/{{ $client->id }}/"><i class="fa fa-btn fa-trash"></i></a>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<a href="/clients/add" class="btn btn-primary">Add client</a>
			</div>
		</div>
	</div>
@endsection
