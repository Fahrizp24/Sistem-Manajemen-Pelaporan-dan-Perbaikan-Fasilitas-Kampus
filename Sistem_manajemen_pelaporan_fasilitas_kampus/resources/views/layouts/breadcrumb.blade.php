<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
                    @foreach ($breadcrumb->list as $key => $value)
                        @if ($key == count($breadcrumb->list) - 1)
                            <li class="breadcrumb-item active">{{ $value }}</li>
                        @else
                            <li class="breadcrumb-item">{{ $value }}</li>
                        @endif
                    @endforeach
                    {{-- <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0)">{{ $breadcrumb_parent ?? 'Parent' }}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ $breadcrumb_current ?? 'Current' }}</li> --}}
                </ul>
            </div>
            <div class="col-md-12">
                {{-- <div class="page-header-title">
                    <h2 class="mb-0">{{ $page->title }}</h2>
                </div> --}}
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->
