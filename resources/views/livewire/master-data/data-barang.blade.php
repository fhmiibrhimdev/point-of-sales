<div>
    <section class="section">
        <div class="section-header tw-rounded-none lg:tw-rounded-lg tw-shadow-md tw-shadow-gray-300 px-4">
            <h1 class="tw-text-lg mb-1">Daftar Barang</h1>
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
                    <h3>Tabel Barang</h3>
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
                                <tr class="tw-text-gray-700 text-left">
                                    <th width="8%" class="text-center">No</th>
                                    <th width="9%">Kode</th>
                                    <th class="text-left">Nama Barang</th>
                                    <th width="10%">Satuan</th>
                                    <th width="13%">Kategori</th>
                                    <th width="10%">Harga</th>
                                    <th width="8%">Stock</th>
                                    <th width="15%" class="text-center">
                                        <i class="fas fa-cog"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                <tr class="text-left tw-uppercase">
                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                    <td>{{ $row->kode_barang }}</td>
                                    <td class="text-left">{{ $row->nama_barang }}</td>
                                    <td>{{ $row->nama_satuan }}</td>
                                    <td>{{ $row->nama_kategori }}</td>
                                    <td class="text-right">{{ number_format($row->harga, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ $row->stock }},00</td>
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
                                    <td class="text-center" colspan="8">No data available in the table</td>
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
        <div class="modal-dialog modal-lg">
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
                        <div class="row">
                            <div class="col-lg-6">
                                @if ($gambar == '')
                                <img src="https://static.vecteezy.com/system/resources/previews/004/141/669/original/no-photo-or-blank-image-icon-loading-images-or-missing-image-mark-image-not-available-or-image-coming-soon-sign-simple-nature-silhouette-in-frame-isolated-illustration-vector.jpg"
                                    class="tw-rounded-lg tw-object-cover tw-h-full tw-w-full mb-3">
                                @else
                                <img src="{{ $gambar->temporaryUrl() }}"
                                    class="tw-rounded-lg tw-object-cover tw-h-full tw-w-full mb-3">
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <div class="custom-file form-group">
                                    <input type="file" wire:model='gambar' class="custom-file-input tw-rounded-lg"
                                        id="gambar">
                                    <label class="custom-file-label" for="gambar">Upload foto barang</label>
                                    @error('gambar') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="kode_barang">Kode Barang</label>
                                            <input type="text" class="form-control tw-rounded-lg tw-uppercase"
                                                name="kode_barang" id="kode_barang" wire:model='kode_barang' readonly>
                                            @error('kode_barang') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control tw-rounded-lg tw-uppercase"
                                                name="nama_barang" id="nama_barang" wire:model='nama_barang'>
                                            @error('nama_barang') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="id_satuan">Satuan</label>
                                            <div wire:ignore>
                                                <select class="form-control tw-rounded-lg" name="id_satuan"
                                                    id="id_satuan" wire:model='id_satuan'>
                                                    @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->nama_satuan }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('id_satuan') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="id_kategori">Kategori</label>
                                            <div wire:ignore>
                                                <select class="form-control tw-rounded-lg" name="id_kategori"
                                                    id="id_kategori" wire:model='id_kategori'>
                                                    @foreach ($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('id_kategori') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" wire:model='harga' name="harga" id="harga"
                                        class="form-control">
                                </div>
                            </div>
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
        <div class="modal-dialog modal-lg">
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
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="hidden" wire:model='dataId'>
                                <input type="hidden" wire:model="gambarLama">
                                @if ($gambar != NULL)
                                <img src="{{ $gambar->temporaryUrl() }}"
                                    class="tw-rounded-lg tw-object-cover tw-h-full tw-w-full mb-3">
                                @else
                                <img src="{{ url('storage/'.$gambarLama) }}"
                                    class="tw-rounded-lg tw-object-cover tw-h-full tw-w-full mb-3">
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <div class="custom-file form-group">
                                    <input type="file" wire:model='gambar' class="custom-file-input tw-rounded-lg"
                                        id="gambar">
                                    <label class="custom-file-label" for="gambar">Upload foto barang</label>
                                    @error('gambar') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="kode_barang">Kode Barang</label>
                                            <input type="text" class="form-control tw-rounded-lg tw-uppercase"
                                                name="kode_barang" id="kode_barang" wire:model='kode_barang' readonly>
                                            @error('kode_barang') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control tw-rounded-lg tw-uppercase"
                                                name="nama_barang" id="nama_barang" wire:model='nama_barang'>
                                            @error('nama_barang') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="id_satuan">Satuan</label>
                                            <div wire:ignore>
                                                <select class="form-control tw-rounded-lg" name="id_satuan"
                                                    id="id_satuan" wire:model='id_satuan'>
                                                    @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->nama_satuan }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('id_satuan') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="id_kategori">Kategori</label>
                                            <div wire:ignore>
                                                <select class="form-control tw-rounded-lg" name="id_kategori"
                                                    id="id_kategori" wire:model='id_kategori'>
                                                    @foreach ($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('id_kategori') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" wire:model='harga' name="harga" id="harga"
                                        class="form-control">
                                </div>
                            </div>
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
