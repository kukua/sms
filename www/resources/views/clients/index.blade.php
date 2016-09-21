@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-8">
				<a href="/clients/add" class="btn btn-primary pull-right">Add client</a>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<table class="table table-striped">
					<thead>
						<tr>
							<td>#</td>
							<td>Full name</td>
							<td>City</td>
							<td>Phone number</td>
							<td>Twilio number</td>
							<td>Type</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						@foreach ($clients as $client)
							<tr>
								<td>{{ $client->id }}</td>
								<td>{{ $client->name }}</td>
								<td>{{ $client->content->city }}</td>
								<td>{{ $client->phone }}</td>
								<td>{{ $client->from }}</td>
								<td>{{ $client->content->type }}</td>
								<td>
									<div class="pull-right">
										<a href="/clients/edit/{{ $client->id }}/"><i class="fa fa-btn fa-edit"></i></a>
										&nbsp;&nbsp;
										<a href="/clients/delete/{{ $client->id }}/" class="js-confirm" data-confirm="Are you sure you want to remove this client?"><i class="fa fa-btn fa-trash"></i></a>
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
