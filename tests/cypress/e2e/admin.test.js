const itemTitle = `cb-vehicles-cypress-item-${Date.now()}`;

describe("CB Vehicles admin integration", () => {
  before(() => {
    cy.login();
    cy.wpCli("plugin activate commonsbooking cb-vehicles");
  });

  beforeEach(() => {
    cy.login();
  });

  it("opens the CB Vehicles settings tab", () => {
    cy.visit("/wp-admin/admin.php?page=commonsbooking_options_cb_vehicles");

    cy.contains(".nav-tab", "CB Vehicles").should("have.class", "nav-tab-active");
    cy.contains("h4", "General").should("be.visible");
    cy.contains("label", "Show frontend templates").should("be.visible");
    cy.contains("h4", "GBFS Default Vehicle Types").should("be.visible");
    cy.contains("label", "Default form factor").should("be.visible");
    cy.get("#default_form_factor").should("have.value", "cargo_bicycle");
    cy.get("#default_propulsion_type").should("have.value", "human");
  });

  it("loads vehicle fields on new CommonsBooking items and can create an item", () => {
    cy.visit("/wp-admin/post-new.php?post_type=cb_item");

    cy.get("#_cbvehicles_form_factor").should("exist");
    cy.get("#_cbvehicles_propulsion_type").should("exist");
    cy.get("#_cbvehicles_wheel_count").should("exist");
    cy.get("#_cbvehicles_make").should("exist");

    cy.wpCli(
      `post create --post_type=cb_item --post_title=${itemTitle} --post_status=publish --porcelain`
    ).then((result) => {
      const postId = result.stdout.trim();

      expect(postId).to.match(/^\d+$/);

      cy.wpCli(`post get ${postId} --field=post_title`).then((postResult) => {
        expect(postResult.stdout.trim()).to.equal(itemTitle);
      });

      cy.visit(`/wp-admin/post.php?post=${postId}&action=edit`);
      cy.get("#_cbvehicles_form_factor").should("exist");
      cy.get("#_cbvehicles_propulsion_type").should("exist");
    });
  });
});
