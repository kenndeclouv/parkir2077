<x-layouts.app title="Kendaraan Masuk">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Catat Kendaraan Masuk</flux:heading>
            <flux:button href="{{ route('transaksi.index') }}" variant="ghost" icon="arrow-left">Kembali</flux:button>
        </div>

        <flux:card>
            <form method="POST" action="{{ route('transaksi.store') }}" class="space-y-5"
                  x-data="{
                      newKendaraan: {{ old('new_kendaraan', '0') === '1' ? 'true' : 'false' }},
                      toggle() {
                          this.newKendaraan = !this.newKendaraan;
                      }
                  }">
                @csrf
                <input type="hidden" name="new_kendaraan" x-bind:value="newKendaraan ? '1' : '0'">

                {{-- ── Pilih kendaraan terdaftar ───────────────── --}}
                <div x-show="!newKendaraan">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <flux:select name="id_kendaraan" label="Kendaraan (Plat Nomor)">
                                <flux:select.option value="">— Pilih Kendaraan —</flux:select.option>
                                @foreach($kendaraans as $k)
                                    <flux:select.option value="{{ $k->id_parkir }}" :selected="old('id_kendaraan') == $k->id_parkir">
                                        {{ $k->plat_nomor }} — {{ ucfirst($k->jenis_kendaraan) }} ({{ $k->pemilik }})
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            @error('id_kendaraan')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- ── Form kendaraan baru (inline) ───────────── --}}
                <div x-show="newKendaraan"
                     class="rounded-xl border border-dashed border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900/40 p-4 space-y-3">
                    <div class="flex items-center gap-1.5 mb-1">
                        <flux:icon name="plus-circle" class="size-4 text-zinc-500" />
                        <flux:text class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Data Kendaraan Baru</flux:text>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <flux:input name="plat_nomor" label="Plat Nomor"
                                placeholder="Contoh: B 1234 XYZ"
                                value="{{ old('plat_nomor') }}" />
                            @error('plat_nomor')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <flux:select name="jenis_kendaraan_baru" label="Jenis Kendaraan">
                                <flux:select.option value="">— Pilih Jenis —</flux:select.option>
                                <flux:select.option value="motor" :selected="old('jenis_kendaraan_baru') === 'motor'">Motor</flux:select.option>
                                <flux:select.option value="mobil" :selected="old('jenis_kendaraan_baru') === 'mobil'">Mobil</flux:select.option>
                                <flux:select.option value="lainnya" :selected="old('jenis_kendaraan_baru') === 'lainnya'">Lainnya</flux:select.option>
                            </flux:select>
                            @error('jenis_kendaraan_baru')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <flux:input name="warna" label="Warna" placeholder="Contoh: Hitam"
                                value="{{ old('warna') }}" />
                            @error('warna')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <flux:input name="pemilik" label="Nama Pemilik" placeholder="Nama pemilik kendaraan"
                                value="{{ old('pemilik') }}" />
                            @error('pemilik')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- ── Toggle button ───────────────────────────── --}}
                <div>
                    <button type="button" @click="toggle()"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline focus:outline-none">
                        <span x-show="!newKendaraan">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Kendaraan belum terdaftar? Tambah baru
                        </span>
                        <span x-show="newKendaraan">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
                            Pilih dari kendaraan terdaftar
                        </span>
                    </button>
                </div>

                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <flux:select name="id_tarif" label="Tarif" required>
                                <flux:select.option value="">— Pilih Tarif —</flux:select.option>
                                @foreach($tarifs as $tarif)
                                    <flux:select.option value="{{ $tarif->id_tarif }}" :selected="old('id_tarif') == $tarif->id_tarif">
                                        {{ ucfirst($tarif->jenis_kendaraan) }} — Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}/jam
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            @error('id_tarif')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <flux:select name="id_area" label="Area Parkir" required>
                                <flux:select.option value="">— Pilih Area —</flux:select.option>
                                @foreach($areas as $area)
                                    <flux:select.option value="{{ $area->id_area }}" :selected="old('id_area') == $area->id_area"
                                        :disabled="$area->terisi >= $area->kapasitas">
                                        {{ $area->nama_area }} ({{ $area->kapasitas - $area->terisi }} slot)
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            @error('id_area')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <flux:input name="waktu_masuk" label="Waktu Masuk" type="datetime-local"
                                value="{{ old('waktu_masuk', now()->format('Y-m-d\TH:i')) }}" required />
                            @error('waktu_masuk')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <flux:button type="submit" variant="primary" icon="printer">
                    Catat Masuk &amp; Cetak Tiket
                </flux:button>
            </form>
        </flux:card>
    </div>
</x-layouts.app>
