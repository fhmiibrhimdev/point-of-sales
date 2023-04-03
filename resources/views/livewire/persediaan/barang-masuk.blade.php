<div>
    <section class="section">
        <div class="section-header tw-rounded-none lg:tw-rounded-lg tw-shadow-md tw-shadow-gray-300 px-4">
            <h1 class="tw-text-lg mb-1">Persediaan - Stok Masuk</h1>
        </div>

        @if ($errors->any())
        <script>
            Swal.fire(
                'error',
                'Ada yang error',
                'error'
            )

        </script>
        @endif
        <div class="section-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card tw-rounded-md tw-shadow-md">
                        <div class="card-body">
                            <h4 class="tw-text-black tw-text-lg mb-3 text-center">F I L T E R</h4>
                            <div class="form-group mt-3">
                                <label for="filter_id_barang">Nama Barang</label>
                                <div wire:ignore>
                                    <select name="filter_id_barang" id="filter_id_barang" wire:model='filter_id_barang'
                                        class="form-control tw-rounded-lg">
                                        <option value="0">-- Pilih Barang --</option>
                                        @foreach ($barangs as $barang)
                                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="filter_dari_tanggal">Dari Tanggal</label>
                                <input type="datetime-local" name="filter_dari_tanggal" id="filter_dari_tanggal"
                                    wire:model='filter_dari_tanggal' class="form-control tw-rounded-lg">
                            </div>
                            <div class="form-group">
                                <label for="filter_sampai_tanggal">s/d Tanggal</label>
                                <input type="datetime-local" name="filter_sampai_tanggal" id="filter_sampai_tanggal"
                                    wire:model='filter_sampai_tanggal' class="form-control tw-rounded-lg">
                            </div>
                            <div class="tw-grid tw-grid-cols-2 tw-gap tw-gap-3">
                                <div>
                                    <a href="{{ route('laporan-excel.barang-masuk', ["id_user" => $this->id_user, "id_barang" => $this->filter_id_barang,
                                    "dari_tanggal" => $this->filter_dari_tanggal, "sampai_tanggal" =>
                                    $this->filter_sampai_tanggal]) }}" class="btn btn-outline-success tw-w-full"
                                        target="_BLANK">
                                        <i class="far fa-file-excel"></i> EXCEL
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('laporan-pdf.barang-masuk', ["id_user" => $this->id_user, "id_barang" => $this->filter_id_barang,
                                    "dari_tanggal" => $this->filter_dari_tanggal, "sampai_tanggal" =>
                                    $this->filter_sampai_tanggal]) }}" class="btn btn-outline-danger tw-w-full"
                                        target="_BLANK">
                                        <i class="far fa-file-pdf"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body px-0">
                            <h3>Tabel Stok Masuk</h3>
                            <div class="show-entries">
                                <p class="show-entries-show">Show</p>
                                <select wire:model='lengthData' id="length-data">
                                    <option value="1">1</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <p class="show-entries-entries">Entries</p>
                            </div>
                            <div class="search-column">
                                <p>Search: </p>
                                <input type="search" wire:model.debounce.1s='search' id="search-data"
                                    placeholder="Search here...">
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="tw-table-fixed tw-w-full tw-text-black tw-text-md mt-3 tw-border-collapse tw-border">
                                    <thead>
                                        <tr class="tw-border-b tw-text-xs text-center text-uppercase">
                                            <th width="10%" class="text-center">No</th>
                                            <th class="p-3">Tanggal</th>
                                            <th class="p-3">Nama Barang</th>
                                            <th class="p-3">QTY</th>
                                            <th class="p-3">Keterangan</th>
                                            <th class="p-3 text-center"><i class="fas fa-cog"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $row)
                                        <tr
                                            class="tw-bg-white tw-border tw-text-uppercase tw-border-gray-200 hover:tw-bg-gray-50 text-center">
                                            <td class="p-3">{{ $loop->index + 1 }}</td>
                                            <td class="p-3">{{ $row->tanggal }}</td>
                                            <td class="p-3">{{ $row->nama_barang }}</td>
                                            <td class="p-3">{{ $row->qty }}</td>
                                            <td class="p-3">{{ $row->keterangan }}</td>
                                            <td class="p-3 text-center">
                                                <button class="btn btn-primary" data-toggle="modal"
                                                    data-target="#ubahDataModal" wire:click="edit({{ $row->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger"
                                                    wire:click.prevent="deleteConfirm({{ $row->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="text-center">
                                            <td class="p-3" colspan="6">
                                                No data available in table
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive p-3">
                                {{ $data->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button
                class="tw-fixed tw-right-[30px] tw-bottom-[50px] tw-w-14 tw-h-14 tw-shadow-2xl tw-rounded-full tw-bg-slate-600 tw-z-40 text-white hover:tw-bg-slate-900 hover:tw-border-slate-600"
                data-toggle="modal" data-target="#tambahDataModal">
                <i class="far fa-plus"></i>
            </button>
        </div>
    </section>
    <div class="modal fade" wire:ignore.self id="tambahDataModal" aria-labelledby="tambahDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="datetime-local" wire:model='tanggal' name="tanggal" id="tanggal"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="id_barang">Nama Barang</label>
                            <div wire:ignore>
                                <select wire:model='id_barang' name="id_barang" id="id_barang" class="form-control">
                                    @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input type="text" wire:model='qty' name="qty" id="qty" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea wire:model='keterangan' name="keterangan" id="keterangan" style="height: 100px;"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.prevent='cancel()' class="btn btn-secondary tw-bg-gray-300"
                            data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="store()" wire:loading.attr="disabled"
                            class="btn btn-primary tw-bg-blue-500">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="ubahDataModal" aria-labelledby="ubahDataModalLabel" aria-hidden="true"
        data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahDataModalLabel">Edit Data</h5>
                    <button type="button" wire:click.prevent='cancel()' class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" wire:model='dataId'>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="datetime-local" wire:model='tanggal' name="tanggal" id="tanggal"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="edit_id_barang">Nama Barang</label>
                            <div wire:ignore>
                                <select wire:model='id_barang' name="id_barang" id="edit_id_barang" class="form-control"
                                    disabled>
                                    @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input type="text" wire:model='qty' name="qty" id="qty" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea wire:model='keterangan' name="keterangan" id="keterangan" style="height: 100px;"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.prevent='cancel()' class="btn btn-secondary tw-bg-gray-300"
                            data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="update()" wire:loading.attr="disabled"
                            class="btn btn-primary tw-bg-blue-500">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    $(document).ready(function () {
        $('#id_barang').select2();
        $('#edit_id_barang').select2();
        $('#id_barang').on('change', function (e) {
            var data = $('#id_barang').select2("val");
            @this.set('id_barang', data);
        });
        $('#edit_id_barang').on('change', function (e) {
            var data = $('#edit_id_barang').select2("val");
            @this.set('id_barang', data);
        });
        $('#filter_id_barang').select2();
        $('#filter_id_barang').on('change', function (e) {
            var data = $('#filter_id_barang').select2("val");
            @this.set('filter_id_barang', data);
        });
    });

</script>

@endpush
