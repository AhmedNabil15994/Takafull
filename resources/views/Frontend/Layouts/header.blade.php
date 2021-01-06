<div class="header">
  <div class="container clearfix">
    <a href="{{ URL::to('/') }}" class="logo"><img src="{{ asset('/assets/images/logo.png') }}" alt="" /></a>
    <i class="iconMenuMob iconMenu"></i>
    <ul class="menuList clearfix">
      @foreach($data->topMenu as $key => $item)
      @if($key == 0)
        <li><a href="#" data-scroll-nav="1">{{ $item->title }}</a></li>
      @elseif($key == 1 || $key == 2)
        <li><a href="#" data-scroll-nav="2">{{ $item->title }}</a></li>
      @endif
      @if($item->page_id == 0)
      <li><a href="{{ $item->link }}">{{ $item->title }}</a></li>
      @else
        @if($item->link != '')
        <li><a href="#" data-toggle="modal" data-target="{{ $item->link }}">{{ $item->title }}</a></li>
        @endif
      @endif
      @endforeach
    </ul>
  </div>  
</div>

<div class="headerHeight"></div>