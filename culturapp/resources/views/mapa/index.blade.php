@extends('layouts.app')

@section('title', 'Mapa Cultural - CulturApp')

@section('styles')
<style>
    #mapa {
        height: 70vh;
        min-height: 500px;
    }
    .marker-popup {
        max-width: 250px;
    }
    .marker-popup img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 5px;
    }
    .legend {
        background: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .legend-icon {
        width: 20px;
        height: 20px;
        margin-right: 8px;
        border-radius: 50%;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-map-marked-alt text-primary"></i> Mapa Cultural</h1>
            <p class="text-muted">Explora las tradiciones, eventos y sitios culturales de la región</p>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex gap-3 flex-wrap">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showTradiciones" checked>
                                    <label class="form-check-label" for="showTradiciones">
                                        <span class="legend-icon bg-primary d-inline-block me-1"></span> Tradiciones
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showEventos" checked>
                                    <label class="form-check-label" for="showEventos">
                                        <span class="legend-icon bg-success d-inline-block me-1"></span> Eventos
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showSitios" checked>
                                    <label class="form-check-label" for="showSitios">
                                        <span class="legend-icon bg-warning d-inline-block me-1"></span> Sitios Culturales
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="btn-group" role="group">
                                <button id="centrarMapa" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-crosshairs"></i> Centrar en Hidalgo
                                </button>
                                <button id="verTodos" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-expand"></i> Ver todos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mapa -->
    <div class="row">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div id="mapa"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5>Cómo usar el mapa</h5>
                    <ul class="mb-0">
                        <li>Haz clic en los marcadores para ver más información</li>
                        <li>Usa los filtros para mostrar/ocultar diferentes tipos de contenido</li>
                        <li>Haz zoom y arrastra para explorar la región</li>
                        <li>Los colores indican el tipo de contenido cultural</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Estadísticas</h5>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 text-primary">{{ count($tradiciones) }}</div>
                            <small>Tradiciones</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 text-success">{{ count($eventos) }}</div>
                            <small>Eventos</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 text-warning">{{ count($sitiosCulturales) }}</div>
                            <small>Sitios</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Inicializar el mapa centrado en Hidalgo
const mapa = L.map('mapa').setView([20.0530, -99.3427], 10);

// Agregar capa de mapa
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(mapa);

// Grupos de capas para cada tipo de contenido
const tradicionesLayer = L.layerGroup().addTo(mapa);
const eventosLayer = L.layerGroup().addTo(mapa);
const sitiosLayer = L.layerGroup().addTo(mapa);

// Array para almacenar todos los marcadores para el "ver todos"
let allMarkers = [];

// Iconos personalizados
const tradicionIcon = L.divIcon({
    html: '<div style="background: #0d6efd; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px;"><i class="fas fa-star"></i></div>',
    iconSize: [20, 20],
    className: 'custom-div-icon'
});

const eventoIcon = L.divIcon({
    html: '<div style="background: #198754; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px;"><i class="fas fa-calendar"></i></div>',
    iconSize: [20, 20],
    className: 'custom-div-icon'
});

const sitioIcon = L.divIcon({
    html: '<div style="background: #ffc107; color: black; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px;"><i class="fas fa-landmark"></i></div>',
    iconSize: [20, 20],
    className: 'custom-div-icon'
});

// Datos de tradiciones
const tradiciones = @json($tradiciones);
tradiciones.forEach(function(tradicion) {
    if (tradicion.latitud && tradicion.longitud) {
        const marker = L.marker([tradicion.latitud, tradicion.longitud], {
            icon: tradicionIcon
        });
        
        const popupContent = `
            <div class="marker-popup">
                ${tradicion.imagen_principal ? 
                    `<img src="/storage/${tradicion.imagen_principal}" alt="${tradicion.titulo}" class="mb-2">` : 
                    ''
                }
                <h6>${tradicion.titulo}</h6>
                <p class="small mb-2">${tradicion.descripcion.substring(0, 100)}...</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary small">${tradicion.categoria.nombre}</span>
                    <a href="/tradiciones/${tradicion.id}" class="btn btn-sm btn-outline-primary">Ver más</a>
                </div>
            </div>
        `;
        
        marker.bindPopup(popupContent);
        tradicionesLayer.addLayer(marker);
        allMarkers.push(marker);
    }
});

// Datos de eventos
const eventos = @json($eventos);
eventos.forEach(function(evento) {
    if (evento.latitud && evento.longitud) {
        const marker = L.marker([evento.latitud, evento.longitud], {
            icon: eventoIcon
        });
        
        const fechaEvento = new Date(evento.fecha_inicio).toLocaleDateString('es-ES');
        
        const popupContent = `
            <div class="marker-popup">
                ${evento.imagen ? 
                    `<img src="/storage/${evento.imagen}" alt="${evento.titulo}" class="mb-2">` : 
                    ''
                }
                <h6>${evento.titulo}</h6>
                <p class="small mb-2">${evento.descripcion.substring(0, 100)}...</p>
                <p class="small mb-2"><i class="fas fa-calendar"></i> ${fechaEvento}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-success small">Evento</span>
                    <a href="/eventos/${evento.id}" class="btn btn-sm btn-outline-success">Ver más</a>
                </div>
            </div>
        `;
        
        marker.bindPopup(popupContent);
        eventosLayer.addLayer(marker);
        allMarkers.push(marker);
    }
});

// Datos de sitios culturales
const sitiosCulturales = @json($sitiosCulturales);
sitiosCulturales.forEach(function(sitio) {
    if (sitio.latitud && sitio.longitud) {
        const marker = L.marker([sitio.latitud, sitio.longitud], {
            icon: sitioIcon
        });
        
        const popupContent = `
            <div class="marker-popup">
                ${sitio.imagen ? 
                    `<img src="/storage/${sitio.imagen}" alt="${sitio.nombre}" class="mb-2">` : 
                    ''
                }
                <h6>${sitio.nombre}</h6>
                <p class="small mb-2">${sitio.descripcion.substring(0, 100)}...</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-warning text-dark small">${sitio.tipo}</span>
                    <button class="btn btn-sm btn-outline-warning">Ver más</button>
                </div>
            </div>
        `;
        
        marker.bindPopup(popupContent);
        sitiosLayer.addLayer(marker);
        allMarkers.push(marker);
    }
});

// Controles de filtros
document.getElementById('showTradiciones').addEventListener('change', function() {
    if (this.checked) {
        mapa.addLayer(tradicionesLayer);
    } else {
        mapa.removeLayer(tradicionesLayer);
    }
});

document.getElementById('showEventos').addEventListener('change', function() {
    if (this.checked) {
        mapa.addLayer(eventosLayer);
    } else {
        mapa.removeLayer(eventosLayer);
    }
});

document.getElementById('showSitios').addEventListener('change', function() {
    if (this.checked) {
        mapa.addLayer(sitiosLayer);
    } else {
        mapa.removeLayer(sitiosLayer);
    }
});

// Botón para centrar el mapa
document.getElementById('centrarMapa').addEventListener('click', function() {
    mapa.setView([20.0530, -99.3427], 10);
});

// Botón para ver todos los marcadores
document.getElementById('verTodos').addEventListener('click', function() {
    if (allMarkers.length > 0) {
        const group = new L.featureGroup(allMarkers);
        mapa.fitBounds(group.getBounds().pad(0.1));
    }
});

// Verificar parámetros URL para centrar en coordenadas específicas
const urlParams = new URLSearchParams(window.location.search);
const lat = urlParams.get('lat');
const lng = urlParams.get('lng');

if (lat && lng) {
    mapa.setView([parseFloat(lat), parseFloat(lng)], 15);
    
    // Agregar marcador temporal para la ubicación específica
    const tempMarker = L.marker([parseFloat(lat), parseFloat(lng)])
        .addTo(mapa)
        .bindPopup('Ubicación seleccionada')
        .openPopup();
}
</script>
@endsection