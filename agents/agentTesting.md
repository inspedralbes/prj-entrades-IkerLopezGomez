# Agent de Testing Automàtic (Filosofia Robustesa i Simplicitat)

Aquest document defineix les regles per a l'agent encarregat de realitzar proves automàtiques al projecte. L'objectiu és garantir la funcionalitat sense dependre de frameworks pesats i mantenint l'estil "Old School".

## 1. Objectiu de l'Agent
Validar que el codi (Frontend i Backend) funciona correctament segons els requisits, mantenint la compatibilitat amb la sintaxi ES5 i les regles del projecte.

## 2. Regles de Testing (No Negociables)
- **Llenguatge**: SEMPRE en Català!!
- **Sintaxi**: ES5 Estricte (`var`, `function`, sense `const/let/=>`).
- **Sense Emojis**: Els logs de test han de ser text net.
- **Enfocament**: 
    - Ús de funcions simples d'asserció per a proves unitàries.
    - Proves d'integració basades en respostes d'API i esdeveniments de sockets.
    - NO utilitzar frameworks de testing moderns (Jest, Vitest, Mocha) si no estan configurats amb compatibilitat total ES5. Preferiblement crear un script de test propi (`test.js`).

## 3. Estructura de Prova (Exemple)
```javascript
//================================ PROVES ======================================

/**
 * Funció d'asserció bàsica.
 * Pas A: Comparar valor real amb l'esperat.
 * Pas B: Imprimir resultat per consola.
 */
function assegurar(nomTest, real, esperat) {
    if (real === esperat) {
        console.log("PASS: " + nomTest);
    } else {
        console.error("FAIL: " + nomTest + " (Esperat: " + esperat + ", Real: " + real + ")");
    }
}

/**
 * Prova del càlcul de preus.
 */
function testPreuReserva() {
    var preuUnitari = 10;
    var quantitat = 2;
    var resultat = calcularPreuTotal(preuUnitari, quantitat);
     assegurar("Càlcul preu total", resultat, 20);
}

//================================ EXECUCIÓ ====================================
testPreuReserva();
```

## 4. Responsabilitats
- **Backend**: Verificar connexió a BD, reserves concurrents (via bucles `for` manuals) i autenticació.
- **Frontend**: Verificar càrrega de dades i canvis d'estat a Pinia.
- **Automatització**: El script `setup.sh` podria opcionalment llançar un `npm test`.

## 5. Idioma i Comentaris
- **Nom de les proves**: En català (ex: `testIniciSessio`).
- **Comentaris**: Explicatius, pas a pas (Pas A, Pas B...), en català.
