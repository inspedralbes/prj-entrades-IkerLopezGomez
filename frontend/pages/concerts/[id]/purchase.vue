<template>
  <div class="p-6 md:p-10 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-blue-50/50 border border-gray-100">
      
      <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
        <div>
          <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-2">Pas Final</p>
          <h1 class="text-4xl font-black text-gray-900 tracking-tight">Resum de la Compra</h1>
        </div>
        <button @click="tornar" class="group flex items-center gap-2 bg-gray-50 px-6 py-3 rounded-2xl text-sm font-black text-gray-500 hover:bg-gray-100 transition-all active:scale-95">
          <span class="group-hover:-translate-x-1 transition-transform">←</span> Modificar localitats
        </button>
      </div>

      <div v-if="carregant" class="flex flex-col items-center justify-center py-20 text-gray-300">
        <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
        <p class="text-[10px] font-black uppercase tracking-widest">Processant dades...</p>
      </div>

      <div v-else class="flex flex-col gap-10">
        <div class="bg-gray-50 p-8 rounded-[2.5rem] border border-gray-100 shadow-inner">
          <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-widest text-center">Detalls del Concert</h2>
          
          <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
              <div class="flex flex-col items-center text-center gap-2">
                  <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Esdeveniment</span>
                  <p class="text-2xl font-black text-gray-900">{{ concert.titol }}</p>
                  <p class="text-sm font-bold text-gray-500">{{ concert.artista }}</p>
              </div>
          </div>

          <div class="space-y-4">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Localitats Seleccionades</p>
            <div class="grid grid-cols-1 gap-3">
                <div v-for="s in infoSeients" :key="s.id" class="flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-[10px] font-black text-white', getColorByRow(s.row)]">
                            {{ s.row }}{{ s.number }}
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-900">{{ getLabelByRow(s.row) }}</p>
                            <p class="text-[10px] font-bold text-gray-400">Butaca seleccionada</p>
                        </div>
                    </div>
                    <span class="text-lg font-black text-blue-600">{{ s.preu }}€</span>
                </div>
            </div>

            <div class="flex justify-between items-center py-8 px-4 mt-6 bg-blue-600 rounded-[2.5rem] text-white shadow-xl shadow-blue-100">
              <span class="text-lg font-black uppercase tracking-[0.2em]">Import Total</span>
              <span class="text-4xl font-black">{{ total }}€</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col items-center gap-6">
          <button @click="comprar" :disabled="comprant" class="w-full bg-blue-600 text-white py-6 rounded-[2rem] font-black uppercase tracking-[0.3em] shadow-2xl shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all disabled:opacity-50 disabled:pointer-events-none text-lg">
            {{ comprant ? 'Processant...' : 'Confirmar i Pagar' }}
          </button>
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
            Les teves entrades es reservaran durant un període limitat.
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
    var concertId = route.params.id;
    var seatIds = route.query.seats ? route.query.seats.split(',') : [];
    
    var concert = ref({});
    var infoSeients = ref([]);
    var carregant = ref(true);
    var comprant = ref(false);
    var socket = null;
    var finalitzat = false;

    var carregarDades = function() {
      carregant.value = true;
      fetch('http://localhost:8000/api/concerts/' + concertId)
        .then(function(res) { return res.json(); })
        .then(function(data) { concert.value = data; })
        .catch(function(err) { console.error('Error:', err); });

      fetch('http://localhost:8000/api/concerts/' + concertId + '/seats')
        .then(function(res) { return res.json(); })
        .then(function(data) { 
           infoSeients.value = data.filter(function(s) { return seatIds.includes(s.id.toString()); });
        })
        .finally(function() { carregant.value = false; });
    };

    var total = computed(function() {
      var sum = 0;
      infoSeients.value.forEach(function(s) { sum += parseFloat(s.preu); });
      return sum.toFixed(2);
    });

    var inicialitzarSocket = function() {
        socket = io('http://localhost:3000');

        socket.on('connect', function() {
            // Notify reservation (YELLOW) for all selected seats
            seatIds.forEach(function(sid) {
                socket.emit('reserva_seient', { concert_id: concertId, seat_id: sid, status: 1 });
            });
        });

        socket.on('actualitzacio_seient', function(dades) {
            if (finalitzat) return; // Ignore events triggered by our own purchase
            if (dades.concert_id == concertId && dades.status === 2) {
                if (seatIds.includes(dades.seat_id.toString())) {
                    alert('Atenció: Una de les localitats ha estat comprada per un altre usuari.');
                    router.push('/');
                }
            }
        });
    };

    var getColorByRow = function(row) {
        if (row === 'P1') return 'bg-blue-600';
        if (row === 'P2') return 'bg-gray-400';
        return 'bg-indigo-500';
    };

    var getLabelByRow = function(row) {
        if (row === 'P1') return 'Pista Preferent (P1)';
        if (row === 'P2') return 'Pista General (P2)';
        if (row === 'L') return 'Grada Esquerra';
        if (row === 'R') return 'Grada Dreta';
        return 'Grada General';
    };

    var comprar = function() {
        comprant.value = true;
        fetch('http://localhost:8000/api/concerts/purchase', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ seat_ids: seatIds })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                finalitzat = true;
                seatIds.forEach(function(sid) {
                    socket.emit('reserva_seient', { concert_id: concertId, seat_id: sid, status: 2 });
                });
                alert('Compra realitzada amb èxit!');
                router.push('/');
            } else {
                // Un-reserve on failure
                seatIds.forEach(function(sid) {
                    socket.emit('reserva_seient', { concert_id: concertId, seat_id: sid, status: 0 });
                });
                alert('Error: ' + data.message);
                router.push('/concerts/' + concertId + '/seats');
            }
        })
        .catch(function(err) {
            // Un-reserve on error
            seatIds.forEach(function(sid) {
                socket.emit('reserva_seient', { concert_id: concertId, seat_id: sid, status: 0 });
            });
            console.error(err);
            alert('Error en el servidor.');
        })
        .finally(function() { comprant.value = false; });
    };

    var tornar = function() { router.push('/concerts/' + concertId + '/seats'); };

    onMounted(function() {
        if (seatIds.length === 0) { router.push('/'); return; }
        carregarDades();
        inicialitzarSocket();
    });

    onUnmounted(function() {
        if (socket) {
            if (!finalitzat) {
                // Un-reserve seats if user leaves without completing purchase
                seatIds.forEach(function(sid) {
                    socket.emit('reserva_seient', { concert_id: concertId, seat_id: sid, status: 0 });
                });
            }
            socket.disconnect();
        }
    });

    return {
      concert: concert,
      infoSeients: infoSeients,
      carregant: carregant,
      comprant: comprant,
      total: total,
      comprar: comprar,
      tornar: tornar,
      getColorByRow: getColorByRow,
      getLabelByRow: getLabelByRow
    };
  }
}
</script>
