// Regression test: CommonsBooking core registers vehicle_types.json itself.
// cb-vehicles only overrides it when it registers the exact same route first
// (rest_api_init priority 5). A different route makes the core default win.
//
// The fixtures are copied from the main CommonsBooking plugin
// (tests/cypress/wordpress-files/content-example.xml) and extended with
// _cbvehicles_* postmeta directly in the WXR.
const apiPath = "/wp-json/commonsbooking/v1/vehicle_types.json";

describe("CB Vehicles GBFS vehicle_types override", () => {
  before(() => {
    return cy.setupGbfsFixtures();
  });

  it("serves the cb-vehicles feed instead of the CommonsBooking default", () => {
    cy.request(apiPath).then((response) => {
      expect(response.status).to.eq(200);

      const body = response.body;

      // CommonsBooking core returns ttl 86400 and a single hardcoded entry.
      expect(body.ttl).to.eq(60);
      expect(body.version).to.eq("3.1-RC3");
      expect(body.data.vehicle_types).to.be.an("array");

      // The CommonsBooking fixture contains 10 cb_items. Requiring at least
      // those catches a missing/partial import as well as a core-only reply.
      expect(body.data.vehicle_types.length).to.be.at.least(10);

      const vehicleTypeIds = body.data.vehicle_types.map(
        (vehicleType) => vehicleType.vehicle_type_id
      );
      expect(vehicleTypeIds).not.to.include("commonsbooking_default");
    });
  });

  it("maps fixture item metadata to the GBFS vehicle type fields", () => {
    cy.request(apiPath).then((response) => {
      const vehicleTypes = response.body.data.vehicle_types;
      const vehicleType = vehicleTypes.find(
        (candidate) =>
          Array.isArray(candidate.make) &&
          candidate.make.some((entry) => entry.text === "Long-John")
      );

      expect(vehicleType, "vehicle type for fixture item 30").to.exist;

      // Item 30 in the WXR: "BasicTest - NoAdmin".
      // The cloaked ID is a rotating hash, never the raw post ID or the
      // CommonsBooking default identifier.
      expect(vehicleType.vehicle_type_id).to.match(/^[a-f0-9]{32}$/);
      expect(vehicleType.vehicle_type_id).not.to.eq("commonsbooking_default");
      expect(vehicleType.vehicle_type_id).not.to.eq("30");

      expect(vehicleType.form_factor).to.eq("cargo_bicycle");
      expect(vehicleType.propulsion_type).to.eq("electric_assist");
      expect(vehicleType.max_range_meters).to.eq(45);
      expect(vehicleType.wheel_count).to.eq(2);
      expect(vehicleType.max_permitted_speed).to.eq(25);
      expect(vehicleType.cargo_volume_capacity).to.eq(120);
      expect(vehicleType.cargo_load_capacity).to.eq(85);
      expect(vehicleType.return_constraint).to.eq("roundtrip_station");

      expect(vehicleType.make).to.have.length(1);
      expect(vehicleType.make[0].text).to.eq("Long-John");
      expect(vehicleType.make[0].language).to.be.a("string").and.not.be.empty;

      expect(vehicleType.model).to.have.length(1);
      expect(vehicleType.model[0].text).to.eq("Longi");
      expect(vehicleType.model[0].language).to.be.a("string").and.not.be.empty;

      // Three different fixture items were annotated in the WXR. Seeing all
      // of them proves that the imported postmeta is what drives the feed.
      const makes = vehicleTypes.reduce((result, candidate) => {
        if (Array.isArray(candidate.make)) {
          result.push(...candidate.make.map((entry) => entry.text));
        }
        return result;
      }, []);
      expect(makes).to.include.members([
        "Long-John",
        "Urban Arrow",
        "Riese & Müller",
      ]);
    });
  });
});
