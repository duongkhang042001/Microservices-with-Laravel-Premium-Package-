// https://docs.cypress.io/api/introduction/api.html

describe('My First Test', () => {
  it('Visits the app root url', () => {
    cy.visit('127.0.0.1:8080')
    cy.contains('h1', 'Welcome to Your Vue.js + TypeScript App')
  })
})
