<div>
    <section class="section">
        <div class="section-header tw-rounded-none lg:tw-rounded-lg tw-shadow-md tw-shadow-gray-300 px-4">
            <h1 class="tw-text-lg mb-1">Laporan Barang Masuk</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card tw-shadow-md tw-shadow-gray-300 tw-rounded-lg">
                        <div class="card-body">
                            <div class="form-group" wire:ignore>
                                <label for="id_user">Nama User</label>
                                <select wire:model="id_user" id="id_user" class="form-control tw-rounded-lg">
                                    <option value="0">-- Select Option --</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" wire:ignore>
                                <label for="id_barang">Nama Barang</label>
                                <select wire:model="id_barang" id="id_barang" class="form-control tw-rounded-lg">
                                    <option value="0">-- Select Option --</option>
                                    @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dari_tanggal">Dari Tanggal</label>
                                <input type="datetime-local" wire:model='dari_tanggal' id="dari_tanggal"
                                    class="form-control tw-rounded-lg">
                            </div>
                            <div class="form-group">
                                <label for="sampai_tanggal">Sampai Tanggal</label>
                                <input type="datetime-local" wire:model='sampai_tanggal' id="sampai_tanggal"
                                    class="form-control tw-rounded-lg">
                            </div>
                            <div class="tw-grid tw-grid-cols-2 tw-gap tw-gap-3">
                                <div>
                                    <a href="{{ route('laporan-excel.barang-masuk', ["id_user" => $this->id_user, "id_barang"=> $this->id_barang,
                                    "dari_tanggal" => $this->dari_tanggal, "sampai_tanggal" =>
                                    $this->sampai_tanggal]) }}" class="btn btn-outline-success tw-w-full"
                                        target="_BLANK">
                                        <i class="far fa-file-excel"></i> EXCEL
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('laporan-pdf.barang-masuk', ["id_user" => $this->id_user, "id_barang"=> $this->id_barang,
                                    "dari_tanggal" => $this->dari_tanggal, "sampai_tanggal" =>
                                    $this->sampai_tanggal]) }}" class="btn btn-outline-danger tw-w-full"
                                        target="_BLANK">
                                        <i class="far fa-file-pdf"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="activities">
                        @forelse ($data as $row)
                        <div class="activity">
                            <div class="activity-icon bg-primary text-white shadow-primary">
                                <i class="far fa-history tw-text-lg"></i>
                            </div>
                            <div class="activity-detail tw-rounded-lg tw-shadow-md tw-shadow-gray-300">
                                <div class="mb-2">
                                    <span class="text-job text-primary">{{ $row->tanggal }}</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" href="#">{{ $row->name }}</a>
                                </div>
                                <p>Menambahkan barang: <b class="tw-text-gray-900">{{ $row->nama_barang }}</b> sebanyak
                                    <b class="tw-text-gray-900">{{ $row->qty }}</b> pcs</p><br />
                                <p>Keterangan: <br /><b class="tw-text-gray-900">{{ $row->keterangan }}</b></p>
                            </div>
                        </div>
                        @empty
                        <center>
                            <img src="{{ asset('images/not-data.png') }}" class="tw-ml-0 lg:tw-ml-36">
                        </center>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')

<script>
    $(document).ready(function () {
        $('#id_user').select2();
        $('#id_barang').select2();
        $('#id_user').on('change', function (e) {
            var data = $('#id_user').select2("val");
            @this.set('id_user', data);
        });
        $('#id_barang').on('change', function (e) {
            var data = $('#id_barang').select2("val");
            @this.set('id_barang', data);
        });
    });

</script>

@endpush
