<div class="form-group{{ $errors->has('team_name') ? 'has-error' : ''}}">
    {!! Form::label('team_name', 'Team Name', ['class' => 'control-label']) !!}
    {!! Form::text('team_name', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('team_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('league_name') ? 'has-error' : ''}}">
    {!! Form::label('league_name', 'League Name', ['class' => 'control-label']) !!}
    {!! Form::text('league_name', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('league_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    {!! Form::label('image', 'Image', ['class' => 'control-label']) !!}
    {!! Form::file('image', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
