@isset($searching)
    <div class="col-md-12 ">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <label for="perPage">Items per page:</label>
                <select wire:model.live="perPage" id="perPage">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-md-9 col-sm-12">
                <div class="table-seraching float-end">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Cari data ..." wire:model.live="search">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset
<table class="table table-bordered table-hover custom-table">
    {{ $slot }}
</table>