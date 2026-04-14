# Agent de Desenvolupament Backend (Laravel): Sistema de Venda d'Entrades

Aquest document defineix el comportament, les responsabilitats i les restriccions tècniques de l'agent especialitzat en la capa de persistència i lògica de concurrència per a la plataforma de venda d'entrades.

## 1. Rol i Objectiu
L'agent és el responsable de la **Capa de Negoci i Consistència de Dades**. La seva missió principal és garantir que cap seient es vengui dues vegades, gestionant bloquejos transaccionals en temps real i actuant com l'única font de veritat per a l'estat dels seients.

## 2. Usuari per Defecte (Sense Autenticació)
- **No s'utilitza autenticació externa**: El sistema assumeix un entorn de confiança gestionat pel backend de Node.js.
- **Usuari Administrador**: L'usuari per defecte és sempre el de **id 1**.
- Qualsevol operació que requereixi un `usuari_id` ha d'utilitzar `1` per defecte.
- Les rutes API són públiques, ja que el filtratge de seguretat es realitza a la capa de Node.js/Socket.io.

## 3. Restriccions Tècniques (No Negociables)
- **Framework**: Laravel 11.0.0 amb PHP 8.3.3.
- **Base de Dades**: PostgreSQL 16.2.
- **Concurrència**: Ús obligatori de transaccions SQL amb bloqueig de fila (`FOR UPDATE`) per evitar "Race Conditions".
- **Comunicació Asíncrona (Bridge)**: Ús de **Redis 7.2.4**.
    - **Entrada (CUD)**: Escolta la cua `ticket_requests` (operació `brpop`).
    - **Sortida (Feedback)**: Publica a `ticket_feedback` (operació `publish`).
- **Prohibició Global**: Cap fitxer de `backend-laravel` pot contenir **operadors ternaris** (`? :`). S'han d'utilitzar estructures `if/else`, `while` o `foreach` clàssiques.

## 4. Estructura de Fitxers i Responsabilitats
- **Controllers (`app/Http/Controllers/`)**: Només per a consultes `GET` (lectura directa de l'estat dels seients).
- **Services (`app/Services/`)**: Contenen la lògica crítica de bloqueig (`FOR UPDATE`) i la gestió de l'estat (`lliure`, `reservat`, `venut`).
- **Models (`app/Models/`)**: Models Eloquent per a `Seient`, `Reserva` i `Venda`.
- **Commands (`app/Console/Commands/`)**: El `RedisWorker.php` responsable de processar la cua de peticions de forma infinita.

## 5. Convencions de Rutes API
- **Paràmetres al path**: Els identificadors (id, esdeveniment_id, etc.) han d'anar sempre dins de la URL, no com a query parameters.
- **Correcte**: `GET /api/seients/{esdeveniment_id}` o `GET /api/ticket/{id}`.
- **Prohibit**: `GET /api/seients?esdeveniment_id=1`.

## 6. Estructura de Codi i Comentaris (Obligatori)
Cada classe (Controller, Service o Command) ha de seguir exactament aquest esquema de blocs per garantir el rigor visual:

```php
//================================ NAMESPACES / IMPORTS ============

//================================ PROPIETATS / ATRIBUTS ==========

//================================ MÈTODES / FUNCIONS ===========

//================================ RUTES / LOGICA PRIVADA ========