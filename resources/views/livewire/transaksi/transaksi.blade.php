<div>
    <section class="section">

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
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group text-uppercase">
                                    <label for="total_hargaa">TOTAL HARGA TRANSAKSI</label>
                                    <input type="text" name="total_hargaa" id="total_hargaa" class="form-control"
                                        style="padding: 67px; font-size: 30px; font-weight: bold; text-align: center;"
                                        readonly value="Rp0">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text" data-toggle="modal"
                                                        data-target="#tambahDataCustomerModal"><i
                                                            class="fas fa-users"></i>
                                                    </div>
                                                </div>
                                                <input type="datetime-local" wire:model="tanggal" id="tanggal"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="nama_kasir">Kasir</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                                </div>
                                                <input type="text" wire:model="nama_kasir" id="nama_kasir"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id_customer">Customer</label>
                                    <div wire:ignore>
                                        <select wire:model="id_customer" id="id_customer" class="form-control">
                                            @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->nama_customer }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" wire:click.prevent="storeOrder()" wire:loading.attr="disabled"
                                class="btn btn-outline-info form-control">Save
                                Data</button>
                        </div>
                    </form>
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="table-responsive tw-max-h-96">
                                <table>
                                    <thead class="tw-sticky tw-top-0">
                                        <tr class="tw-text-gray-700 text-left">
                                            <th width="7%" class="text-center">No</th>
                                            <th width="27%">Nama Barang</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Diskon</th>
                                            <th>Total</th>
                                            <th width="15%" class="text-center">
                                                <i class="fas fa-cog"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>ADAPTOR 25V 2A</td>
                                            <td>25000</td>
                                            <td>1</td>
                                            <td>0</td>
                                            <td>25000</td>
                                            <td>
                                                <button class="btn btn-warning" wire:click='edit()' data-toggle="modal"
                                                    data-target="#ubahDataModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" wire:click.prevent='deleteConfirm()'>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn-modal" data-toggle="modal" data-target="#tambahDataModal">
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
                            <label for="addIdBarang">Nama Barang</label>
                            <select class="form-control tw-rounded-lg" name="addIdBarang" id="addIdBarang"
                                wire:model='addIdBarang'>
                                <option value="0">-- Pilih Opsi --</option>
                                @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                            @error('addIdBarang') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="addQty">Qty</label>
                            <input type="text" wire:model="addQty" id="addQty" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary tw-bg-gray-300"
                            data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="addCart({{ $addIdBarang }}, {{ $addQty }})"
                            wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">Save Data</button>
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
                            <label for="nama_kategori">Kategori</label>
                            <input type="text" wire:model="nama_kategori" id="nama_kategori" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary tw-bg-gray-300"
                            data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="update()" wire:loading.attr="disabled"
                            class="btn btn-primary tw-bg-blue-500">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
