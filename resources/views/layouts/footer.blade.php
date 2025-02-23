<div class="row mx-0 productFooter">
    <div class="col-md-12">
        All rights reserved &copy; {{ date('Y') }}
        <a href="//{{ getWebsiteName() }}">{{ getAppName() }}</a>
        @if(config('app.show_version'))
            <span class="float-right version_name">v{{ version() }}</span>
        @endif
    </div>
</div>

