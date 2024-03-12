FROM node:16-alpine AS base
WORKDIR /usr/local/src/backup
COPY package.* .

FROM base AS prod
RUN npm install --production
COPY . .
CMD ["npm", "start"]

FROM base AS dev
RUN npm install
COPY . .
CMD ["npm", "run", "dev"]
