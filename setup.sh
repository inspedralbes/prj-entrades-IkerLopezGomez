#!/bin/bash

# setup.sh - Configuració de la infraestructura i dependències
# Aquest script automatitza la instal·lació de dependències i llança l'entorn Docker.

echo "Iniciant la configuració del projecte..."

# Dependències del Backend (Només si npm està disponible)
echo "Instal·lant dependències del Backend..."
if command -v npm &> /dev/null
then
    cd backend && npm install && cd ..
else
    echo "Avís: npm no s'ha trobat. Si us plau, instal·leu Node.js i npm per muntar el projecte."
fi

# Dependències del Frontend (Només si npm està disponible)
echo "Instal·lant dependències del Frontend..."
if command -v npm &> /dev/null
then
    cd frontend && npm install && cd ..
else
    echo "Avís: npm no s'ha trobat. Si us plau, instal·leu Node.js i npm per muntar el projecte."
fi

# Infraestructura Docker
echo "Iniciant la infraestructura Docker..."
if command -v docker-compose &> /dev/null
then
    cd docker && docker-compose up -d && cd ..
elif command -v docker &> /dev/null && docker compose version &> /dev/null
then
    cd docker && docker compose up -d && cd ..
else
    echo "Avís: docker-compose no s'ha trobat. Si us plau, instal·leu Docker i docker-compose."
fi

echo "Intent de configuració finalitzat. Reviseu els registres superiors."
