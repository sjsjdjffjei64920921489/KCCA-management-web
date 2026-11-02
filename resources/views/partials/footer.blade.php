<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            {{-- Brand --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center text-white font-black text-sm">KC</div>
                    <div>
                        <div class="font-bold text-white">KCCA Venues</div>
                        <div class="text-xs text-gray-400">Kampala Capital City Authority</div>
                    </div>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed max-w-sm">
                    Your gateway to booking community halls, sports venues and public spaces across Lugogo, Kamwokya and Kabalagala, Kampala.
                </p>
                <div class="mt-4 flex gap-3">
                    <a href="tel:+2560800110110" class="text-xs text-gray-400 hover:text-green-400 transition">📞 0800 110 110</a>
                    <span class="text-gray-600">·</span>
                    <a href="mailto:venues@kcca.go.ug" class="text-xs text-gray-400 hover:text-green-400 transition">✉️ venues@kcca.go.ug</a>
                </div>
            </div>

            {{-- Quick links --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-4">Browse by Area</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('venues.index', ['location' => 'Lugogo']) }}" class="hover:text-green-400 transition">📍 Lugogo</a></li>
                    <li><a href="{{ route('venues.index', ['location' => 'Kamwokya']) }}" class="hover:text-green-400 transition">📍 Kamwokya</a></li>
                    <li><a href="{{ route('venues.index', ['location' => 'Kabalagala']) }}" class="hover:text-green-400 transition">📍 Kabalagala</a></li>
                </ul>
            </div>

            {{-- Venue types --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-4">Venue Types</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('venues.index', ['type' => 'community_hall']) }}" class="hover:text-green-400 transition">🏛️ Community Halls</a></li>
                    <li><a href="{{ route('venues.index', ['type' => 'sports_venue']) }}" class="hover:text-green-400 transition">⚽ Sports Venues</a></li>
                    <li><a href="{{ route('venues.index', ['type' => 'conference_center']) }}" class="hover:text-green-400 transition">🏢 Conference Centres</a></li>
                    <li><a href="{{ route('venues.index', ['type' => 'outdoor_space']) }}" class="hover:text-green-400 transition">🌳 Outdoor Spaces</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <p>© {{ date('Y') }} Kampala Capital City Authority. All rights reserved.</p>
            <p>Managed by KCCA Directorate of Public Health & Environment</p>
        </div>
    </div>
</footer>
