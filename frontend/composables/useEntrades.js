// Composable per a la gestió de les entrades (Pel·lícules i Concerts).
// Manté l'estat de la categoria seleccionada i la llista de dades.
import { ref, computed } from 'vue';

export var useEntrades = function () {
    // A. Definició de l'estat reactiu (ES5 var)
    var categoriaActual = ref('pel·lícules');

    // B. Dades de mostra (Mock Data)
    var dadesPelicules = [
        { id: 1, titol: "Dune: Part Two", descripcio: "L'epopeia de Paul Atreides continua.", preu: 9.50 },
        { id: 2, titol: "Interstellar", descripcio: "Viatge a través del temps i l'espai.", preu: 8.00 },
        { id: 3, titol: "Oppenheimer", descripcio: "La història de la bomba atòmica.", preu: 10.00 }
    ];

    var dadesConcerts = [
        { id: 101, titol: "Estopa - Gira 25 anys", descripcio: "Concert al Palau Sant Jordi.", preu: 45.00 },
        { id: 102, titol: "Coldplay - Music of the Spheres", descripcio: "Espectacle visual i sonor únic.", preu: 85.00 },
        { id: 103, titol: "The Tyets - Èpic Solete", descripcio: "Música festiva en català.", preu: 25.00 }
    ];

    // C. Funcions de lògica (ES5 function)
    var canviarCategoria = function (nova) {
        categoriaActual.value = nova;
    };

    // D. Propietat computada per a la llista filtrada
    var llistaActual = computed(function () {
        if (categoriaActual.value === 'pel·lícules') {
            return dadesPelicules;
        } else {
            return dadesConcerts;
        }
    });

    return {
        categoriaActual: categoriaActual,
        llistaActual: llistaActual,
        canviarCategoria: canviarCategoria
    };
};
