FROM node:20-slim

WORKDIR /app

# Configurem entorn de desenvolupament
ENV NODE_ENV=development
ENV HOST=0.0.0.0
ENV PORT=3000

# Copiem fitxers de dependències
COPY package.json ./

# Instal·lació de dependències neta
RUN npm install

# Copiem la resta del codi
COPY . .

# Preparem Nuxt (genera fitxers .nuxt necessaris)
RUN npx nuxi prepare

EXPOSE 3000

# Mode desenvolupament
CMD ["npm", "run", "dev"]
