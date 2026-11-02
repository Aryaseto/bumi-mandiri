@extends('layouts.app')

@section('title', 'Booking ' . $booking->booking_code . ' | Bumi Mandiri')

@section('content')
<section class="pb-20 pt-28 lg:pt-32">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
                    <x-icon name="arrow-left" :size="15" />
                    Booking saya
                </a>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <h1 class="font-heading text-2xl font-extrabold tracking-tight text-forest sm:text-3xl">{{ $booking->booking_code }}</h1>
                    <x-status-badge :status="$booking->status" />
                </div>
                <p class="mt-2 text-sm text-charcoal/70">Dibuat {{ $booking->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <a href="{{ route('contact.create', ['subject' => 'Booking ' . $booking->booking_code]) }}" class="btn btn-secondary btn-sm">
                <x-icon name="mail" :size="16" />
                Hubungi kami
            </a>
        </div>

        <div class="mt-7">
            <x-account-nav />
        </div>

        @if(session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-nature/25 bg-nature/10 px-4 py-3 text-sm text-nature" role="status">
                <x-icon name="check" :size="18" class="mt-0.5 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px] lg:items-start">
            <div class="space-y-6">
                {{-- Items --}}
                <section class="overflow-hidden rounded-2xl border border-forest/10 bg-white">
                    <div class="border-b border-sand/70 px-5 py-4 sm:px-6">
                        <h2 class="font-heading text-base font-bold text-forest">Aktivitas yang dipesan</h2>
                    </div>
                    <div class="divide-y divide-sand/60">
                        @foreach($booking->items as $item)
                            @php $activity = $item->activity; @endphp
                            <div class="flex items-center gap-4 p-5 sm:p-6">
                                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-sand sm:h-20 sm:w-20">
                                    @if($activity)
                                        <img src="{{ $activity->imageUrl() }}" alt="{{ $activity->name }}"
                                             class="h-full w-full object-cover" width="160" height="160" loading="lazy">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-forest">{{ $activity?->name ?? 'Aktivitas Bumi Mandiri' }}</h3>
                                    <p class="mt-1 text-sm text-charcoal/70">
                                        {{ $item->quantity }} peserta
                                        <span class="text-charcoal/70">&middot;</span>
                                        Rp {{ number_format($item->price ?? ($item->subtotal / max($item->quantity, 1)), 0, ',', '.') }} / orang
                                    </p>
                                    @if($activity)
                                        <a href="{{ route('activities.show', $activity) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-charcoal/70 transition hover:text-forest">
                                            Lihat aktivitas
                                            <x-icon name="arrow-up-right" :size="13" />
                                        </a>
                                    @endif
                                </div>
                                <p class="whitespace-nowrap text-sm font-semibold text-forest">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Visit details --}}
                <section class="rounded-2xl border border-forest/10 bg-white p-5 sm:p-6">
                    <h2 class="font-heading text-base font-bold text-forest">Detail kunjungan</h2>
                    <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                        @foreach([
                            ['label' => 'Tanggal', 'value' => $booking->booking_date->translatedFormat('l, d F Y'), 'icon' => 'calendar'],
                            ['label' => 'Waktu', 'value' => $booking->timeLabel() ?: 'Belum ditentukan', 'icon' => 'clock'],
                            ['label' => 'Jumlah peserta', 'value' => $booking->participants . ' orang', 'icon' => 'users'],
                            ['label' => 'Paket', 'value' => $booking->package?->name ?? 'Tanpa paket', 'icon' => 'package'],
                        ] as $detail)
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sand-light text-forest">
                                    <x-icon :name="$detail['icon']" :size="15" />
                                </span>
                                <div>
                                    <dt class="text-[11px] font-medium uppercase tracking-wider text-charcoal/70">{{ $detail['label'] }}</dt>
                                    <dd class="mt-0.5 text-sm font-medium text-charcoal/80">{{ $detail['value'] }}</dd>
                                </div>
                            </div>
                        @endforeach
                    </dl>

                    @if($booking->notes)
                        <div class="mt-5 rounded-xl bg-sand-light/70 p-4">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-charcoal/70">Catatan Anda</p>
                            <p class="mt-1 text-sm leading-relaxed text-charcoal/70">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </section>

                {{-- Review --}}
                @if($booking->isCompleted() && ! $booking->review)
                    <section class="rounded-2xl border border-forest/10 bg-white p-5 sm:p-6">
                        <h2 class="font-heading text-base font-bold text-forest">Bagikan pengalaman Anda</h2>
                        <p class="mt-1 text-sm text-charcoal/70">Ulasan membantu pengunjung lain memilih aktivitas yang tepat.</p>

                        <form method="POST" action="{{ route('reviews.store', [$booking, $booking->items->first()->activity]) }}" class="mt-5 space-y-4">
                            @csrf
                            <fieldset>
                                <legend class="field-label">Rating</legend>
                                <div class="flex flex-wrap gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" @checked($i === 5) required>
                                            <span class="inline-flex items-center gap-1 rounded-lg border border-sand bg-white px-3 py-2 text-sm font-semibold text-charcoal/70 transition peer-checked:border-earth peer-checked:bg-earth/10 peer-checked:text-earth peer-focus-visible:ring-2 peer-focus-visible:ring-earth/40">
                                                {{ $i }}
                                                <x-icon name="star" :size="13" class="fill-current" />
                                            </span>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating') <p class="field-error">{{ $message }}</p> @enderror
                            </fieldset>

                            <div>
                                <label for="comment" class="field-label">Ulasan</label>
                                <textarea id="comment" name="comment" rows="4" required
                                          placeholder="Ceritakan pengalaman Anda selama berkunjung..."
                                          class="field">{{ old('comment') }}</textarea>
                                @error('comment') <p class="field-error">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Kirim ulasan</button>
                        </form>
                    </section>
                @elseif($booking->review)
                    <section class="rounded-2xl border border-forest/10 bg-white p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="font-heading text-base font-bold text-forest">Ulasan Anda</h2>
                            <span class="inline-flex items-center gap-1 text-sm font-semibold text-earth">
                                {{ $booking->review->rating }}
                                <x-icon name="star" :size="13" class="fill-current" />
                            </span>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-charcoal/70">{{ $booking->review->comment }}</p>
                        <p class="mt-3 text-xs text-charcoal/70">
                            {{ $booking->review->is_approved ? 'Sudah tampil di halaman aktivitas.' : 'Menunggu peninjauan admin.' }}
                        </p>
                    </section>
                @endif
            </div>

            {{-- Summary --}}
            <aside class="space-y-5 lg:sticky lg:top-24">
                <div class="rounded-2xl border border-forest/10 bg-white p-5 sm:p-6">
                    <h2 class="font-heading text-base font-bold text-forest">Ringkasan biaya</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-charcoal/70">Subtotal</dt>
                            <dd class="font-medium">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</dd>
                        </div>
                        @if((float) $booking->discount > 0)
                            <div class="flex justify-between gap-3">
                                <dt class="text-charcoal/70">Diskon</dt>
                                <dd class="font-medium text-nature">- Rp {{ number_format($booking->discount, 0, ',', '.') }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-3 border-t border-sand/70 pt-3">
                            <dt class="font-semibold text-forest">Total</dt>
                            <dd class="font-heading text-lg font-extrabold text-forest">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    @if($booking->payment)
                        <div class="mt-4 rounded-xl bg-sand-light/70 p-4 text-sm">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-charcoal/70">Pembayaran</span>
                                <x-status-badge :status="$booking->payment->payment_status" />
                            </div>
                            <p class="mt-2 text-xs text-charcoal/70">{{ $booking->payment->payment_method }}</p>
                            <p class="mt-1 font-mono text-xs text-charcoal/70">{{ $booking->payment->payment_code }}</p>
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl border border-forest/10 bg-sand-light/60 p-5">
                    <h2 class="font-heading text-sm font-bold text-forest">Butuh perubahan?</h2>
                    <p class="mt-1.5 text-sm leading-relaxed text-charcoal/70">
                        Untuk mengubah tanggal atau jumlah peserta, hubungi kami dengan menyertakan kode booking.
                    </p>
                    <a href="{{ route('contact.create', ['subject' => 'Perubahan booking ' . $booking->booking_code]) }}"
                       class="btn btn-secondary btn-sm mt-3 w-full">
                        Ajukan perubahan
                    </a>

                    @if(in_array($booking->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="mt-3"
                              onsubmit="return confirm('Batalkan booking ini? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf
                            <button type="submit" class="w-full rounded-lg py-2 text-xs font-semibold text-red-700 transition hover:bg-red-50">
                                Batalkan booking
                            </button>
                        </form>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
