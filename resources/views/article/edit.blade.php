@extends('layouts.app')
@section('content')
	<div class="container">	
		@if(Session::has('success'))
		<div class="alert alert-info">
			{{Session::get('success')}}
		</div>
		@endif
		<div class="row">
        	<div class="col-md-8 col-md-offset-2">
            	<div class="panel panel-default">
            		<div class="panel-heading">
            			<h2>Edit article</h2>
            		</div>
					<div class="panel-body">
						{{-- <!-- Form -->
						<!-- With @include we call the form template that we have in the article.form view --> --}}
						@include('article.form', ['article' => $article, 'url' => '/articles/'.$article->id, 'method' => 'PATCH'])
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection