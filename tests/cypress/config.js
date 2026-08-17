const { defineConfig } = require('cypress');

module.exports = defineConfig({
  chromeWebSecurity: false,
  fixturesFolder: 'tests/cypress/fixtures',
  screenshotsFolder: 'tests/cypress/screenshots',
  videosFolder: 'tests/cypress/videos',
  downloadsFolder: 'tests/cypress/downloads',
  video: false,
  e2e: {
    baseUrl: 'http://localhost:1001/',
    specPattern: 'tests/cypress/e2e/**/*.test.{js,jsx,ts,tsx}',
    supportFile: 'tests/cypress/support/e2e.js'
  },
});
