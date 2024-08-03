<div>
    <div class="container mx-auto">
        <div class="bg-white p-6 mt-3 rounded-lg shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Nama Pegawai : </strong> {{ Auth::user()->name }}</p>
                        <p><strong>Kantor : </strong> {{ $schedule->office->name }}</p>
                        <p><strong>Shift : </strong> {{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} -
                            {{ $schedule->shift->end_time }} WIB)</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-l font-bold">Jam Masuk</h4>
                            <p>08.50</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-l font-bold">Jam Keluar</h4>
                            <p>17.05</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-2">Presensi</h2>
                    <div id="map" class="mb-4 rounded-lg border border-gray-300" wire:ignore></div>
                    <button type="button" onclick="tagLocation()" class="px-4 py-2 bg-blue-500 font-semibold text-white rounded">Tag Location</button>
                    @if ($insideRadius)
                        <button type="button" class="px-4 py-2 bg-green-500 font-semibold text-white rounded">Submit Presensi</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        let map;
        let lat;
        let lng;
        const office = [{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}];
        const radius = {{ $schedule->office->radius }};
        let marker;
        let component;

        document.addEventListener('livewire:initialized', function() {
            component = @this;
            map = L.map('map').setView([{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
            const circle = L.circle(office, {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
    
            circle.bindPopup("Radius presensi");
        })


        function tagLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);

                    marker.bindPopup("Lokasi saya");

                    if (isWithinRadius(lat, lng, office, radius)) {
                        alert('Anda dalam radius');
                        component.set('insideRadius', true);
                    } else {
                        alert('Anda di luar radius');
                    }
                })
            } else {
                alert('Tidak bisa get location');
            }
        }

        function isWithinRadius(lat, lng, center, radius) {
            let distance = map.distance([lat, lng], center);
            return distance <= radius;
        }
    </script>
</div>
