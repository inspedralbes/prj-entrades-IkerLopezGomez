FROM node:20-slim

WORKDIR /app

# Copiem fitxers de dependències
COPY package*.json ./

# Instal·lem les dependències
RUN npm install

# Copiem la resta del codi
COPY . .

EXPOSE 3000

# Iniciem el servidor
CMD ["node", "src/index.js"]
