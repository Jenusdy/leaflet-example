<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <title>TopoJson Example</title>

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .leaflet-container {
            height: 400px;
            width: 600px;
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>
<body>
    <div id="map" style="width: 100%; height: 100%;"></div>

    <script>
        // Melakukan load basemap google street dengan maksimal zoom 10
        var GOOGLE_HYBRID_TILE = 'http://mt0.google.com/vt/lyrs=y&hl=en&x={x}&y={y}&z={z}';
        var GOOGLE_STREET_TILE = 'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}';
        var GOOGLE_HYBRID = L.tileLayer(GOOGLE_HYBRID_TILE, { maxZoom: 10, attribution: 'Google Hybrid'});
        var GOOGLE_STREET = L.tileLayer(GOOGLE_STREET_TILE, { maxZoom: 10, attribution: 'Google Street'});
        var NO_BASEMAP = L.tileLayer('', { maxZoom: 10, attribution: 'No Basemap'});
        var baseLayers = {
            "Google Hybrid": GOOGLE_HYBRID,
            "Google Street": GOOGLE_STREET,
            "No Basemap": NO_BASEMAP
        };
        // Inisialisasi variabel Map -> Setting lokasi awal dan zoom
        var map = L.map('map',{
            layers: [GOOGLE_HYBRID]
        }).setView([-6.200000, 120.816666], 5);
        L.control.layers(baseLayers).addTo(map);

        // Ini contoh response backend dari Yii yang akan ditampilkan di Map
        var response = [{"idkec":"8108010004","progres":23},{"idkec":"8108011004","progres":87},{"idkec":"8108012003","progres":99},{"idkec":"8108013006","progres":49},{"idkec":"8108020001","progres":87},{"idkec":"8108021002","progres":81},{"idkec":"8108022001","progres":18},{"idkec":"8108030006","progres":7},{"idkec":"8108041006","progres":62},{"idkec":"8108042004","progres":4},{"idkec":"8108050007","progres":42},{"idkec":"8108060002","progres":86},{"idkec":"8108070004","progres":80},{"idkec":"8108071006","progres":65},{"idkec":"8108080008","progres":10},{"idkec":"8108081011","progres":58},{"idkec":"8108082002","progres":15}];

        // Fungsi Untuk Menampilkan Feature Tiap Map
        function onEachFeature(feature, layer){
            for (let i = 0; i < response.length; i++) {
                if(feature.properties.idbs.slice(0,10) === response[i].idkec){
                    feature.properties.progres = response[i].progres;
                }
            }
            layer.bindPopup(feature.properties.nmkec + " : " + feature.properties.progres + "%");
            layer.setStyle({
                fillColor: getColor(feature.properties.progres)
            });
        }

        // Digunakan Menampilkan Warna Sesuai Progress
        function getColor(d) {
            return d == 100 ? '#00FF7F' :
                d > 80  ? '#3BCA6D' :
                    d > 60  ? '#77945C' :
                        d > 40  ? '#B25F4A' : '#ED2938';
        }

        // Memanggil Kecamatan.geojson dan menampilkan di peta
        $.getJSON("assets/kecamatan.geojson", function (data) {
            let peta = L.geoJSON(data,{
                onEachFeature: onEachFeature,
            }).addTo(map);
            map.fitBounds(peta.getBounds());
        });

    </script>
</body>
</html>