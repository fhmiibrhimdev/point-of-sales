<div>
    <section class="section">
        <div class="section-header tw-rounded-none lg:tw-rounded-lg tw-shadow-md tw-shadow-gray-300 px-4">
            <h1 class="tw-text-lg mb-1">Daftar Supplier</h1>
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
            <div class="card">
                <div class="card-body px-0">
                    <h3>Tabel Supplier</h3>
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
                    <div class="table-responsive tw-max-h-96">
                        <table>
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="8%" class="text-center">No</th>
                                    <th width="13%">Nama</th>
                                    <th width="14%">Nomor HP</th>
                                    <th>Alamat</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">
                                        <i class="fas fa-cog"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                <tr class="text-center">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td class="text-left">{{ $row->nama_supplier }}</td>
                                    <td class="text-left">{{ $row->hp_supplier }}</td>
                                    <td class="text-left">{{ Str::substr($row->alamat_supplier, 0, 50) }}...</td>
                                    <td class="text-left">{{ $row->deskripsi_supplier }}</td>
                                    <td>
                                        <button class="btn btn-warning" wire:click='edit({{ $row->id }})'
                                            data-toggle="modal" data-target="#ubahDataModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger"
                                            wire:click.prevent='deleteConfirm({{ $row->id }})'>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="6">No data available in the table</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 table-responsive tw-mb-[-15px]">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
        <button class="btn-modal" data-toggle="modal" data-target="#tambahDataModal">
            <i class="far fa-plus"></i>
        </button>
    </section>

    <div class="modal fade" wire:ignore.self id="tambahDataModal" aria-labelledby="tambahDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data</h5>
                    <button type="button" wire:click.prevent="cancel()" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_supplier">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" wire:model="nama_supplier" id="nama_supplier" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="hp_supplier">Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" wire:model="hp_supplier" id="hp_supplier" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="alamat_supplier">Alamat</label>
                            <textarea wire:model="alamat_supplier" id="alamat_supplier" class="form-control"
                                style="height: 50px !important;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_supplier">Deskripsi</label>
                            <textarea wire:model="deskripsi_supplier" id="deskripsi_supplier" class="form-control"
                                style="height: 100px !important;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary tw-bg-gray-300"
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
                            <label for="nama_supplier">Nama Lengkap <span class="tw-text-red-500">*</span></label>
                            <input type="text" wire:model="nama_supplier" id="nama_supplier" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="hp_supplier">Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" wire:model="hp_supplier" id="hp_supplier" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="alamat_supplier">Alamat</label>
                            <textarea wire:model="alamat_supplier" id="alamat_supplier" class="form-control"
                                style="height: 50px !important;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_supplier">Deskripsi</label>
                            <textarea wire:model="deskripsi_supplier" id="deskripsi_supplier" class="form-control"
                                style="height: 100px !important;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary tw-bg-gray-300"
                            data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="update()" wire:loading.attr="disabled"
                            class="btn btn-primary tw-bg-blue-500">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
