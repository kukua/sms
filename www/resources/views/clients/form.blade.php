<div class="form-group">
	<label for="name" class="control-label col-sm-4">Name *</label>
	<div class="col-sm-8">
		<input type="text" id="name" class="form-control" name="name" value="{{ $client->name }}">
	</div>
</div>

<div class="form-group">
	<label for="city" class="control-label col-sm-4">City *</label>
	<div class="col-sm-8">
		<input type="text" id="city" class="form-control" name="city" value="{{ $client->city }}">
	</div>
</div>

<div class="form-group">
	<label for="phone" class="control-label col-sm-4">Phone *</label>
	<div class="col-sm-8">
		<input type="text" id="phone" class="form-control" name="phone" value="{{ $client->phone }}">
	</div>
</div>

<div class="form-group">
	<label for="from" class="control-label col-sm-4">From (Twilio nr) *</label>
	<div class="col-sm-8">
		<input type="text" id="from" class="form-control" name="from" value="{{ $client->from }}">
	</div>
</div>

<div class="form-group">
	<div class="col-sm-8 col-sm-offset-4">
		<div class="radio">
			<label>
				<input type="radio" name="type" value="1" checked> SMS Type 1
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="type" value="2"> SMS Type 2
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-8 col-sm-offset-4">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<button type="submit" class="btn btn-success btn-block">Save</button>
	</div>
</div>
