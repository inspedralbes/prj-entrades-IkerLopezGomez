<template>
  <div class="p-6 md:p-10 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-blue-50/50 border border-gray-100">
      
      <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
        <div>
          <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-2">Pas Final</p>
          <h1 class="text-4xl font-black text-gray-900 tracking-tight">Resum de la Compra</h1>
        </div>
        <button @click="tornar" class="group flex items-center gap-2 bg-gray-50 px-6 py-3 rounded-2xl text-sm font-black text-gray-500 hover:bg-gray-100 transition-all active:scale-95">
          <span class="group-hover:-translate-x-1 transition-transform">←</span> Modificar seients
        </button>
      </div>

      <div v-if="carregant" class="flex flex-col items-center justify-center py-20 text-gray-300">
        <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
        <p class="text-[10px] font-black uppercase tracking-widest">Processant dades...</p>
      </div>

      <div v-else class="flex flex-col gap-10">
        <div class="bg-gray-50 p-8 rounded-[2.5rem] border border-gray-100">
          <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-widest">Detalls de l'entrada</h2>
          <div class="flex flex-col gap-4">
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
              <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Pel·lícula</span>
              <span class="text-sm font-bold text-gray-900">{{ movie.titol }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
              <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Seients</span>
              <div class="flex gap-2">
                <span v-for="s in infoSeients" :key="s.id" class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-black">
                  {{ s.row }}{{ s.number }}
                </span>
              </div>
            </div>
            <div class="flex justify-between items-center py-6 mt-4">
              <span class="text-lg font-black text-gray-900 uppercase tracking-[0.2em]">Total</span>
              <span class="text-3xl font-black text-blue-600">{{ total }}€</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col items-center gap-6">
          <button @click="comprar" :disabled="comprant" class="w-full bg-blue-600 text-white py-6 rounded-[2rem] font-black uppercase tracking-[0.3em] shadow-2xl shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all disabled:opacity-50 disabled:pointer-events-none text-lg">
            {{ comprant ? 'Processant...' : 'Confirmar i Pagar' }}
          </button>
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
            En fer clic, els teus seients quedaran confirmats definitivament.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRoute, useRouter } from '#app';
import { io } from 'socket.io-client';

export default {
  setup: function() {
    var route = useRoute();
    var router = useRouter();
    var movieId = route.params.id;
    var seatIds = route.query.seats ? route.query.seats.split(',') : [];
    
    var movie = ref({});
    var infoSeients = ref([]);
    var carregant = ref(true);
    var comprant = ref(false);
    var socket = null;
    var finalitzat = false;

    var carregarDades = function() {
      carregant.value = true;
      
      // Carregar detalls de la pel·lícula
      fetch('http://localhost:8000/api/movies/' + movieId)
        .then(function(res) { return res.json(); })
        .then(function(data) { movie.value = data; })
        .catch(function(err) { console.error('Error:', err); });

      // Carregar informació específica dels seients
      fetch('http://localhost:8000/api/movies/' + movieId + '/seats')
        .then(function(res) { return res.json(); })
        .then(function(data) { 
           infoSeients.value = data.filter(function(s) { return seatIds.includes(s.id.toString()); });
        })
        .finally(function() { carregant.value = false; });
    };

    var total = computed(function() {
      return (infoSeients.value.length * (movie.value.preu || 8)).toFixed(2);
    });

    var inicialitzarSocket = function() {
        socket = io('http://localhost:3000');
        
        socket.on('connect', function() {
            // Notificar reserva temporal (YELLOW) per a tots els seients seleccionats
            seatIds.forEach(function(sid) {
                socket.emit('reserva_seient', { movie_id: movieId, seat_id: sid, status: 1 });
            });
        });

        socket.on('actualitzacio_seient', function(dades) {
            // Si algú compra (2) un dels nostres seients mentre estem aquí
            if (dades.movie_id == movieId && dades.status === 2) {
                if (seatIds.includes(dades.seat_id.toString())) {
                    alert('Atenció: Un dels seients ha estat comprat per un altre usuari. Torna al catàleg.');
                    router.push('/');
                }
            }
        });
    };

    var comprar = function() {
        comprant.value = true;
        fetch('http://localhost:8000/api/movies/purchase', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ seat_ids: seatIds })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                finalitzat = true;
                // Notificar compra final (RED)
                seatIds.forEach(function(sid) {
                    socket.emit('reserva_seient', { movie_id: movieId, seat_id: sid, status: 2 });
                });
                alert('Compra realitzada amb èxit!');
                router.push('/');
            } else {
                alert('Error en la compra: ' + data.message);
                router.push('/movies/' + movieId + '/seats');
            }
        })
        .catch(function(err) { 
            console.error(err);
            alert('Hi ha hagut un error en el servidor.');
        })
        .finally(function() { comprant.value = false; });
    };

    var tornar = function() {
        router.push('/movies/' + movieId + '/seats');
    };

    onMounted(function() {
        if (seatIds.length === 0) {
            router.push('/');
            return;
        }
        carregarDades();
        inicialitzarSocket();
    });

    onUnmounted(function() {
        if (socket) {
            // Si sortim sense completar la compra, alliberem els seients (WHITE)
            if (!finalitzat) {
                seatIds.forEach(function(sid) {
                    socket.emit('reserva_seient', { movie_id: movieId, seat_id: sid, status: 0 });
                });
            }
            socket.disconnect();
        }
    });

    return {
      movie: movie,
      infoSeients: infoSeients,
      carregant: carregant,
      comprant: comprant,
      total: total,
      comprar: comprar,
      tornar: tornar
    };
  }
}
</script>
