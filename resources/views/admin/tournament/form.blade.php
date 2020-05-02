<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'Type', ['class' => 'control-label']) !!}
    {!! Form::text('type', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('number_of_players') ? 'has-error' : ''}}">
    {!! Form::label('number_of_players', 'Number Of Players', ['class' => 'control-label']) !!}
    {!! Form::number('number_of_players', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('number_of_players', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('number_of_teams_per_player') ? 'has-error' : ''}}">
    {!! Form::label('number_of_teams_per_player', 'Number Of Teams Per Player', ['class' => 'control-label']) !!}
    {!! Form::number('number_of_teams_per_player', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('number_of_teams_per_player', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('number_of_plays_against_each_team') ? 'has-error' : ''}}">
    {!! Form::label('number_of_plays_against_each_team', 'Number Of Plays Against Each Team', ['class' => 'control-label']) !!}
    {!! Form::text('number_of_plays_against_each_team', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('number_of_plays_against_each_team', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('number_of_players_that_will_be_in_the_knockout_stage') ? 'has-error' : ''}}">
    {!! Form::label('number_of_players_that_will_be_in_the_knockout_stage', 'Number Of Players That Will Be In The Knockout Stage', ['class' => 'control-label']) !!}
    {!! Form::text('number_of_players_that_will_be_in_the_knockout_stage', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('number_of_players_that_will_be_in_the_knockout_stage', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('legs_per_match_in_knockout_stage') ? 'has-error' : ''}}">
    {!! Form::label('legs_per_match_in_knockout_stage', 'Legs Per Match In Knockout Stage', ['class' => 'control-label']) !!}
    {!! Form::text('legs_per_match_in_knockout_stage', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('legs_per_match_in_knockout_stage', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('number_of_legs_in_final') ? 'has-error' : ''}}">
    {!! Form::label('number_of_legs_in_final', 'Number Of Legs In Final', ['class' => 'control-label']) !!}
    {!! Form::text('number_of_legs_in_final', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('number_of_legs_in_final', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
