<div class="form-group{{ $errors->has('about_us_customer') ? 'has-error' : ''}}">
    {!! Form::label('about_us_customer', 'About Us', ['class' => 'control-label']) !!}
    {!! Form::textarea('about_us_customer', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('about_us_customer', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('terms_and_conditions_customer') ? 'has-error' : ''}}">
    {!! Form::label('terms_and_conditions_customer', 'Terms And Conditions', ['class' => 'control-label']) !!}
    {!! Form::textarea('terms_and_conditions_customer', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('terms_and_conditions_customer', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('private_policy_customer') ? 'has-error' : ''}}">
    {!! Form::label('private_policy_customer', 'Private Policy', ['class' => 'control-label']) !!}
    {!! Form::textarea('private_policy_customer', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('private_policy_customer', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('facebook_url') ? 'has-error' : ''}}">
    {!! Form::label('facebook_url', 'facebook URL', ['class' => 'control-label']) !!}
    {!! Form::text('facebook_url', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('facebook_url', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('youtube_url') ? 'has-error' : ''}}">
    {!! Form::label('youtube_url', 'Youtube URL', ['class' => 'control-label']) !!}
    {!! Form::text('youtube_url', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('youtube_url', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('instagram_url') ? 'has-error' : ''}}">
    {!! Form::label('instagram_url', 'Instagram URL', ['class' => 'control-label']) !!}
    {!! Form::text('instagram_url', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('instagram_url', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('twitch') ? 'has-error' : ''}}">
    {!! Form::label('twitch', 'Twitch', ['class' => 'control-label']) !!}
    {!! Form::text('twitch', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('twitch', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('google_play_url') ? 'has-error' : ''}}">
    {!! Form::label('google_play_url', 'Google Play URL', ['class' => 'control-label']) !!}
    {!! Form::text('google_play_url', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('google_play_url', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('app_store_url') ? 'has-error' : ''}}">
    {!! Form::label('app_store_url', 'App Store URL', ['class' => 'control-label']) !!}
    {!! Form::text('app_store_url', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('app_store_url', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('address') ? 'has-error' : ''}}">
    {!! Form::label('address', 'Address', ['class' => 'control-label']) !!}
    {!! Form::text('address', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('phone_number') ? 'has-error' : ''}}">
    {!! Form::label('phone_number', 'Phone number', ['class' => 'control-label']) !!}
    {!! Form::text('phone_number', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('phone_number', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
    {!! Form::email('email', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
