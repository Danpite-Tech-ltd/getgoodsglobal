@extends('backEnd.layouts.master')
@section('title', 'Maps')
@section('css')
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .scroll-box {
            max-height: 450px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .list-group-item.active {
            background-color: #90ee90 !important;
            color: black;
            font-weight: bold;
        }

        .count {
            color: red;
            font-weight: bold;
        }

        #map {
            height: 450px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Maps Data List</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-header">
                        <h3>District Orders</h3>
                    </div>
                    <div class="card-body">
                        <div class="scroll-box">
                            <ul class="list-group">
                                @foreach ($districts as $district)
                                    @php
                                        $order_count = App\Models\Order::where('city', $district)->count();
                                    @endphp
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $district }} <span class="count">({{ $order_count }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Map</h3>
                    </div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    @endsection


    @section('script')
        <!-- third party js -->
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js">
        </script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js">
        </script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js">
        </script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js">
        </script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="{{ asset('/public/backEnd/') }}/assets/js/pages/datatables.init.js"></script>
        <!-- third party js ends -->

        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            var map = L.map('map').setView([23.685, 90.3563], 7); // Centered at Bangladesh

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var currentMarker = null;
            var currentPolygon = null;

            function findLocation(placeName, id) {
                var order = null;
                $.ajax({
                    type: 'GET',
                    url: 'citydata/' + id,

                    success: function(data) {
                        order = data;
                    },
                    error: function(error) {
                        console.log('error');
                    }

                });
                // Step 1: Get OSM ID using Nominatim API
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${placeName}&polygon_geojson=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var lat = data[0].lat;
                            var lon = data[0].lon;
                            var osmId = data[0].osm_id;
                            var osmType = data[0].osm_type === 'relation' ? 'relation' : 'way';

                            // Remove previous marker and polygon
                            if (currentMarker) map.removeLayer(currentMarker);
                            if (currentPolygon) map.removeLayer(currentPolygon);

                            // Add marker at center
                            currentMarker = L.marker([lat, lon]).addTo(map)
                                .bindPopup(`<b><h6>${placeName}</h6></b>${order}`).openPopup();

                            // Step 2: Fetch the exact boundary from Overpass API
                            fetch(
                                    `https://overpass-api.de/api/interpreter?data=[out:json];${osmType}(${osmId});(._;>;);out body;`)
                                .then(response => response.json())
                                .then(overpassData => {
                                    var geoJson = osmtogeojson(overpassData);

                                    // Draw full boundary area
                                    currentPolygon = L.geoJSON(geoJson, {
                                        style: {
                                            color: 'green',
                                            fillOpacity: 0.3
                                        }
                                    }).addTo(map);

                                    // Zoom to fit the boundary
                                    map.fitBounds(currentPolygon.getBounds());
                                })
                                .catch(error => console.log("Error fetching boundary:", error));
                        } else {
                            alert("Location not found!");
                        }
                    })
                    .catch(error => console.log("Error fetching location:", error));
            }

            // Include osmtogeojson library to convert OSM data to GeoJSON
            var script = document.createElement('script');
            script.src = "https://rawgit.com/tyrasd/osmtogeojson/gh-pages/osmtogeojson.js";
            document.head.appendChild(script);
        </script>
    @endsection
