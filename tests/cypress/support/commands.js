// Shared E2E helpers for the GBFS API tests.

const GBFS_FIXTURE_PATH =
  "/var/www/html/wp-content/plugins/cb-vehicles/tests/cypress/wordpress-files/content-example.xml";
const GBFS_FIXTURE_IMPORT_MARKER = "cb_vehicles_e2e_fixtures_imported";

// Run a WP-CLI command through the custom task defined in cypress.config.js.
Cypress.Commands.add("wpCli", (command, options = {}) =>
  cy.task("wpCli", command, options)
);

// Activate the plugins and make sure the API endpoints are reachable.
// CommonsBooking needs the API flags; cb-vehicles needs its GBFS defaults for
// items that do not define every field at item level.
Cypress.Commands.add("setupGbfsApi", () => {
  return cy
    .wpCli("plugin activate commonsbooking cb-vehicles wordpress-importer")
    .then(() =>
      cy.wpCli(
        `eval 'update_option("commonsbooking_options_api", array_merge((array) get_option("commonsbooking_options_api", array()), array("api-activated" => "on", "apikey_not_required" => "on", "api_share_group" => array()))); update_option("commonsbooking_options_cb_vehicles", array_merge((array) get_option("commonsbooking_options_cb_vehicles", array()), array("default_form_factor" => "cargo_bicycle", "default_propulsion_type" => "human", "default_max_range" => "20000")));'`
      )
    );
});

// Import the CommonsBooking fixture WXR once. The WXR has been extended with
// cb-vehicles postmeta so the imported items already carry GBFS metadata.
Cypress.Commands.add("importGbfsFixtures", () => {
  return cy
    .wpCli(
      `eval 'echo get_option("${GBFS_FIXTURE_IMPORT_MARKER}") ? "yes" : "no";'`
    )
    .then(({ stdout }) => {
      if (stdout.trim() === "yes") {
        return;
      }

      return cy
        .wpCli(`import ${GBFS_FIXTURE_PATH} --authors=create`, {
          timeout: 180000,
        })
        .then(() =>
          cy.wpCli(
            `eval 'update_option("${GBFS_FIXTURE_IMPORT_MARKER}", 1);'`
          )
        );
    });
});

Cypress.Commands.add("setupGbfsFixtures", () => {
  cy.setupGbfsApi();
  return cy.importGbfsFixtures();
});
