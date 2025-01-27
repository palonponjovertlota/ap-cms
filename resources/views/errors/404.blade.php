@extends('root.layouts.lite')

@section('content')
	<div class="m-grid__item m-grid__item--fluid m-grid  m-error-3" 
		style="background-image: url(/root/assets/app/media/img/error/bg3.jpg);">
		<div class="m-error_container">
			<span class="m-error_number">
				<h1>404</h1>		 
			</span> 	
			<p class="m-error_title m--font-light">
				How did you get here
			</p>
			<p class="m-error_subtitle">
				Sorry we can't seem to find the page you're looking for.
			</p>	
			<p class="m-error_description">
				There may be a mispelling in the URL entered,<br>
				or the page you are looking for is no longer existing.
			</p>	 
		</div>	 
	</div>
@endsection