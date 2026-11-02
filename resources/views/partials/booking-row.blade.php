@php $compact = $compact ?? false; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 {{ $compact ? 'opacity-80' : '' }} hover:shadow-md transition-shadow">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        <div class="flex items-start gap-4 flex-1 min-w-0">
            {{-- Icon --}}
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-xl shrink-0">
                @switch($booking->venue->type)
                    @case('sports_venue') ⚽ @break
                    @case('community_hall') 🏛️ @break
                    @case('conference_center') 🏢 @break
                    @case('outdoor_space') 🌳 @break
                    @default 🏟️
                @endswitch
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-0.5">
                    <h3 class="font-bold text-gray-900 text-sm truncate">{{ $booking->venue->name }}</h3>
                    <span class="
                        @if($booking->status === 'confirmed') badge-success
                        @elseif($booking->status === 'pending') badge-warning
                        @elseif($booking->status === 'cancelled') badge-danger
                        @else badge-secondary @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mb-1">
                    📍 {{ $booking->venue->location }} · {{ ucwords(str_replace('_', ' ', $booking->event_type)) }}
                </p>
                <div class="flex flex-wrap gap-3 text-xs text-gray-600">
                    <span>📅 {{ $booking->event_date->format('D, d M Y') }}</span>
                    <span>⏰ {{ substr($booking->start_time,0,5) }} – {{ substr($booking->end_time,0,5) }}</span>
                    <span>👥 {{ number_format($booking->expected_guests) }} guests</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:items-end gap-2 shrink-0">
            <div class="font-bold text-green-700 text-sm">{{ $booking->formatted_total }}</div>
            <div class="text-xs text-gray-400 font-mono">{{ $booking->reference_number }}</div>
            <div class="flex gap-2">
                <a href="{{ route('bookings.show', $booking->reference_number) }}"
                   class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg font-semibold text-xs bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 transition">View</a>
                @if($booking->canBeCancelled())
                    <button onclick="openCancelModal('{{ $booking->reference_number }}')"
                            class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg font-semibold text-xs bg-red-600 hover:bg-red-700 text-white transition">Cancel</button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Cancel modal (per booking) --}}
@if($booking->canBeCancelled())
<div id="cancel-modal-{{ $booking->reference_number }}"
     class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
        <h3 class="font-bold text-gray-900 text-lg mb-2">Cancel Booking</h3>
        <p class="text-sm text-gray-500 mb-1">Reference: <strong>{{ $booking->reference_number }}</strong></p>
        <p class="text-sm text-gray-500 mb-4">{{ $booking->venue->name }} – {{ $booking->event_date->format('d M Y') }}</p>
        <form method="POST" action="{{ route('bookings.cancel', $booking->reference_number) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="reason-{{ $booking->reference_number }}">Reason for cancellation <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="cancellation_reason" id="reason-{{ $booking->reference_number }}"
                          rows="3" class="form-input resize-none"
                          placeholder="Tell us why you're cancelling…"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-red-600 hover:bg-red-700 text-white transition-all duration-200 flex-1">Yes, Cancel Booking</button>
                <button type="button" onclick="closeCancelModal('{{ $booking->reference_number }}')"
                        class="btn-secondary flex-1">Keep Booking</button>
            </div>
        </form>
    </div>
</div>
<script>
function openCancelModal(ref)  { document.getElementById('cancel-modal-' + ref).classList.remove('hidden'); }
function closeCancelModal(ref) { document.getElementById('cancel-modal-' + ref).classList.add('hidden'); }
</script>
@endif
