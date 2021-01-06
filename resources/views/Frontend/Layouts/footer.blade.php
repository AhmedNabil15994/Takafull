<footer class="footer">
	<div class="container">
		<center>
			<div class="descFooter">
				{!! $data->pages[2]->title !!} 
			</div>
			<p class="price">{!! $data->pages[3]->title !!} </p>
			<div class="numbDiv">
				<p class="titleNomb">{!! $data->pages[4]->title !!}</p>
				<p class="numbFooter">
					<a href="tel:{{ $data->tele }}">{{ $data->tele }}</a> - 
					<a href="tel:{{ $data->tele2 }}">{{ $data->tele2 }}</a> 
				</p>
			</div>
			<a href="#" class="logoServers wow fadeInUp"><img src="{{ URL::to('/assets/images/logoServers.png') }}" alt="" /></a>
		</center>
	</div>
</footer>

<div class="menuDown">
	<ul class="linksList">
		@foreach($data->bottomMenu as $key => $item)
      		@if($key == 0)
        	<li><a href="#" data-scroll-nav="1"><i class="{{ $item->icon }}"></i>{{ $item->title }}</a></li>
      		@elseif($key == 1 || $key == 2)
        	<li><a href="#" data-scroll-nav="2"><i class="{{ $item->icon }}"></i>{{ $item->title }}</a></li>
      		@endif
      		@if($item->page_id == 0)
      		<li><a href="{{ $item->link }}"><i class="{{ $item->icon }}"></i>{{ $item->title }}</a></li>
      		@else
        		@if($item->link != '')
        		<li><a href="#" data-toggle="modal" data-target="{{ $item->link }}"><i class="{{ $item->icon }}"></i>{{ $item->title }}</a></li>
        		@endif
      		@endif
      	@endforeach
	</ul>
</div>