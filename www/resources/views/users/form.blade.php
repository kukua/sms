<div class="form-group">
	<label for="name" class="control-label col-sm-4">Name *</label>
	<div class="col-sm-8">
		<input type="text" id="name" class="form-control" name="name" value="{{ $user->name }}">
	</div>
</div>

<div class="form-group">
	<label for="name" class="control-label col-sm-4">E-mail *</label>
	<div class="col-sm-8">
		<input type="text" id="name" class="form-control" name="email" value="{{ $user->email }}">
	</div>
</div>

<div class="form-group">
	<label for="name" class="control-label col-sm-4">Password *</label>
	<div class="col-sm-8">
		<input type="password" id="name" class="form-control" name="password" value="">
	</div>
</div>

<div class="form-group">
	<label for="name" class="control-label col-sm-4">Password confirmation *</label>
	<div class="col-sm-8">
		<input type="password" id="name" class="form-control" name="password_confirmation" value="">
	</div>
</div>

<div class="form-group">
	<div class="col-sm-8 col-sm-offset-4">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<button type="submit" class="btn btn-success btn-block">Save</button>
	</div>
</div>
