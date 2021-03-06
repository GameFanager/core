@extends('livecms::backend')

@section('form')
	
	@include('livecms::admin.partials.error')
	<div class="row form-group">
		<div class="col-md-3">
			{!! Form::label('category', trans('livecms::livecms.category'), ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-9">
			{!! Form::text('category', $category->category, ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="row form-group">
		<div class="col-md-3">
			{!! Form::label('slug', 'Slug', ['class' => 'control-label']) !!}
		</div>
		<div class="col-md-9">
			{!! Form::text('slug', $category->slug, ['class' => 'form-control']) !!}
		</div>
	</div>

@stop

@section('content')
@include('livecms::admin.partials.form')
@stop