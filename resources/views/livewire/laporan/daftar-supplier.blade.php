<div>
    <section class="section">
        <div class="section-header tw-rounded-none lg:tw-rounded-lg tw-shadow-md tw-shadow-gray-300 px-4">
            <h1 class="tw-text-lg mb-1">Laporan Daftar Supplier</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="tw-text-base tw-font-bold tw-text-black">Export Daftar Supplier</h4>
                            <br>
                            <p>Menampilkan seluruh data nama, nomor hp, alamat, dan deskripsi supplier.</p>
                            <p>Pilih Export Data:</p>
                            <div class="tw-flex tw-mt-4 tw-ml-auto">
                                <a href="{{ route('laporan-excel.daftar-supplier') }}" target="_BLANK"
                                    class="btn btn-success">
                                    <i class="far fa-file-excel"></i> Export Excel
                                </a>
                                <a href="{{ route('laporan-pdf.daftar-supplier') }}" target="_BLANK"
                                    class="btn btn-danger tw-ml-3">
                                    <i class="far fa-file-pdf"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
