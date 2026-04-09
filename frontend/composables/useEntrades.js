// Composable per a la gestió de les entrades (Pel·lícules i Concerts) des de la BD.
import { ref, onMounted } from 'vue';

export var useEntrades = function () {
    // A. Definició de l'estat reactiu
    var categoriaActual = ref('pel·lícules');
    var dadesPelicules = ref([]);
    var dadesConcerts = ref([]);
    var carregant = ref(true);

    // B. Funció per carregar dades des de l'API (Laravel a port 8000)
    var carregarDades = function () {
        carregant.value = true;

        // Carreguem pel·lícules des de Laravel
        fetch('/api/movies')
            .then(function (res) { return res.json(); })
            .then(function (data) { dadesPelicules.value = data; })
            .catch(function (err) { console.error('Error carregant pel·lícules:', err); });

        // Carreguem concerts des de Laravel
        fetch('/api/concerts')
            .then(function (res) { return res.json(); })
            .then(function (data) { dadesConcerts.value = data; })
            .catch(function (err) { console.error('Error carregant concerts:', err); })
            .finally(function () { carregant.value = false; });
    };

    // C. Canvi de categoria
    var canviarCategoria = function (nova) {
        categoriaActual.value = nova;
    };

    // Carregar en muntar
    onMounted(carregarDades);

    return {
        categoriaActual: categoriaActual,
        dadesPelicules: dadesPelicules,
        dadesConcerts: dadesConcerts,
        carregant: carregant,
        canviarCategoria: canviarCategoria,
        refrescar: carregarDades
    };
};
