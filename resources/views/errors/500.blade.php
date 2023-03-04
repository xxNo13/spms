@extends('errors::mazer')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Oops.. Something went wrong..'))
@section('image', __('error-500.svg'))
@section('url', 'refresh')