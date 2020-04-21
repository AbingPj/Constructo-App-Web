@extends('layouts.app')
@section('content')

 <div id="app">
   <router-view name='WelcomePage'> </router-view>
   <router-view> <router-view>
 </div>
@endsection
