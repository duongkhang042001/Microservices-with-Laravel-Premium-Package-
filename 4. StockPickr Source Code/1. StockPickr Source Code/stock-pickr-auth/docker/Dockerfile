FROM node:16-alpine AS base
WORKDIR /usr/local/src/auth
COPY package.* .

FROM base AS prod
RUN npm install --production
COPY . .
EXPOSE 3000
CMD ["npm", "start"]

FROM base AS dev
RUN npm install
COPY . .
EXPOSE 3000
CMD ["npm", "run", "dev"]
