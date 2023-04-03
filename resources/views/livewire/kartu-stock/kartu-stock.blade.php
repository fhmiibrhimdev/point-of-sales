<div>
    <section class="section">
        <div class="section-header tw-rounded-none lg:tw-rounded-lg tw-shadow-md tw-shadow-gray-300 px-4">
            <h1 class="tw-text-lg mb-1">Kartu Stock</h1>
        </div>
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
                                    <a href="{{ route('laporan-excel.kartu-stock', ["id_barang"=> $this->filter_id_barang,
                                    "dari_tanggal" => $this->filter_dari_tanggal, "sampai_tanggal" =>
                                    $this->filter_sampai_tanggal]) }}" class="btn btn-outline-success tw-w-full"
                                        target="_BLANK">
                                        <i class="far fa-file-excel"></i> EXCEL
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('laporan-pdf.kartu-stock', ["id_barang"=> $this->filter_id_barang,
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
                            <h3>Tabel Kartu Stock</h3>
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
                                            <th rowspan="2" class="p-3">Tanggal</th>
                                            <th rowspan="2" class="p-3">Keterangan</th>
                                            <th rowspan="2" class="p-3">STOCK AWAL</th>
                                            <th colspan="2" class="p-3">MUTASI</th>
                                            <th rowspan="2" class="p-3">STOCK AKHIR</th>
                                        </tr>
                                        <tr class="tw-border-b tw-text-xs text-center text-uppercase">
                                            <th class="p-3">IN</th>
                                            <th class="p-3">OUT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data == '')
                                        <tr id="table-row" class="text-center">
                                            <td class="p-3" colspan="6">
                                                No data available in table
                                            </td>
                                        </tr>
                                        @else
                                        @php
                                        $amountIn = 0;
                                        $amountOut = 0;
                                        $amountBalance = 0;
                                        $amountBalanceLast = 0;
                                        @endphp
                                        @forelse ($data as $row)
                                        <tr
                                            class="tw-bg-white tw-border tw-uppercase tw-border-gray-200 hover:tw-bg-gray-50 text-center">
                                            <td class="p-3">{{ $row->tanggal }}</td>
                                            <td class="p-3">{{ $row->keterangan }}</td>
                                            <td class="p-3">
                                                @if ($row->status == 'Balance')
                                                @php
                                                $amountBalance += $row->qty;
                                                @endphp
                                                {{ $row->qty }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td class="p-3">
                                                @if ($row->status == 'In')
                                                @php
                                                $amountIn += $row->qty;
                                                @endphp
                                                {{ $row->qty }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td class="p-3">
                                                @if ($row->status == 'Out')
                                                @php
                                                $amountOut += $row->qty;
                                                @endphp
                                                {{ $row->qty }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td class="p-3">
                                                @php
                                                $amountBalanceLast = last((array)$row->balancing);
                                                @endphp
                                                {{ $row->balancing }}
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
                                    <thead>
                                        <tr class="tw-text-center">
                                            <th class="p-3 tw-text-center" colspan="2">TOTAL</th>
                                            <th class="p-3">{{ $amountBalance }}</th>
                                            <th class="p-3">{{ $amountIn }}</th>
                                            <th class="p-3">{{ $amountOut }}</th>
                                            <th class="p-3">{{ $amountBalanceLast }}</th>
                                        </tr>
                                    </thead>
                                    @endif

                                </table>
                            </div>
                            <div class="table-responsive p-3">
                                @if ($data == '')

                                @else
                                {{ $data->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#filter_id_barang').select2();
        $('#filter_id_barang').on('change', function (e) {
            var data = $('#filter_id_barang').select2("val");
            @this.set('filter_id_barang', data);
        });
    });

</script>
@endpush
