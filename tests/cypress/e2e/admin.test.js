describe("Admin can login and open dashboard", () => {
  beforeEach(() => {
    cy.login();
  });

  afterEach(() => {
    cy.activatePlugin("commonsbooking");
    cy.activatePlugin("commonsbooking-bikes-and-trailers");
  });

  it("Open dashboard", () => {
    cy.visit(`/wp-admin`);
    cy.get("h1").should("contain", "Dashboard");
  });
});
