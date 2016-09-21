<div class="form-group">
	<label for="name" class="control-label col-sm-4">Name *</label>
	<div class="col-sm-8">
		<input type="text" id="name" class="form-control" name="name" value="{{ $client->name }}">
	</div>
</div>

<div class="form-group">
	<label for="city" class="control-label col-sm-4">City *</label>
	<div class="col-sm-8">
		<select class="form-control" id="city" name="city">
			@foreach ($cities as $city)
				<?php $selected = "" ?>
				@if ($client->content && $city->city == $client->content->city)
					<?php $selected = "selected='selected'" ?>
				@endif
				<option value="{{ $city->city }}" {{ $selected }}>{{ $city->city }}</option>
			@endforeach
		</select>
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
		@foreach ($types as $type)
			<?php $checked = "" ?>
			@if ($client->content && $type->type == $client->content->type)
				<?php $checked = "checked='checked'" ?>
			@endif
			<div class="radio">
				<label>
					<input type="radio" name="type" value="{{ $type->type }}" {{ $checked }}> SMS Type {{ $type->type }}
				</label>
			</div>
		@endforeach
	</div>
</div>

<div class="form-group">
	<div class="col-sm-8 col-sm-offset-4">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<button type="submit" class="btn btn-success btn-block">Save</button>
	</div>
</div>
