@extends('layouts.app')

@section('title', 'Pesan ' . $activity->name . ' | Bumi Mandiri')

@section('content')
<section class="pb-20 pt-28 lg:pt-32"
    x-data="bookingForm({
        activityPrice: {{ (float) $activity->price }},
        packages: {{ $packages->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float) $p->base_price])->toJson() }}
    })">

    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <a href="{{ route('activities.show', $activity) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
                    <x-icon name="arrow-left" :size="15" />
                    Kembali ke aktivitas
                </a>
                <h1 class="mt-3 max-w-2xl text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Pesan {{ $activity->name }}.</h1>
                <p class="mt-2 text-sm text-charcoal/70">Lengkapi empat langkah singkat, lalu kirim permintaan booking.</p>
            </div>
            @if($activity->category)
                <span class="rounded-full border border-forest/15 bg-white px-3 py-1.5 text-xs font-semibold text-forest">{{ $activity->category->name }}</span>
            @endif
        </div>

        {{-- Stepper --}}
        <ol class="no-scrollbar mt-8 flex items-center gap-1.5 pb-1 sm:gap-3" aria-label="Langkah pemesanan">
            <template x-for="(label, index) in ['Aktivitas', 'Jadwal', 'Peserta', 'Konfirmasi']" :key="index">
                <li class="flex shrink-0 items-center gap-1.5 sm:gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition"
                          :class="step >= index + 1 ? 'bg-forest text-cream' : 'bg-sand text-charcoal/70'"
                          x-text="index + 1"></span>
                    <span class="text-[11px] font-semibold transition sm:text-sm"
                          :class="step >= index + 1 ? 'text-forest' : 'text-charcoal/70'"
                          x-text="label"></span>
                    <span class="mx-1 hidden h-px w-8 bg-sand sm:block" x-show="index < 3"></span>
                </li>
            </template>
        </ol>

        @if($errors->any())
            <div class="mt-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('booking.store', $activity) }}"
              @submit="if (step < 4) { $event.preventDefault(); step = 4 }"
              class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px] lg:items-start">
            @csrf

            <div class="rounded-2xl border border-forest/10 bg-white">

                {{-- Step 1 --}}
                <div x-show="step === 1" class="p-5 sm:p-7">
                    <p class="eyebrow text-earth">Langkah 1</p>
                    <h2 class="mt-2 font-heading text-xl font-bold text-forest">Aktivitas yang dipilih</h2>

                    <div class="mt-5 flex flex-col gap-5 sm:flex-row">
                        <div class="h-40 w-full shrink-0 overflow-hidden rounded-xl bg-sand sm:h-32 sm:w-44">
                            <img src="{{ $activity->imageUrl() }}" alt="{{ $activity->name }}"
                                 class="h-full w-full object-cover" width="600" height="440">
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-heading text-lg font-bold text-forest">{{ $activity->name }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-charcoal/70">{{ $activity->short_description }}</p>
                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2 text-xs text-charcoal/70">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-icon name="clock" :size="14" />
                                    {{ $activity->durationLabel() }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <x-icon name="users" :size="14" />
                                    {{ $activity->capacity }} peserta
                                </span>
                                @if($activity->destination)
                                    <span class="inline-flex items-center gap-1.5">
                                        <x-icon name="map-pin" :size="14" />
                                        {{ $activity->destination->name }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-3 font-heading text-lg font-extrabold text-forest">
                                Rp {{ number_format($activity->price, 0, ',', '.') }}
                                <span class="font-body text-xs font-normal text-charcoal/70">/ orang</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl bg-sand-light/70 p-4 text-xs leading-relaxed text-charcoal/70">
                        Harga akhir dihitung otomatis sesuai jumlah peserta dan paket yang dipilih. Permintaan booking akan dikonfirmasi oleh tim Bumi Mandiri.
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="step = 2" class="btn btn-primary">
                            Lanjut ke jadwal
                            <x-icon name="arrow-right" :size="16" />
                        </button>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div x-show="step === 2" x-cloak class="p-5 sm:p-7">
                    <p class="eyebrow text-earth">Langkah 2</p>
                    <h2 class="mt-2 font-heading text-xl font-bold text-forest">Tentukan jadwal</h2>
                    <p class="mt-1 text-sm text-charcoal/70">Pilih tanggal kunjungan. Waktu bersifat perkiraan dan bisa disesuaikan kemudian.</p>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="booking_date" class="field-label">Tanggal kunjungan</label>
                            <input id="booking_date" type="date" name="booking_date" x-model="bookingDate" required
                                   min="{{ now()->toDateString() }}" class="field">
                        </div>
                        <div>
                            <label for="booking_time" class="field-label">Perkiraan waktu <span class="font-normal text-charcoal/70">(opsional)</span></label>
                            <input id="booking_time" type="time" name="booking_time" x-model="bookingTime" class="field">
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between gap-3">
                        <button type="button" @click="step = 1" class="inline-flex items-center gap-1.5 text-sm font-semibold text-charcoal/70 transition hover:text-forest">
                            <x-icon name="arrow-left" :size="15" />
                            Kembali
                        </button>
                        <button type="button" @click="if (bookingDate) { step = 3 }"
                                :disabled="! bookingDate"
                                class="btn btn-primary disabled:cursor-not-allowed disabled:opacity-40">
                            Lanjut ke peserta
                            <x-icon name="arrow-right" :size="16" />
                        </button>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div x-show="step === 3" x-cloak class="p-5 sm:p-7">
                    <p class="eyebrow text-earth">Langkah 3</p>
                    <h2 class="mt-2 font-heading text-xl font-bold text-forest">Peserta dan paket</h2>

                    <div class="mt-6 space-y-5">
                        <div>
                            <label for="participants" class="field-label">Jumlah peserta</label>
                            <input id="participants" type="number" name="participants" x-model.number="participants" min="1" required class="field">
                            <p class="mt-1.5 text-xs text-charcoal/70">Kapasitas aktivitas {{ $activity->capacity }} peserta per sesi.</p>
                        </div>

                        <div>
                            <label for="package_id" class="field-label">Paket <span class="font-normal text-charcoal/70">(opsional)</span></label>
                            <select id="package_id" name="package_id" x-model="packageId" class="field">
                                <option value="">Tanpa paket</option>
                                <template x-for="pkg in packages" :key="pkg.id">
                                    <option :value="pkg.id" x-text="pkg.name + ' · Rp ' + pkg.price.toLocaleString('id-ID')"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="field-label">Catatan <span class="font-normal text-charcoal/70">(opsional)</span></label>
                            <textarea id="notes" name="notes" rows="3" class="field"
                                      placeholder="Kebutuhan khusus, jumlah pendamping, atau permintaan lain">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between gap-3">
                        <button type="button" @click="step = 2" class="inline-flex items-center gap-1.5 text-sm font-semibold text-charcoal/70 transition hover:text-forest">
                            <x-icon name="arrow-left" :size="15" />
                            Kembali
                        </button>
                        <button type="button" @click="if (participants > 0) { step = 4 }"
                                class="btn btn-primary disabled:cursor-not-allowed disabled:opacity-40"
                                :disabled="participants < 1">
                            Lanjut ke konfirmasi
                            <x-icon name="arrow-right" :size="16" />
                        </button>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div x-show="step === 4" x-cloak class="p-5 sm:p-7">
                    <p class="eyebrow text-earth">Langkah 4</p>
                    <h2 class="mt-2 font-heading text-xl font-bold text-forest">Periksa sebelum mengirim</h2>
                    <p class="mt-1 text-sm text-charcoal/70">Pastikan tanggal dan jumlah peserta sudah benar.</p>

                    <dl class="mt-6 divide-y divide-sand/60 rounded-xl border border-sand/80">
                        <div class="flex items-center justify-between gap-4 px-4 py-3">
                            <dt class="text-sm text-charcoal/70">Aktivitas</dt>
                            <dd class="text-right text-sm font-semibold text-forest">{{ $activity->name }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 px-4 py-3">
                            <dt class="text-sm text-charcoal/70">Tanggal</dt>
                            <dd class="text-right text-sm font-semibold text-forest" x-text="bookingDate ? new Date(bookingDate + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Belum dipilih'"></dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 px-4 py-3">
                            <dt class="text-sm text-charcoal/70">Waktu</dt>
                            <dd class="text-right text-sm font-semibold text-forest" x-text="bookingTime || 'Fleksibel'"></dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 px-4 py-3">
                            <dt class="text-sm text-charcoal/70">Peserta</dt>
                            <dd class="text-right text-sm font-semibold text-forest" x-text="participants + ' orang'"></dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 px-4 py-3">
                            <dt class="text-sm text-charcoal/70">Paket</dt>
                            <dd class="text-right text-sm font-semibold text-forest" x-text="selectedPackage ? selectedPackage.name : 'Tanpa paket'"></dd>
                        </div>
                    </dl>

                    <div class="mt-5 rounded-xl bg-sand-light/70 p-4 text-xs leading-relaxed text-charcoal/70">
                        Dengan mengirim permintaan ini, Anda menyetujui bahwa ketersediaan tanggal akan dikonfirmasi oleh tim Bumi Mandiri melalui kontak yang terdaftar.
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <button type="button" @click="step = 3" class="inline-flex items-center justify-center gap-1.5 text-sm font-semibold text-charcoal/70 transition hover:text-forest">
                            <x-icon name="arrow-left" :size="15" />
                            Ubah peserta
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Kirim permintaan booking
                            <x-icon name="arrow-right" :size="16" />
                        </button>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <aside class="rounded-2xl border border-forest/10 bg-forest p-5 text-cream sm:p-6 lg:sticky lg:top-24">
                <p class="eyebrow text-cream/70">Ringkasan</p>
                <h2 class="mt-2 font-heading text-lg font-bold">{{ $activity->name }}</h2>

                <dl class="mt-5 space-y-3 text-sm">
                    <div class="flex justify-between gap-3 text-cream/70">
                        <dt>Harga per orang</dt>
                        <dd>Rp {{ number_format($activity->price, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3 text-cream/70">
                        <dt>Peserta</dt>
                        <dd x-text="participants || 0"></dd>
                    </div>
                    <div class="flex justify-between gap-3 text-cream/70">
                        <dt>Subtotal aktivitas</dt>
                        <dd x-text="'Rp ' + activitySubtotal.toLocaleString('id-ID')"></dd>
                    </div>
                    <div class="flex justify-between gap-3 text-cream/70" x-show="packageId">
                        <dt>Paket</dt>
                        <dd x-text="'Rp ' + packagePrice.toLocaleString('id-ID')"></dd>
                    </div>
                    <div class="flex justify-between gap-3 text-cream/70" x-show="discount > 0">
                        <dt>Diskon grup 5%</dt>
                        <dd x-text="'- Rp ' + discount.toLocaleString('id-ID')"></dd>
                    </div>
                    <div class="flex items-end justify-between gap-3 border-t border-cream/15 pt-4">
                        <dt class="text-sm font-medium text-cream/80">Perkiraan total</dt>
                        <dd class="font-heading text-xl font-extrabold text-cream" x-text="'Rp ' + total.toLocaleString('id-ID')"></dd>
                    </div>
                </dl>

                <p class="mt-4 border-t border-cream/15 pt-4 text-xs leading-relaxed text-cream/70">
                    Belum ada pembayaran pada langkah ini. Tim kami akan mengonfirmasi ketersediaan dan detail pembayaran.
                </p>
                <ul class="mt-4 space-y-2 text-xs text-cream/70">
                    <li class="flex items-start gap-2">
                        <x-icon name="check" :size="13" class="mt-0.5 shrink-0 text-cream/70" />
                        Konfirmasi dari tim Bumi Mandiri
                    </li>
                    <li class="flex items-start gap-2">
                        <x-icon name="check" :size="13" class="mt-0.5 shrink-0 text-cream/70" />
                        Jadwal bisa didiskusikan
                    </li>
                </ul>
            </aside>
        </form>
    </div>
</section>

@push('scripts')
<script>
function bookingForm({ activityPrice, packages }) {
    return {
        step: 1,
        bookingDate: '',
        bookingTime: '',
        participants: 1,
        packageId: '',
        packages: packages,

        get activitySubtotal() {
            return activityPrice * (this.participants || 0);
        },
        get selectedPackage() {
            return this.packages.find(pkg => pkg.id == this.packageId) || null;
        },
        get packagePrice() {
            return this.selectedPackage ? this.selectedPackage.price : 0;
        },
        get subtotal() {
            return this.activitySubtotal + this.packagePrice;
        },
        get discount() {
            return this.participants >= 20 ? this.subtotal * 0.05 : 0;
        },
        get total() {
            return this.subtotal - this.discount;
        },
    }
}
</script>
@endpush
@endsection
