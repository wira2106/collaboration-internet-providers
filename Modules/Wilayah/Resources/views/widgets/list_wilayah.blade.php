
<div class="col-md-12">
    <Card class="card card-outline-info">
        <div class="card-header text-white">
            {{ trans('wilayah::wilayahs.list resource') }}

            <div class="card-actions">
                <a data-action="collapse">
                <i class="ti-minus text-white"></i>
                </a>
            </div>
        </div>
        <div class="card-body show collapse">
            <list-wilayah-widget>
            </list-wilayah-widget>
        </div>
    </Card>
</div>
