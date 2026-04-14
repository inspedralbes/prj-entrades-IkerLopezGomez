<template>
  <div class="p-6 md:p-10 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto bg-white rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-blue-50/50 border border-gray-100">
      
      <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
        <div>
          <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-2">Escull la teva zona</p>
          <h1 class="text-4xl font-black text-gray-900 tracking-tight">{{ concert.titol || 'Selecció de Localitats' }}</h1>
        </div>
        <NuxtLink to="/" class="group flex items-center gap-2 bg-gray-50 px-6 py-3 rounded-2xl text-sm font-black text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition-all active:scale-95">
          <span class="group-hover:-translate-x-1 transition-transform">←</span> Tornar al Catàleg
        </NuxtLink>
      </div>

      <!-- ESCENARI -->
      <div class="relative mb-16">
        <div class="w-full h-16 bg-gradient-to-b from-gray-800 to-gray-900 rounded-t-lg shadow-2xl flex items-center justify-center border-b-4 border-blue-500">
            <span class="text-[12px] font-black uppercase tracking-[1.5em] text-gray-100 drop-shadow-md">ESCENARI</span>
        </div>
      </div>

      <div v-if="carregant" class="flex flex-col items-center justify-center py-20 text-gray-300">
        <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
        <p class="text-[10px] font-black uppercase tracking-widest">Carregant aforament...</p>
      </div>

      <div v-else class="flex flex-col items-center gap-12">
        
        <!-- DISTRIBUCIÓ CONCERT -->
        <div class="relative w-full flex flex-col items-center bg-gray-50/50 p-8 md:p-12 rounded-[3.5rem] border border-gray-100 shadow-inner">
            
            <!-- CONTENIDOR CENTRAL (PISTES) -->
            <div class="flex flex-col gap-8 z-10">

                <!-- PISTA 1 (VIP) - Zona General -->
                <div class="flex flex-col items-center gap-4 bg-white p-6 rounded-[2.5rem] shadow-xl shadow-blue-50 border border-blue-100" style="width: 390px;">
                    <div class="flex w-full justify-between items-center">
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Pista 1 · Preferent</span>
                        <span class="text-[10px] font-black text-blue-400">{{ pistaDisponibles('P1') }} places lliures</span>
                    </div>
                    <!-- Zone block -->
                    <div class="w-full rounded-2xl border-2 border-blue-200 bg-blue-50/50 flex flex-col items-center justify-center gap-5 py-10 px-6" style="min-height: 130px;">
                        <p class="text-2xl font-black text-blue-700">{{ parseFloat(filterSeats('P1')[0]?.preu || 0).toFixed(2) }}€ <span class="text-sm font-bold text-blue-400">/ entrada</span></p>
                        <div class="flex items-center gap-6">
                            <button @click="llevarPista('P1')" :disabled="pistaSeleccionats('P1') === 0" class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-black text-xl hover:bg-blue-200 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-90">−</button>
                            <div class="flex flex-col items-center">
                                <span class="text-4xl font-black text-blue-700 leading-none">{{ pistaSeleccionats('P1') }}</span>
                                <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest mt-1">entrades</span>
                            </div>
                            <button @click="afegirPista('P1')" :disabled="pistaDisponibles('P1') === 0 || totalSeleccionats >= 5" class="w-10 h-10 rounded-full bg-blue-600 text-white font-black text-xl hover:bg-blue-700 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-90">+</button>
                        </div>
                    </div>
                </div>

                <!-- PISTA 2 (GENERAL) - Zona General -->
                <div class="flex flex-col items-center gap-4 bg-white p-6 rounded-[2.5rem] shadow-lg border border-gray-100" style="width: 390px;">
                    <div class="flex w-full justify-between items-center">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Pista 2 · General</span>
                        <span class="text-[10px] font-black text-gray-400">{{ pistaDisponibles('P2') }} places lliures</span>
                    </div>
                    <!-- Zone block -->
                    <div class="w-full rounded-2xl border-2 border-gray-200 bg-gray-50 flex flex-col items-center justify-center gap-5 py-10 px-6" style="min-height: 130px;">
                        <p class="text-2xl font-black text-gray-700">{{ parseFloat(filterSeats('P2')[0]?.preu || 0).toFixed(2) }}€ <span class="text-sm font-bold text-gray-400">/ entrada</span></p>
                        <div class="flex items-center gap-6">
                            <button @click="llevarPista('P2')" :disabled="pistaSeleccionats('P2') === 0" class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 font-black text-xl hover:bg-gray-300 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-90">−</button>
                            <div class="flex flex-col items-center">
                                <span class="text-4xl font-black text-gray-700 leading-none">{{ pistaSeleccionats('P2') }}</span>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">entrades</span>
                            </div>
                            <button @click="afegirPista('P2')" :disabled="pistaDisponibles('P2') === 0 || totalSeleccionats >= 5" class="w-10 h-10 rounded-full bg-gray-800 text-white font-black text-xl hover:bg-gray-700 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-90">+</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- GRADES (U-SHAPE ENVOLTANT) -->
            <!-- Esquerra -->
            <div class="absolute left-6 top-1/2 -translate-y-1/2 flex flex-col gap-2">
                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest -rotate-90 mb-4">Grada L</span>
                <div v-for="seient in filterSeats('L')" :key="seient.id"
                    @click="canviarSeleccio(seient)"
                    :class="['w-8 h-8 rounded-lg flex items-center justify-center text-[8px] font-black transition-all cursor-pointer select-none active:scale-90', 
                             obteEstilSeient(seient)]"
                    :title="'L' + seient.number + ' (' + seient.preu + '€)'">
                    L{{ seient.number }}
                </div>
            </div>

            <!-- Dreta -->
            <div class="absolute right-6 top-1/2 -translate-y-1/2 flex flex-col gap-2">
                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest rotate-90 mb-4">Grada R</span>
                <div v-for="seient in filterSeats('R')" :key="seient.id"
                    @click="canviarSeleccio(seient)"
                    :class="['w-8 h-8 rounded-lg flex items-center justify-center text-[8px] font-black transition-all cursor-pointer select-none active:scale-90', 
                             obteEstilSeient(seient)]"
                    :title="'R' + seient.number + ' (' + seient.preu + '€)'">
                    R{{ seient.number }}
                </div>
            </div>

            <!-- Inferior -->
            <div class="mt-12 flex flex-col items-center gap-4">
                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Grada General</span>
                <div class="flex flex-wrap justify-center gap-2 max-w-2xl">
                    <div v-for="seient in filterSeats('G')" :key="seient.id"
                        @click="canviarSeleccio(seient)"
                        :class="['w-8 h-8 rounded-lg flex items-center justify-center text-[8px] font-black transition-all cursor-pointer select-none active:scale-90', 
                                 obteEstilSeient(seient)]"
                        :title="'G' + seient.number + ' (' + seient.preu + '€)'">
                        G{{ seient.number }}
                    </div>
                </div>
            </div>
        </div>

        <!-- LLEGENDA I ACCIONS -->
        <div class="flex flex-col gap-8 w-full items-center mt-8">
            <div class="grid grid-cols-4 gap-4 p-8 bg-white border border-gray-100 rounded-[2rem] w-full max-w-2xl shadow-xl shadow-blue-50">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-6 h-6 bg-white border-2 border-gray-200 rounded-lg shadow-sm"></div>
                    <span class="text-[8px] font-black uppercase text-gray-400">Lliure</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-6 h-6 bg-blue-600 rounded-lg shadow-md"></div>
                    <span class="text-[8px] font-black uppercase text-gray-400">Seleccionat</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-6 h-6 bg-amber-400 rounded-lg shadow-md"></div>
                    <span class="text-[8px] font-black uppercase text-gray-400">Reservat</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-6 h-6 bg-rose-500 rounded-lg shadow-md"></div>
                    <span class="text-[8px] font-black uppercase text-gray-400">Ocupat</span>
                </div>
            </div>

            <div v-if="seleccionats.length > 0" class="w-full flex flex-col items-center gap-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="flex flex-col items-center gap-2">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Preu total estimat</p>
                    <p class="text-4xl font-black text-blue-600 tracking-tighter">{{ totalEstimado }}€</p>
                </div>
                <button @click="confirmarCompra" class="bg-blue-600 text-white px-12 py-5 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-2xl shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all text-sm group">
                    Procedir al pagament <span class="ml-2 group-hover:translate-x-1 transition-transform">→</span>
                </button>
            </div>
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
    var concert = ref({});
    var seients = ref([]);
    var seleccionats = ref([]);
    var carregant = ref(true);
    var socket = null;

    var carregarDades = function() {
      carregant.value = true;
      fetch('/api/concerts/' + concertId)
        .then(function(res) { return res.json(); })
        .then(function(data) { concert.value = data; })
        .catch(function(err) { console.error('Error concert:', err); });

      fetch('/api/concerts/' + concertId + '/seats')
        .then(function(res) { return res.json(); })
        .then(function(data) { seients.value = data; })
        .catch(function(err) { console.error('Error seients:', err); })
        .finally(function() { carregant.value = false; });
    };

    var inicialitzarSocket = function() {
        socket = io('http://localhost:3000');
        socket.on('actualitzacio_seient', function(dades) {
            if (dades.concert_id == concertId) {
                var index = seients.value.findIndex(function(s) { return s.id == dades.seat_id; });
                if (index !== -1) {
                    seients.value[index].status = dades.status;
                    if (dades.status !== 0) {
                        var sIndex = seleccionats.value.findIndex(function(id) { return id == dades.seat_id; });
                        if (sIndex !== -1) {
                            seleccionats.value.splice(sIndex, 1);
                        }
                    }
                }
            }
        });
    };

    var filterSeats = function(row) {
        return seients.value.filter(function(s) { return s.row === row; });
    };

    // Number of available (not reserved/sold) seats in a Pista zone
    var pistaDisponibles = function(row) {
        return seients.value.filter(function(s) {
            return s.row === row && s.status === 0 && !seleccionats.value.includes(s.id);
        }).length;
    };

    // Number of seats currently selected for a Pista zone
    var pistaSeleccionats = function(row) {
        return seients.value.filter(function(s) {
            return s.row === row && seleccionats.value.includes(s.id);
        }).length;
    };

    var totalSeleccionats = computed(function() {
        return seleccionats.value.length;
    });

    // Add one spot from a Pista zone
    var afegirPista = function(row) {
        if (seleccionats.value.length >= 5) return;
        var lliure = seients.value.find(function(s) {
            return s.row === row && s.status === 0 && !seleccionats.value.includes(s.id);
        });
        if (lliure) seleccionats.value.push(lliure.id);
    };

    // Remove one spot from a Pista zone
    var llevarPista = function(row) {
        var idx = seleccionats.value.findIndex(function(id) {
            var s = seients.value.find(function(x) { return x.id === id; });
            return s && s.row === row;
        });
        if (idx !== -1) seleccionats.value.splice(idx, 1);
    };

    var obteEstilSeient = function(seient) {
      var estaSeleccionat = seleccionats.value.includes(seient.id);
      if (estaSeleccionat) return 'bg-blue-600 text-white shadow-xl shadow-blue-200 ring-4 ring-blue-100 z-10';
      if (seient.status === 0) return 'bg-white border-2 border-gray-200 text-gray-300 hover:border-blue-500 hover:shadow-lg';
      if (seient.status === 1) return 'bg-amber-400 text-white shadow-lg shadow-amber-100 pointer-events-none opacity-80';
      if (seient.status === 2) return 'bg-rose-500 text-white shadow-lg shadow-rose-100 pointer-events-none opacity-80';
      return 'bg-gray-100 text-gray-200';
    };

    var canviarSeleccio = function(seient) {
      var index = seleccionats.value.indexOf(seient.id);
      if (index !== -1) { seleccionats.value.splice(index, 1); return; }
      if (seient.status !== 0) return;
      if (seleccionats.value.length >= 5) { alert('Màxim 5 entrades seleccionades.'); return; }
      seleccionats.value.push(seient.id);
    };

    var totalEstimado = computed(function() {
        var total = 0;
        seients.value.forEach(function(s) {
            if (seleccionats.value.includes(s.id)) {
                total += parseFloat(s.preu);
            }
        });
        return total.toFixed(2);
    });

    var confirmarCompra = function() {
        if (seleccionats.value.length === 0) return;
        router.push({
            path: '/concerts/' + concertId + '/purchase',
            query: { seats: seleccionats.value.join(',') }
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
      concert: concert,
      seients: seients,
      seleccionats: seleccionats,
      carregant: carregant,
      filterSeats: filterSeats,
      pistaDisponibles: pistaDisponibles,
      pistaSeleccionats: pistaSeleccionats,
      totalSeleccionats: totalSeleccionats,
      afegirPista: afegirPista,
      llevarPista: llevarPista,
      obteEstilSeient: obteEstilSeient,
      canviarSeleccio: canviarSeleccio,
      totalEstimado: totalEstimado,
      confirmarCompra: confirmarCompra
    };
  }
}
</script>
