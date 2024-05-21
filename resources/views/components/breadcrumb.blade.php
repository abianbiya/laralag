<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $title }}</h4>
            
            <div class="page-title-right d-none d-md-block">
                <ol class="breadcrumb m-0">
                    @if(filled(@$crumbs))
                        @foreach ($crumbs as $key => $crumb)
                            <li class="breadcrumb-item"><a href="{{ $key == '#' ? 'javascript: void(0);' : route($key) }}">{{ $crumb }}</a></li>
                        @endforeach
                    @endif
                    @if(isset($title))
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    @endif
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->