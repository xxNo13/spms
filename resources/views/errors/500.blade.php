@extends('errors::mazer')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Something went wrong.'))
@section('image', __('error-500.svg'))
