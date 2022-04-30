@extends('layouts.app')

@section('content')
    @php

    $datas = [['name' => 'incident_gps_lat', 'title' => 'GPS crash latitude'], ['name' => 'incident_gps_lon', 'title' => 'GPS crash longitude'], ['name' => 'depart_gps_lat', 'title' => 'GPS départ latitude'], ['name' => 'depart_gps_lon', 'title' => 'GPS départ longitude'], ['name' => 'destination_gps_lat', 'title' => 'GPS arrivée latitude'], ['name' => 'destination_gps_lon', 'title' => 'GPS arrivée longitude']];
    @endphp


    <br>
    <div class="container">
        <div class="panel panel-primary ">
            <div class="panel-heading d-flex justify-content-center h2">Modification d'un crash</div>
            <div class="panel-body d-flex justify-content-center">
                <div class="col-sm-6 mt-4">
                    <form method="POST" action="{{ route('incidents.update', [$incident->id]) }}" accept-charset="UTF-8"
                        class="form-horizontalpanel">
                        @csrf
                        @method('PUT')

                        @foreach ($datas as $data)
                            <div class="form-group {!! $errors->has($data['name']) ? 'has-error' : '' !!} mb-2">
                                <label for="{{ $data['name'] }}" class="col-md-4 control-label">{{ $data['title'] }}</label>
                                <input type="text" name="{{ $data['name'] }}"
                                    value="{{ old($data['name']) ?? $incident[$data['name']] }}"
                                    placeholder="{{ $data['title'] }}" class="form-control">
                                {!! $errors->first($data['name'], '<small class="help-block">:message</small>') !!}
                            </div>
                        @endforeach





                        <div class="d-flex justify-content-end ">
                            <input class="btn btn-success pull-right mt-5 " type="submit" value="Enregistrer">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
