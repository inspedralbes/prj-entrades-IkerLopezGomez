<template>
  <div class="p-6 md:p-10 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-blue-50/50 border border-gray-100">
      
      <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
        <div>
          <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-2">Escull el teu lloc</p>
          <h1 class="text-4xl font-black text-gray-900 tracking-tight">{{ movie.titol || 'Selecció de Seients' }}</h1>
        </div>
        <NuxtLink to="/" class="group flex items-center gap-2 bg-gray-50 px-6 py-3 rounded-2xl text-sm font-black text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition-all active:scale-95">
          <span class="group-hover:-translate-x-1 transition-transform">←</span> Tornar al Catàleg
        </NuxtLink>
      </div>

      <div class="relative mb-20">
        <div class="w-full h-3 bg-gradient-to-r from-gray-200 via-gray-400 to-gray-200 rounded-full shadow-[0_20px_40px_-5px_rgba(0,0,0,0.1)]"></div>
        <p class="text-center text-[10px] font-black uppercase tracking-[0.5em] text-gray-400 mt-6">Pantalla</p>
      </div>

      <div v-if="carregant" class="flex flex-col items-center justify-center py-20 text-gray-300">
        <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
        <p class="text-[10px] font-black uppercase tracking-widest">Carregant sala...</p>
      </div>

      <div v-else class="flex flex-col items-center">
        <div class="grid grid-cols-8 gap-3 md:gap-4 mb-20">
          <div v-for="seient in seients" :key="seient.id"
               @click="canviarSeleccio(seient)"
               :class="['w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-[10px] md:text-xs font-black transition-all cursor-pointer select-none active:scale-90', 
                        obteEstilSeient(seient)]">
            {{ seient.row }}{{ seient.number }}
          </div>
        </div>

        <div class="flex flex-col gap-8 w-full items-center">
            <div class="grid grid-cols-3 gap-8 p-8 bg-gray-50 rounded-[2rem] w-full max-w-lg">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-8 h-8 bg-white border-2 border-gray-200 rounded-xl shadow-sm"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Lliure</span>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-8 h-8 bg-amber-400 rounded-xl shadow-lg shadow-amber-100"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Reservat</span>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-8 h-8 bg-rose-500 rounded-xl shadow-lg shadow-rose-100"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Ocupat</span>
                </div>
            </div>

            <div v-if="seleccionats.length > 0" class="w-full flex flex-col items-center gap-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <p class="text-sm font-black text-gray-900 uppercase tracking-widest">
                    Has seleccionat <span class="text-blue-600">{{ seleccionats.length }}</span> seients (màxim 5)
                </p>
                <button @click="confirmarCompra" class="bg-blue-600 text-white px-10 py-5 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-2xl shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all text-sm">
                    Confirmar Compra
                </button>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRoute } from '#app';
import { io } from 'socket.io-client';

export default {
  setup: function() {
    var route = useRoute();
    var movieId = route.params.id;
    var movie = ref({});
    var seients = ref([]);
    var seleccionats = ref([]);
    var carregant = ref(true);
    var socket = null;

    var carregarDades = function() {
      carregant.value = true;
      
      // Carregar detalls de la pel·lícula
      fetch('http://localhost:8000/api/movies/' + movieId)
        .then(function(res) { return res.json(); })
        .then(function(data) { movie.value = data; })
        .catch(function(err) { console.error('Error pel·lícula:', err); });

      // Carregar seients
      fetch('http://localhost:8000/api/movies/' + movieId + '/seats')
        .then(function(res) { return res.json(); })
        .then(function(data) { seients.value = data; })
        .catch(function(err) { console.error('Error seients:', err); })
        .finally(function() { carregant.value = false; });
    };

    var inicialitzarSocket = function() {
        socket = io('http://localhost:3000');
        
        socket.on('actualitzacio_seient', function(dades) {
            if (dades.movie_id == movieId) {
                var index = seients.value.findIndex(function(s) { return s.id == dades.seat_id; });
                if (index !== -1) {
                    seients.value[index].status = dades.status;
                }
            }
        });
    };

    var obteEstilSeient = function(seient) {
      var estaSeleccionat = seleccionats.value.includes(seient.id);
      
      if (estaSeleccionat) return 'bg-blue-600 text-white shadow-xl shadow-blue-200 ring-4 ring-blue-100 z-10';
      if (seient.status === 0) return 'bg-white border-2 border-gray-200 text-gray-400 hover:border-blue-500 hover:text-blue-600 hover:shadow-lg hover:shadow-blue-50';
      if (seient.status === 1) return 'bg-amber-400 text-white shadow-lg shadow-amber-100 pointer-events-none opacity-80';
      if (seient.status === 2) return 'bg-rose-500 text-white shadow-lg shadow-rose-100 pointer-events-none opacity-80';
      return 'bg-gray-100 text-gray-300';
    };

    var canviarSeleccio = function(seient) {
      if (seient.status !== 0) return;

      var index = seleccionats.value.indexOf(seient.id);
      if (index === -1) {
        if (seleccionats.value.length >= 5) {
          alert('Màxim 5 seients seleccionats.');
          return;
        }
        seleccionats.value.push(seient.id);
        // Notificar al socket (status 1 = reservat/en procés)
        socket.emit('reserva_seient', { movie_id: movieId, seat_id: seient.id, status: 1 });
      } else {
        seleccionats.value.splice(index, 1);
        // Notificar al socket que torna a estar lliure
        socket.emit('reserva_seient', { movie_id: movieId, seat_id: seient.id, status: 0 });
      }
    };

    var confirmarCompra = function() {
        // En el futur aquí aniríem a la pantalla de pagament
        // De moment simulem la compra que posa els seients en vermell
        alert('Redirigint a la pantalla de pagament per a ' + seleccionats.value.length + ' seients...');
        
        // Simular compra per veure el resultat en temps real (RED)
        seleccionats.value.forEach(function(sid) {
            socket.emit('reserva_seient', { movie_id: movieId, seat_id: sid, status: 2 });
        });
        
        // Enviar a Laravel per a persistència (en producció això ho faria la passarel·la de pagament)
        fetch('http://localhost:8000/api/movies/purchase', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ seat_ids: seleccionats.value })
        }).then(function() {
            seleccionats.value = [];
        });
    };

    onMounted(function() {
        carregarDades();
        inicialitzarSocket();
    });

    onUnmounted(function() {
        if (socket) socket.disconnect();
    });

    return {
      movie: movie,
      seients: seients,
      seleccionats: seleccionats,
      carregant: carregant,
      obteEstilSeient: obteEstilSeient,
      canviarSeleccio: canviarSeleccio,
      confirmarCompra: confirmarCompra
    };
  }
}
</script>
