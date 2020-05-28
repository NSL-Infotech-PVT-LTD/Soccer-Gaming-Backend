<div class="form-group{{ $errors->has('about_us_customer') ? 'has-error' : ''}}">
    {!! Form::label('about_us_customer', 'About Us', ['class' => 'control-label']) !!}
    {!! Form::textarea('about_us_customer', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('about_us_customer', '<p class="help-block">:message</p>') !!}
</div>
<!--<div class="form-group{{ $errors->has('about_us_service_provider') ? 'has-error' : ''}}">
    {!! Form::label('about_us_service_provider', 'about Us Service Provider', ['class' => 'control-label']) !!}
    {!! Form::textarea('about_us_service_provider', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('about_us_service_provider', '<p class="help-block">:message</p>') !!}
</div>-->
<div class="form-group{{ $errors->has('terms_and_conditions_customer') ? 'has-error' : ''}}">
    {!! Form::label('terms_and_conditions_customer', 'Terms And Conditions', ['class' => 'control-label']) !!}
    {!! Form::textarea('terms_and_conditions_customer', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('terms_and_conditions_customer', '<p class="help-block">:message</p>') !!}
</div>
<!--<div class="form-group{{ $errors->has('terms_and_conditions_service_provider') ? 'has-error' : ''}}">
    {!! Form::label('terms_and_conditions_service_provider', 'Terms And Conditions Service Provider', ['class' => 'control-label']) !!}
    {!! Form::textarea('terms_and_conditions_service_provider', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('terms_and_conditions_service_provider', '<p class="help-block">:message</p>') !!}
</div>-->

<div class="form-group{{ $errors->has('private_policy_customer') ? 'has-error' : ''}}">
    {!! Form::label('private_policy_customer', 'Private Policy', ['class' => 'control-label']) !!}
    {!! Form::textarea('private_policy_customer', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('private_policy_customer', '<p class="help-block">:message</p>') !!}
</div>

<!--<div class="form-group{{ $errors->has('private_policy_service_provider') ? 'has-error' : ''}}">
    {!! Form::label('private_policy_service_provider', 'Private Policy Service Provider', ['class' => 'control-label']) !!}
    {!! Form::textarea('private_policy_service_provider', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('private_policy_service_provider', '<p class="help-block">:message</p>') !!}
</div>-->


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
