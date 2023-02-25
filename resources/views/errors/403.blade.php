@extends('errors::mazer')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('image', __('error-403.svg'))
