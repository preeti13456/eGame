@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row">
        	<div class="col-md-8 col-md-offset-2">
            	<div class="panel panel-default">
            		<div class="panel-heading">
            			<h2>New article</h2>
            		</div>
					<div class="panel-body">
						{{-- <!-- Form -->
						<!-- With @include we call the form template that we have in the article.form view --> --}}
						@include('article.form', ['article' => $article, 'url' => '/articles', 'method' => 'POST'])
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection